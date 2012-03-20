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
 * A user password DTO
 */
class UserPassword {

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * @var string
	 */
	protected $passwordConfirmation;

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getPasswordConfirmation() {
		return $this->passwordConfirmation;
	}

	/**
	 * @param string $passwordConfirmation
	 */
	public function setPasswordConfirmation($passwordConfirmation) {
		$this->passwordConfirmation = $passwordConfirmation;
	}


}
?>