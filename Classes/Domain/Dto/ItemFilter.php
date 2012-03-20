<?php
namespace Planetflow3\Domain\Dto;

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

/**
 * A item filter DTO
 */
class ItemFilter {

	/**
	 * @var \Planetflow3\Domain\Model\Channel
	 */
	protected $channel;

	/**
	 * @var \Planetflow3\Domain\Model\Category
	 */
	protected $category;

	/**
	 * @return \Planetflow3\Domain\Model\Channel
	 */
	public function getChannel() {
		return $this->channel;
	}

	/**
	 * @param \Planetflow3\Domain\Model\Channel $channel
	 */
	public function setChannel($channel) {
		$this->channel = $channel;
	}

	/**
	 * @return \Planetflow3\Domain\Model\Category
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * @param \Planetflow3\Domain\Model\Category $category
	 */
	public function setCategory($category) {
		$this->category = $category;
	}

}
?>