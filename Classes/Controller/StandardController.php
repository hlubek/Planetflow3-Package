<?php
declare(ENCODING = 'utf-8');
namespace F3\Planetflow3\Controller;

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
 * Standard controller for the Planetflow3 package 
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class StandardController extends \F3\FLOW3\MVC\Controller\ActionController {

	/**
	 * @inject
	 * @var \F3\Planetflow3\Domain\Repository\ItemRepository
	 */
	protected $itemRepository;

	/**
	 * Items per page
	 * @var integer
	 */
	protected $perPage = 10;

	/**
	 * Index action
	 *
	 * @param integer $page
	 * @return void
	 */
	public function indexAction($page = 1) {
		$offset = ($page - 1) * $this->perPage;
		$count = $this->itemRepository->countAll();
		$items = $this->itemRepository->findRecent($offset, $this->perPage);

		$this->view->assign('items', $items);
		$this->view->assign('page', $page);
		$this->view->assign('count', $count);
		$this->view->assign('offset', $offset);
		$this->view->assign('perPage', $this->perPage);
		$this->view->assign('hasNext', $offset + $this->perPage <= $count);
		$this->view->assign('nextPage', $page + 1);
	}
	
}
?>