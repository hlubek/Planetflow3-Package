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
 * A user
 *
 * @FLOW3\Entity
 */
class User extends \TYPO3\Party\Domain\Model\AbstractParty {

	/**
	 * The email address of the user
	 * @var string
	 * @FLOW3\Validate(type="NotEmpty")
	 * @FLOW3\Validate(type="EmailAddress")
	 */
	protected $emailAddress;

	/**
	 * @var string
	 * @FLOW3\Validate(type="NotEmpty")
	 * @FLOW3\Validate(type="RegularExpression", options={"regularExpression"="/^(Administrator|SystemAdministrator)$/"})
	 */
	protected $role;

	/**
	 * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Security\Cryptography\HashService
	 */
	protected $hashService;

	/**
	 * Construct a user
	 */
	public function __construct() {
		parent::__construct();
		$account = new \TYPO3\FLOW3\Security\Account();
		$account->setAuthenticationProviderName('AdminInterfaceProvider');
		$this->addAccount($account);
	}

	/**
	 * @return string
	 */
	public function getEmailAddress() {
		return $this->emailAddress;
	}

	/**
	 * @param string $emailAddress
	 */
	public function setEmailAddress($emailAddress) {
		$this->emailAddress = $emailAddress;
		$account = $this->getPrimaryAccount();
		$account->setAccountIdentifier($emailAddress);
	}

	/**
	 * @return string
	 */
	public function getRole() {
		return $this->role;
	}

	/**
	 * @param string $role
	 */
	public function setRole($role) {
		$this->role = $role;
		$account = $this->getPrimaryAccount();
		$account->setRoles(array($role));
	}

	/**
	 * @param string $password
	 */
	public function setPassword($password) {
		$account = $this->getPrimaryAccount();
		$account->setCredentialsSource($this->hashService->hashPassword($password));
	}

	/**
	 * @return \TYPO3\FLOW3\Security\Account
	 */
	public function getPrimaryAccount() {
		if (count($this->accounts) > 0) {
			return $this->accounts->first();
		}
		return NULL;
	}

}
?>