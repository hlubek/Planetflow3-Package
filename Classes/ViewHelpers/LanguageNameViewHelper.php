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
 * A view helper to output the name of a language
 *
 */
class LanguageNameViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 *
	 * @param string $language
	 * @param boolean $lowercase
	 * @return string
	 */
	public function render($language = NULL, $lowercase = FALSE) {
		if ($language === NULL) {
			$language = $this->renderChildren();
		}
		$label = $language;
		switch($language) {
			case 'en':
				$label = 'English';
				break;
			case 'de':
				$label = 'German';
				break;
			case 'fr':
				$label = 'French';
				break;
			case 'es':
				$lable = 'Spanish';
				break;
		}
		if ($lowercase) {
			$label = strtolower($label);
		}
		return $label;
	}

}
?>