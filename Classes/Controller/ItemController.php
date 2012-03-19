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
 * Item controller
 *
 */
class ItemController extends AbstractBackendController {

	/**
	 * @FLOW3\Inject
	 * @var \Planetflow3\Domain\Repository\ItemRepository
	 */
	protected $itemRepository;

	/**
	 * Index action
	 *
	 * @FLOW3\SkipCsrfProtection
	 */
	public function indexAction() {
		$items = $this->itemRepository->findAll();
		$this->view->assign('items', $items);
	}

	/**
	 * Edit action
	 *
	 * @param \Planetflow3\Domain\Model\Item $item
	 * @FLOW3\IgnoreValidation("$item")
	 */
	public function editAction(\Planetflow3\Domain\Model\Item $item) {
		$this->view->assign('item', $item);
	}

	/**
	 * Update action
	 *
	 * @param \Planetflow3\Domain\Model\Item $item
	 */
	public function updateAction(\Planetflow3\Domain\Model\Item $item) {
		$this->itemRepository->update($item);

		$this->addFlashMessage('Item updated.', 'Success', \TYPO3\FLOW3\Error\Message::SEVERITY_OK);
		$this->redirect('index');
	}

}
?>