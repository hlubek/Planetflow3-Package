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
 * Abstract backend controller
 */
class AbstractBackendController extends \TYPO3\FLOW3\MVC\Controller\ActionController {

	/**
	 * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Security\Context
	 */
	protected $securityContext;

	/**
	 *
	 * @param \TYPO3\FLOW3\MVC\View\ViewInterface $view
	 * @return void
	 */
	protected function initializeView(\TYPO3\FLOW3\MVC\View\ViewInterface $view) {
		parent::initializeView($view);
		$account = $this->securityContext->getAccount();
		$view->assign('account', $account);
	}

}
?>