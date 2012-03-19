<?php
namespace Planetflow3\ViewHelpers\Menu;

/*                                                                        *
 * This script belongs to the FLOW3 package "Planetflow3".                *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Generate a menu item
 */
class ItemViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	protected $tagName = 'li';

	/**
	 * Escape the given value
	 *
	 * @param string $controller
	 * @param string $action
	 * @param string $icon
	 * @param string $activeLevel
	 * @return string
	 */
	public function render($controller, $action = NULL, $packageKey = NULL, $icon = NULL, $activeLevel = 'controller') {
		$link = new \TYPO3\Fluid\Core\ViewHelper\TagBuilder('a');

		$uriBuilder = $this->controllerContext->getUriBuilder();
		$uri = $uriBuilder->reset()->uriFor($action, array(), $controller, $packageKey);

		$active = TRUE;
		if ($this->controllerContext->getRequest()->getControllerName() !== $controller) {
			$active = FALSE;
		}
		if ($activeLevel === 'action' && $this->controllerContext->getRequest()->getControllerActionName() !== $action) {
			$active = FALSE;
		}

		$prependLabelContent = '';
		if ($icon !== NULL) {
			$iconClass = $icon;
			if ($active) {
				$iconClass .= ' icon-white';
			}
			$prependLabelContent = '<i class="' . $iconClass . '"></i> ';
		}

		if ($active) {
			$this->tag->addAttribute('class', 'active');
		}

		$link->addAttribute('href', $uri);
		$link->setContent($prependLabelContent . $this->renderChildren());
		$this->tag->setContent($link->render());
		return $this->tag->render();
	}

}
?>