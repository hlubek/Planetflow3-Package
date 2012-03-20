<?php
namespace Planetflow3\Domain\Repository;

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
 * A repository for Users
 *
 */
class UserRepository extends \TYPO3\FLOW3\Persistence\Doctrine\Repository {

	/**
	 * @var array
	 */
	protected $defaultOrderings = array('emailAddress' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_ASCENDING);

	/**
	 * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @param \Planetflow3\Domain\Model\User $object
	 */
	public function add($object) {
		$this->accountRepository->add($object->getPrimaryAccount());
		parent::add($object);
	}

	/**
	 *
	 * @param \Planetflow3\Domain\Model\User $object
	 */
	public function update($object) {
		$this->accountRepository->update($object->getPrimaryAccount());
		parent::update($object);
	}

	/**
	 * @param \Planetflow3\Domain\Model\User $object
	 */
	public function remove($object) {
		$this->accountRepository->remove($object->getPrimaryAccount());
		parent::remove($object);
	}

}
?>