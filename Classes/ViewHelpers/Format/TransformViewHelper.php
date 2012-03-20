<?php
namespace Planetflow3\ViewHelpers\Format;

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
 * Text transform view helper
 */
class TransformViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Transform the given value
	 *
	 * @param string $value
	 * @param string $case "upper" or "lower" to convert to these cases, optional
	 * @return string
	 */
	public function render($value = NULL, $case = NULL) {
		if ($value === NULL) {
			$value = $this->renderChildren();
		}
		if ($case === 'upper') {
			$value = strtoupper($value);
		} elseif ($case === 'lower') {
			$value = strtolower($value);
		}
		return $value;
	}

}
?>