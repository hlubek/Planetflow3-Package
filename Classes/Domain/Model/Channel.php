<?php
declare(ENCODING = 'utf-8');
namespace Planetflow3\Domain\Model;

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
	 * @validate NotEmpty, Label
	 */
	protected $name;

	/**
	 * The url for linking the channel
	 * @var string
	 * @validate NotEmpty
	 */
	protected $url;

	/**
	 * The feed url
	 * @var string
	 * @validate NotEmpty
	 */
	protected $feedUrl;

	/**
	 * Fetch only these categories (by name)
	 * @var array
	 */
	protected $fetchedCategories = array();

	/**
	 * Assign a default category when the item has no category (e.g. missing in feed)
	 * @var \Planetflow3\Domain\Model\Category
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
	 * @var \Doctrine\Common\Collections\ArrayCollection<\Planetflow3\Domain\Model\Item>
	 * @OneToMany(mappedBy="channel", cascade={"all"})
	 */
	protected $items;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->items = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @return \Doctrine\Common\Collections\ArrayCollection<\Planetflow3\Domain\Model\Item>
	 */
	public function getItems() {
		return $this->items;
	}

	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection<\Planetflow3\Domain\Model\Item> $items
	 * @return void
	 */
	public function setItems(\Doctrine\Common\Collections\ArrayCollection $items) {
		$this->items = $items;
	}

	/**
	 *
	 * @param \Planetflow3\Domain\Model\Item $item
	 * @return void
	 */
	public function addItem(\Planetflow3\Domain\Model\Item $item) {
		$item->setChannel($this);
		$this->items->add($item);
	}

	/**
	 *
	 * @return \Planetflow3\Domain\Model\Category
	 */
	public function getDefaultCategory() {
		return $this->defaultCategory;
	}

	/**
	 *
	 * @param \Planetflow3\Domain\Model\Category $defaultCategory
	 * @return void
	 */
	public function setDefaultCategory(\Planetflow3\Domain\Model\Category $defaultCategory) {
		$this->defaultCategory = $defaultCategory;
	}

}
?>