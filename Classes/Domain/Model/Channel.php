<?php
declare(ENCODING = 'utf-8');
namespace F3\Planetflow3\Domain\Model;

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
 * A Channel
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @scope prototype
 * @entity
 */
class Channel {

	/**
	 * The name
	 * @var string
	 */
	protected $name;

	/**
	 * The url for linking the channel
	 * @var string
	 */
	protected $url;

	/**
	 * The feed url
	 * @var string
	 */
	protected $feedUrl;

	/**
	 * Fetch only these categories (by name)
	 * @var array
	 */
	protected $fetchedCategories = array();

	/**
	 * Assign a default category when the item has no category (e.g. missing in feed)
	 * @var \F3\Planetflow3\Domain\Model\Category
	 * @ManyToOne
	 */
	protected $defaultCategory;

	/**
	 * The filter
	 * @var string
	 */
	protected $filter = '';

	/**
	 * Items published by this channel
	 * @var \Doctrine\Common\Collections\ArrayCollection<\F3\Planetflow3\Domain\Model\Item>
	 * @OneToMany(mappedBy="channel", cascade={"all"})
	 */
	protected $items;

	/**
	 * @inject
	 * @var \F3\Planetflow3\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->items = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Fetches (new) items from the configured feed
	 *
	 * @return void
	 */
	public function fetchItems() {
		$simplePie = new \SimplePie();
		$simplePie->set_feed_url($this->feedUrl);
		$simplePie->enable_cache(FALSE);
		$simplePie->init();

		$availableCategories = $this->categoryRepository->findAll();
		$availableCategoriesByName = array();
		foreach ($availableCategories as $availableCategory) {
			$availableCategoriesByName[$availableCategory->getName()] = $availableCategory;
		}

		$existingUniversalIdentifiers = array();
		foreach ($this->items as $item) {
			$existingUniversalIdentifiers[$item->getUniversalIdentifier()] = TRUE;
		}

		$feedItems = $simplePie->get_items();
		foreach ($feedItems as $feedItem) {
			$item = new \F3\Planetflow3\Domain\Model\Item();
			$item->setUniversalIdentifier($feedItem->get_id());
			if (isset($existingUniversalIdentifiers[$item->getUniversalIdentifier()])) {
				echo "Skipped " . $item->getUniversalIdentifier() . ", already fetched." . PHP_EOL;
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
						echo "Skipped category " . $term . PHP_EOL;
					}
					
				}
			}

			if ($item->getCategories()->count() === 0 && $this->defaultCategory !== NULL) {
				$item->addCategory($this->defaultCategory);
			}

			if ($item->matchesChannel($this)) {
				$this->addItem($item);
				$existingUniversalIdentifiers[$item->getUniversalIdentifier()] = TRUE;
			} else {
				echo "Skipped " . $item->getUniversalIdentifier() . ', not matched.' . PHP_EOL;
			}
		}
	}

	/**
	 * Get the Channel's name
	 *
	 * @return string The Channel's name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets this Channel's name
	 *
	 * @param string $name The Channel's name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param string $url
	 * @return void
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * Get the Channel's feed url
	 *
	 * @return string The Channel's feed url
	 */
	public function getFeedUrl() {
		return $this->feedUrl;
	}

	/**
	 * Sets this Channel's feed url
	 *
	 * @param string $feedUrl The Channel's feed url
	 * @return void
	 */
	public function setFeedUrl($feedUrl) {
		$this->feedUrl = $feedUrl;
	}

	/**
	 * Get the Channel's categories
	 *
	 * @return array The Channel's categories
	 */
	public function getFetchedCategories() {
		return $this->fetchedCategories;
	}

	/**
	 * Sets this Channel's categories
	 *
	 * @param array $categories The Channel's categories
	 * @return void
	 */
	public function setFetchedCategories(array $categories) {
		$this->fetchedCategories = $categories;
	}

	/**
	 * Get the Channel's filter
	 *
	 * @return string The Channel's filter
	 */
	public function getFilter() {
		return $this->filter;
	}

	/**
	 * Sets this Channel's filter
	 *
	 * @param string $filter The Channel's filter
	 * @return void
	 */
	public function setFilter($filter) {
		$this->filter = $filter;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection<\F3\Planetflow3\Domain\Model\Item>
	 */
	public function getItems() {
		return $this->items;
	}

	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection<\F3\Planetflow3\Domain\Model\Item> $items
	 * @return void
	 */
	public function setItems(\Doctrine\Common\Collections\ArrayCollection $items) {
		$this->items = $items;
	}

	/**
	 *
	 * @param \F3\Planetflow3\Domain\Model\Item $item
	 * @return void
	 */
	public function addItem(\F3\Planetflow3\Domain\Model\Item $item) {
		$item->setChannel($this);
		$this->items->add($item);
	}

	/**
	 *
	 * @return \F3\Planetflow3\Domain\Model\Category
	 */
	public function getDefaultCategory() {
		return $this->defaultCategory;
	}

	/**
	 *
	 * @param \F3\Planetflow3\Domain\Model\Category $defaultCategory
	 * @return void
	 */
	public function setDefaultCategory(\F3\Planetflow3\Domain\Model\Category $defaultCategory) {
		$this->defaultCategory = $defaultCategory;
	}

}
?>