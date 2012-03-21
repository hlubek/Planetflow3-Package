<?php
namespace Planetflow3\Domain\Service;

/*                                                                        *
 * This script belongs to the FLOW3 package "Planetflow3".                *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

use Planetflow3\Domain\Model\Channel as Channel;
use Planetflow3\Domain\Model\Item as Item;

/**
 * A service to sync feeds from channels
 *
 * @FLOW3\Scope("singleton")
 */
class ChannelService {

	/**
	 * @FLOW3\Inject
	 * @var \Planetflow3\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @FLOW3\Inject
	 * @var \Planetflow3\Domain\Repository\ItemRepository
	 */
	protected $itemRepository;

	/**
	 * @FLOW3\Inject
	 * @var \Planetflow3\Domain\Repository\ChannelRepository
	 */
	protected $channelRepository;

	/**
	 * @FLOW3\Inject
	 * @var \Libtextcat\Textcat
	 */
	protected $textcat;

	/**
	 * Array of available categories by name, will be lazily initialized
	 * @var array
	 */
	protected $availableCategories = NULL;

	/**
	 * Fetches (new) items from the configured feed of a channel
	 *
	 * Adds new items and updates channels with a new last fetch date.
	 *
	 * @param \Planetflow3\Domain\Model\Channel $channel
	 * @param \Closure $logCallback
	 * @return void
	 */
	public function fetchItems(Channel $channel, $logCallback = NULL) {
		if ($logCallback === NULL) {
			$logCallback = function(Item $item, $message, $severity = LOG_INFO) {};
		}

		$simplePie = $this->createSimplePie($channel);

		$existingUniversalIdentifiers = $channel->getItemsUniversalIdentifier();

		$feedItems = $simplePie->get_items();
		foreach ($feedItems as $feedItem) {
			$item = new Item();
			$item->setUniversalIdentifier($feedItem->get_id());
			if (isset($existingUniversalIdentifiers[$item->getUniversalIdentifier()])) {
				$logCallback($item, 'Skipped item, already fetched', LOG_DEBUG);
				continue;
			}

			$this->populateItemProperties($item, $feedItem);
			$this->populateItemCategories($item, $feedItem, $logCallback);

			if ($item->getCategories()->count() === 0 && $channel->getDefaultCategory() !== NULL) {
				$item->addCategory($channel->getDefaultCategory());
			}

			if ($item->matchesChannel($channel)) {
				$language = $this->textcat->classify($item->getDescription() . ' ' . $item->getContent());
				if ($language !== FALSE) {
					$item->setLanguage($language);
					$logCallback($item, 'Detected language ' . $language . ' for item', LOG_DEBUG);
				}

				$channel->addItem($item);
				$this->itemRepository->add($item);

				$logCallback($item, 'Item fetched and saved', LOG_INFO);

				$existingUniversalIdentifiers[$item->getUniversalIdentifier()] = TRUE;
			} else {
				$logCallback($item, 'Skipped item, filter not matched', LOG_DEBUG);
			}
		}

		$channel->setLastFetchDate(new \DateTime());
		$this->channelRepository->update($channel);
	}

	/**
	 * Assign categories to an item from a feed item
	 *
	 * @param $item \Planetflow3\Domain\Model\Item
	 * @param $feedItem \SimplePie_Item
	 * @param $logCallback \Closure
	 * @return void
	 */
	protected function populateItemCategories($item, $feedItem, $logCallback) {
		$feedItemCategories = $feedItem->get_categories();
		if (is_array($feedItemCategories)) {
			$availableCategoriesByName = $this->getAvailableCategories();
			foreach ($feedItemCategories as $feedItemCategory) {
				$term = $feedItemCategory->get_term();
				if ($term === NULL || $term === '') {
					continue;
				}
				if (isset($availableCategoriesByName[$term])) {
					$category = $availableCategoriesByName[$term];
					$item->addCategory($category);
				} else {
					$logCallback($item, 'Skipped category "' . $term . '", not found', LOG_INFO);
				}
			}
		}
	}

	/**
	 * Populate an item from a feed item
	 *
	 * @param $item \Planetflow3\Domain\Model\Item
	 * @param $feedItem \SimplePie_Item
	 * @return void
	 */
	protected function populateItemProperties(Item $item, \SimplePie_Item $feedItem) {
		$item->setLink($feedItem->get_link());
		$item->setTitle($feedItem->get_title());
		$item->setDescription($feedItem->get_description());
		$item->setContent($feedItem->get_content(TRUE));
		$item->setPublicationDate(new \DateTime($feedItem->get_date()));
		$item->setAuthor($feedItem->get_author());
	}

	/**
	 * @return array Categories indexed by name
	 */
	protected function getAvailableCategories() {
		if ($this->availableCategories === NULL) {
			$availableCategories = $this->categoryRepository->findAll();
			$availableCategoriesByName = array();
			foreach ($availableCategories as $availableCategory) {
				$availableCategoriesByName[$availableCategory->getName()] = $availableCategory;
			}
			$this->availableCategories = $availableCategoriesByName;
		}
		return $this->availableCategories;
	}

	/**
	 * Factory method to create a SimplePie instance
	 *
	 * If a file URL is set as feedUrl on the channel, the raw data will be set
	 * on SimplePie to enable functional testing.
	 *
	 * @param \Planetflow3\Domain\Model\Channel $channel
	 * @return \SimplePie
	 */
	protected function createSimplePie(Channel $channel) {
		$simplePie = new \SimplePie();
		if (strpos($channel->getFeedUrl(), 'file://') === 0) {
			$simplePie->set_raw_data(file_get_contents($channel->getFeedUrl()));
		} else {
			$simplePie->set_feed_url($channel->getFeedUrl());
		}
		$simplePie->enable_cache(FALSE);
		$simplePie->init();

		return $simplePie;
	}

}
?>