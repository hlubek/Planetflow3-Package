<?php
declare(ENCODING = 'utf-8');
namespace Planetflow3\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "Planetflow3".                *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Channel controller for the Planetflow3 package 
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class LoginController extends \TYPO3\FLOW3\MVC\Controller\ActionController {

	/**
	 * @inject
	 * @var \TYPO3\FLOW3\Security\Authentication\AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	/**
	 * Default action, displays the login screen
	 *
	 * @param string $username Optional: A username to prefill into the username field
	 * @return void
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function indexAction($username = NULL) {
		$this->view->assign('username', $username);
	}

	/**
	 * Authenticates an account by invoking the Provider based Authentication Manager.
	 *
	 * On successful authentication redirects to the backend, otherwise returns
	 * to the login screen.
	 *
	 * Note: You need to send the username and password these two POST parameters:
	 *       TYPO3[FLOW3][Security][Authentication][Token][UsernamePassword][username]
	 *   and TYPO3[FLOW3][Security][Authentication][Token][UsernamePassword][password]
	 *
	 * @return void
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function authenticateAction() {
		$authenticated = FALSE;
		try {
			$this->authenticationManager->authenticate();
			$authenticated = TRUE;
		} catch (\TYPO3\FLOW3\Security\Exception\AuthenticationRequiredException $exception) {
			throw $exception;
		}

		if ($authenticated) {
			$this->redirect('index', 'Channel');
		} else {
			$this->flashMessageContainer->add('Wrong username or password.');
			$this->redirect('index', 'Login');
		}
	}

	/**
	 * Logs out a - possibly - currently logged in account.
	 *
	 * @return void
	 * @author Robert Lemke <robert@typo3.org>
	 * @extdirect
	 */
	public function logoutAction() {
		$this->authenticationManager->logout();

		$this->flashMessageContainer->add('Successfully logged out.');
		$this->redirect('index');
	}

}
?>