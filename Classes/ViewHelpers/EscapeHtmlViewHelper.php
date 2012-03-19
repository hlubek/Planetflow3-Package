<?php
namespace Planetflow3\ViewHelpers;

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
 * A view helper to propertly escape HTML like content for feed elements that don't
 * accept HTML (e.g. title).
 *
 * See http://www.rssboard.org/rss-profile#data-types-characterdata or
 *     http://validator.w3.org/feed/docs/warning/ContainsHTML.html for more information.
 */
class EscapeHtmlViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Escape the given value
	 *
	 * @param string $value
	 * @return string
	 */
	public function render($value = NULL) {
		if ($value === NULL) {
			$value = $this->renderChildren();
		}
		$value = str_replace(
			array('&amp;', '<', '>'),
			array('&#x26;', '&#x3C;', '&#x3E;'),
			$value
		);
		return htmlspecialchars($value, NULL, NULL, FALSE);
	}

}
?>