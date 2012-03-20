<?php
namespace Planetflow3\Controller;

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
 * User controller
 *
 */
class UserController extends AbstractBackendController {

	/**
	 * @FLOW3\Inject
	 * @var \Planetflow3\Domain\Repository\UserRepository
	 */
	protected $userRepository;

	/**
	 * Index action
	 *
	 * @FLOW3\SkipCsrfProtection
	 */
	public function indexAction() {
		$users = $this->userRepository->findAll();
		$this->view->assign('users', $users);
	}

	/**
	 * New action
	 *
	 * @param \Planetflow3\Domain\Model\User $user
	 * @FLOW3\IgnoreValidation("$user")
	 */
	public function newAction(\Planetflow3\Domain\Model\User $user = NULL) {
		$this->view->assign('user', $user);
	}

	/**
	 * Create action
	 *
	 * @param \Planetflow3\Domain\Model\User $user
	 * @param \Planetflow3\Domain\Dto\UserPassword $userPassword
	 * @FLOW3\Validate("$userPassword", type="Planetflow3\Domain\Validator\CreateUserPasswordValidator")
	 */
	public function createAction(\Planetflow3\Domain\Model\User $user, \Planetflow3\Domain\Dto\UserPassword $userPassword) {
		$user->setPassword($userPassword->getPassword());
		$this->userRepository->add($user);

		$this->addFlashMessage('User created.', 'Success', \TYPO3\FLOW3\Error\Message::SEVERITY_OK);
		$this->redirect('index');
	}

	/**
	 * Edit action
	 *
	 * @param \Planetflow3\Domain\Model\User $user
	 * @FLOW3\IgnoreValidation("$user")
	 */
	public function editAction(\Planetflow3\Domain\Model\User $user) {
		$this->view->assign('user', $user);
	}

	/**
	 * Update action
	 *
	 * @param \Planetflow3\Domain\Model\User $user
	 * @param \Planetflow3\Domain\Dto\UserPassword $userPassword
	 * @FLOW3\Validate("$userPassword", type="Planetflow3\Domain\Validator\UserPasswordValidator")
	 */
	public function updateAction(\Planetflow3\Domain\Model\User $user, \Planetflow3\Domain\Dto\UserPassword $userPassword) {
		if ((string)$userPassword->getPassword() !== '') {
			$user->setPassword($userPassword->getPassword());
		}
		$this->userRepository->update($user);

		$this->addFlashMessage('User updated.', 'Success', \TYPO3\FLOW3\Error\Message::SEVERITY_OK);
		$this->redirect('index');
	}

	/**
	 * Delete action
	 *
	 * @param \Planetflow3\Domain\Model\User $user
	 * @FLOW3\IgnoreValidation("$user")
	 */
	public function deleteAction(\Planetflow3\Domain\Model\User $user) {
		$this->userRepository->remove($user);

		$this->addFlashMessage('User removed.', 'Success', \TYPO3\FLOW3\Error\Message::SEVERITY_NOTICE);
		$this->redirect('index');
	}

}
?>