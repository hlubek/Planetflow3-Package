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
 * Login controller
 *
 */
class LoginController extends \TYPO3\FLOW3\MVC\Controller\ActionController {

	/**
	 * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Security\Authentication\AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	/**
	 * Default action, displays the login screen
	 *
	 * @param string $username Username to prefill into the username field (optional)
	 * @return void
	 */
	public function indexAction($username = NULL) {
		$this->view->assign('username', $username);
	}

	/**
	 * Authenticates an account by invoking the Provider based Authentication Manager.
	 */
	public function authenticateAction() {
		try {
            $this->authenticationManager->authenticate();
            $this->addFlashMessage('You have successfully logged in.', 'Welcome', \TYPO3\FLOW3\Error\Message::SEVERITY_OK);
            $this->redirect('index', 'Overview');
        } catch (\TYPO3\FLOW3\Security\Exception\AuthenticationRequiredException $exception) {
			$this->addFlashMessage('Wrong username or password.', 'Login failed', \TYPO3\FLOW3\Error\Message::SEVERITY_ERROR);
            throw $exception;
        }
	}

	/**
	 * Logs out a - possibly - currently logged in account.
	 */
	public function logoutAction() {
		$this->authenticationManager->logout();

		$this->addFlashMessage('You are logged out now.', 'See you later', \TYPO3\FLOW3\Error\Message::SEVERITY_OK);
		$this->redirect('index');
	}

}
?>