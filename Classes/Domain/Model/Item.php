<?php
namespace Planetflow3\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "Planetflow3".                *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * An Item
 *
 * @FLOW3\Entity
 */
class Item {

	/**
	 * The universal identifier (atomId or guid), used as identifier and id
	 * @var string
	 * @FLOW3\Identity
	 * @ORM\Id
	 */
	protected $universalIdentifier;

	/**
	 * The title
	 * @var string
	 */
	protected $title;

	/**
	 * The link
	 * @var string
	 */
	protected $link;

	/**
	 * The description
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $description = '';

	/**
	 * The item content
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $content = '';

	/**
	 * The author
	 * @var string
	 */
	protected $author;

	/**
	 * The categories
	 * @var \Doctrine\Common\Collections\ArrayCollection<\Planetflow3\Domain\Model\Category>
	 * @ORM\ManyToMany
	 */
	protected $categories;

	/**
	 * The publication date
	 * @var \DateTime
	 */
	protected $publicationDate;

	/**
	 * Language of the item (ISO 2-letter)
	 * @var string
	 */
	protected $language;

	/**
	 * The channel
	 * @var \Planetflow3\Domain\Model\Channel
	 * @ORM\ManyToOne(inversedBy="items")
	 */
	protected $channel;

	/**
	 * If the item is disabled it won't be shown
	 * @var boolean
	 */
	protected $disabled = FALSE;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->categories = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Check if this item matches the channel settings
	 *
	 * @param \Planetflow3\Domain\Model\Channel $channel
	 * @return boolean
	 */
	public function matchesChannel(\Planetflow3\Domain\Model\Channel $channel) {
		return $this->matchesFilter($channel->getFilter())
			&& $this->matchesCategories($channel->getFetchedCategories());
	}

	/**
	 * Check if this item has one of the given categories
	 *
	 * @param array $categoryNames
	 * @return boolean
	 */
	public function matchesCategories(array $categoryNames) {
		if (count($categoryNames) === 0) {
			return TRUE;
		}
		foreach ($this->categories as $category) {
			if (in_array($category->getName(), $categoryNames)) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Check filter and fetched categories
	 *
	 * @param string $filter
	 * @return boolean TRUE if this item matches the given filter
	 */
	public function matchesFilter($filter) {
		if ($filter === NULL || $filter === '') {
			return TRUE;
		}

		$keywords = explode(',', $filter);

		foreach (array($this->getTitle(), $this->getDescription(), $this->getContent()) as $field) {
			foreach ($keywords as $keyword) {
				if (strpos($field, $keyword) !== FALSE) {
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	/**
	 *
	 * @param string $content
	 * @return string
	 */
	public function cleanHtml($content) {
		$cleanupPatterns = array(
			// Filter out flattr buttons
			array('/<p><a.*?title="Flattr".*?<\/a><\/p>/is', ''),
			// Filter out shareaholic toolbar
			array('/<div>\s*<ul>.*?<\/ul><div><\/div><div><a[^>]*>Get Shareaholic<\/a><\/div><div><\/div><\/div>/is', ''),
			// Filter out google tracking pixel
			array('/<div><img width="1" height="1"[^>]*>\s*<\/div>/is', ''),
			// Remove empty paragraphs
			array('/<p>\s*<\/p>/is', ''),
			array('/<p>&nbsp;<\/p>/is', ''),
			// Remove empty divs
			array('/<div>\s*<\/div>/is', ''),
			// Introduce wrapping paragraph around text
			array('/^\s*([^<].*[^>])\s*$/is', '<p>$1</p>')
		);
		foreach ($cleanupPatterns as $cleanupPattern) {
			list($pattern, $replacement) = $cleanupPattern;
			$content = preg_replace($pattern, $replacement, $content);
		}
		return $content;
	}

	/**
	 * Get the Item's title
	 *
	 * @return string The Item's title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets this Item's title
	 *
	 * @param string $title The Item's title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Get the Item's link
	 *
	 * @return string The Item's link
	 */
	public function getLink() {
		return $this->link;
	}

	/**
	 * Sets this Item's link
	 *
	 * @param string $link The Item's link
	 * @return void
	 */
	public function setLink($link) {
		$this->link = $link;
	}

	/**
	 * Get the Item's description
	 *
	 * @return string The Item's description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Sets this Item's description
	 *
	 * @param string $description The Item's description
	 * @return void
	 */
	public function setDescription($description) {
		if ($description === NULL) {
			$description = '';
		}
		$this->description = $this->cleanHtml($description);
	}

	/**
	 * Get the Item's content
	 *
	 * @return string The Item's content
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Sets this Item's content
	 *
	 * @param string $content The Item's content
	 * @return void
	 */
	public function setContent($content) {
		if ($content === NULL) {
			$content = '';
		}
		$this->content = $this->cleanHtml($content);
	}

	/**
	 * @return string
	 */
	public function getTeaser() {
		if ($this->content !== '') {
			return substr(strip_tags($this->content), 0, 200);
		} else {
			return substr(strip_tags($this->description), 0, 200);
		}
	}

	/**
	 * Get the Item's author
	 *
	 * @return string The Item's author
	 */
	public function getAuthor() {
		return $this->author;
	}

	/**
	 * Sets this Item's author
	 *
	 * @param string $author The Item's author
	 * @return void
	 */
	public function setAuthor($author) {
		$this->author = $author;
	}

	/**
	 * Get the Item's categories
	 *
	 * @return \Doctrine\Common\Collections\ArrayCollection<\Planetflow3\Domain\Model\Category> The Item's categories
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * Sets this Item's categories
	 *
	 * @param \Doctrine\Common\Collections\ArrayCollection<\Planetflow3\Domain\Model\Category> $categories The Item's categories
	 * @return void
	 */
	public function setCategories(\Doctrine\Common\Collections\ArrayCollection $categories) {
		$this->categories = $categories;
	}

	/**
	 * Add a category to this item
	 *
	 * @param \Planetflow3\Domain\Model\Category $category
	 * @return void
	 */
	public function addCategory(\Planetflow3\Domain\Model\Category $category) {
		$this->categories->add($category);
	}


	/**
	 * Get the Item's universal identifier
	 *
	 * @return string The Item's universal identifier
	 */
	public function getUniversalIdentifier() {
		return $this->universalIdentifier;
	}

	/**
	 * Sets this Item's universal identifier
	 *
	 * @param string $universalIdentifier The Item's universal identifier
	 * @return void
	 */
	public function setUniversalIdentifier($universalIdentifier) {
		$this->universalIdentifier = $universalIdentifier;
	}

	/**
	 * Get the Item's publication date
	 *
	 * @return \DateTime The Item's publication date
	 */
	public function getPublicationDate() {
		return $this->publicationDate;
	}

	/**
	 * Sets this Item's publication date
	 *
	 * @param \DateTime $publicationDate The Item's publication date
	 * @return void
	 */
	public function setPublicationDate(\DateTime $publicationDate) {
		$this->publicationDate = $publicationDate;
	}

	/**
	 * @return string
	 */
	public function getLanguage() {
		return $this->language;
	}

	/**
	 * @param string $language
	 * @return void
	 */
	public function setLanguage($language) {
		$this->language = $language;
	}

	/**
	 * Get the Item's channel
	 *
	 * @return \Planetflow3\Domain\Model\Channel The Item's channel
	 */
	public function getChannel() {
		return $this->channel;
	}

	/**
	 * Sets this Item's channel
	 *
	 * @param \Planetflow3\Domain\Model\Channel $channel The Item's channel
	 * @return void
	 */
	public function setChannel(\Planetflow3\Domain\Model\Channel $channel) {
		$this->channel = $channel;
	}

	/**
	 * @return boolean
	 */
	public function getDisabled() {
		return $this->disabled;
	}

	/**
	 * @param boolean $disabled
	 */
	public function setDisabled($disabled) {
		$this->disabled = $disabled;
	}

}
?>