<?php
namespace Planetflow3\ViewHelpers\Widget;

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
 * Custom paginate widget
 */
class PaginateViewHelper extends \TYPO3\Fluid\ViewHelpers\Widget\PaginateViewHelper {

	/**
	 * @FLOW3\Inject
	 * @var \Planetflow3\ViewHelpers\Widget\Controller\PaginateController
	 */
	protected $controller;

}
?>