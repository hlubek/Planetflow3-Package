<?php
declare(ENCODING = 'utf-8');
namespace Planetflow3\Domain\Service;

/*                                                                        *
 * This script belongs to the FLOW3 package "Planetflow3".                *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License as published by the Free   *
 * Software Foundation, either version 3 of the License, or (at your      *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        *
 * You should have received a copy of the GNU General Public License      *
 * along with the script.                                                 *
 * If not, see http://www.gnu.org/licenses/gpl.html                       *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * A service to sync feeds from channels
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ChannelService {

	/**
	 * @inject
	 * @var \Planetflow3\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @inject
	 * @var \Planetflow3\Domain\Repository\ItemRepository
	 */
	protected $itemRepository;


	/**
	 * Fetches (new) items from the configured feed
	 *
	 * @param \Planetflow3\Domain\Model\Channel $channel
	 * @param \Closure $logger
	 * @return void
	 */
	public function fetchItems(\Planetflow3\Domain\Model\Channel $channel, $logger = NULL) {
		if ($logger === NULL) {
			$logger = function($message, $severity = LOG_INFO) {};
		}

		$simplePie = new \SimplePie();
		$simplePie->set_feed_url($channel->getFeedUrl());
		$simplePie->enable_cache(FALSE);
		$simplePie->init();

		$availableCategories = $this->categoryRepository->findAll();
		$availableCategoriesByName = array();
		foreach ($availableCategories as $availableCategory) {
			$availableCategoriesByName[$availableCategory->getName()] = $availableCategory;
		}

		$textcat = new \Libtextcat\Textcat();

		$existingUniversalIdentifiers = array();
		foreach ($channel->getItems() as $item) {
			$existingUniversalIdentifiers[$item->getUniversalIdentifier()] = TRUE;
		}

		$feedItems = $simplePie->get_items();
		foreach ($feedItems as $feedItem) {
			$item = new \Planetflow3\Domain\Model\Item();
			$item->setUniversalIdentifier($feedItem->get_id());
			if (isset($existingUniversalIdentifiers[$item->getUniversalIdentifier()])) {
				$logger('Skipped item "' . $item->getUniversalIdentifier() . '", already fetched.', LOG_DEBUG);
				continue;
			}
			$item->setLink($feedItem->get_link());
			$item->setTitle($feedItem->get_title());
			$item->setDescription($feedItem->get_description());
			$item->setContent($feedItem->get_content(TRUE));
			$item->setPublicationDate(new \DateTime($feedItem->get_date()));

			$feedItemCategories = $feedItem->get_categories();
			if (is_array($feedItemCategories)) {
				foreach ($feedItemCategories as $feedItemCategory) {
					$term = $feedItemCategory->get_term();
					if ($term === NULL || $term === '') {
						continue;
					}
					if (isset($availableCategoriesByName[$term])) {
						$category = $availableCategoriesByName[$term];
						$item->addCategory($category);
					} else {
						$logger('Skipped category "' . $term . '"', LOG_DEBUG);
					}

				}
			}

			if ($item->getCategories()->count() === 0 && $channel->getDefaultCategory() !== NULL) {
				$item->addCategory($channel->getDefaultCategory());
			}

			if ($item->matchesChannel($channel)) {
				$language = $textcat->classify($item->getDescription() . ' ' . $item->getContent());
				if ($language !== FALSE) {
					$logger('Detected language ' . $language . ' for item "' . $item->getUniversalIdentifier() . '"', LOG_DEBUG);
					$item->setLanguage($language);
				}

				$channel->addItem($item);
				$this->itemRepository->add($item);

				$existingUniversalIdentifiers[$item->getUniversalIdentifier()] = TRUE;
			} else {
				$logger('Skipped item "' . $item->getUniversalIdentifier() . '", not matched.', LOG_DEBUG);
			}
		}
	}

}
?>