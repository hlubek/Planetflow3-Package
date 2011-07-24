<?php
declare(ENCODING = 'utf-8');
namespace Planetflow3\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "Planetflow3".                *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License as published by the Free   *
 * Software Foundation, either version 3 of the License, or (at your      *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        *
 * You should have received a copy of the GNU General Public License      *
 * along with the script.                                                 *
 * If not, see http://www.gnu.org/licenses/gpl.html                       *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * A view helper to propertly escape HTML like content for feed elements that don't
 * accept HTML (e.g. title).
 *
 * See http://www.rssboard.org/rss-profile#data-types-characterdata or
 *     http://validator.w3.org/feed/docs/warning/ContainsHTML.html for more information.
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EscapeHtmlViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Escape the given value
	 *
	 * @param string $value
	 * @return string
	 * @author Christopher Hlubek <hlubek@networkteam.com>
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