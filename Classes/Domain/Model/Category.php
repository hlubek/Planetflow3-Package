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
 * A Category
 *
 * @FLOW3\Entity
 */
class Category {

	/**
	 * The name of this category
	 *
	 * @var string
	 * @FLOW3\Identity
	 * @ORM\Id
	 */
	protected $name;

	/**
	 * Constructor
	 *
	 * @param string $name
	 */
	public function __construct($name = '') {
		$this->name = $name;
	}

	/**
	 * Get the Category's name
	 *
	 * @return string The Category's name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets this Category's name
	 *
	 * @param string $name The Category's name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

}
?>