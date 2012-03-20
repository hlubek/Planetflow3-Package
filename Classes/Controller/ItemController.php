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
	 * @FLOW3\Inject
	 * @var \Planetflow3\Domain\Repository\ChannelRepository
	 */
	protected $channelRepository;

	/**
	 * @FLOW3\Inject
	 * @var \Planetflow3\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * Index action
	 *
	 * @param \Planetflow3\Domain\Dto\ItemFilter $filter
	 * @FLOW3\SkipCsrfProtection
	 */
	public function indexAction($filter = NULL) {
		if ($filter === NULL) {
			$filter = new \Planetflow3\Domain\Dto\ItemFilter();
		}
		$items = $this->itemRepository->findByFilter($filter);
		$this->view->assign('items', $items);
		$this->view->assign('filter', $filter);

		$channels = $this->channelRepository->findAll();
		$categories = $this->categoryRepository->findAll();
		$this->view->assign('channels', $channels->toArray());
		$this->view->assign('categories', $categories->toArray());
	}

	/**
	 * Edit action
	 *
	 * @param \Planetflow3\Domain\Model\Item $item
	 * @FLOW3\IgnoreValidation("$item")
	 */
	public function editAction(\Planetflow3\Domain\Model\Item $item) {
		$categories = $this->categoryRepository->findAll();

		$this->view->assign('item', $item);
		$this->view->assign('categories', $categories);
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

	/**
	 * Enable action
	 *
	 * @param \Planetflow3\Domain\Model\Item $item
	 */
	public function enableAction(\Planetflow3\Domain\Model\Item $item) {
		$item->setDisabled(FALSE);
		$this->itemRepository->update($item);

		$this->addFlashMessage('Item enabled.', 'Success', \TYPO3\FLOW3\Error\Message::SEVERITY_OK);
		$this->redirect('index');
	}

	/**
	 * Disable action
	 *
	 * @param \Planetflow3\Domain\Model\Item $item
	 */
	public function disableAction(\Planetflow3\Domain\Model\Item $item) {
		$item->setDisabled(TRUE);
		$this->itemRepository->update($item);

		$this->addFlashMessage('Item disabled.', 'Success', \TYPO3\FLOW3\Error\Message::SEVERITY_OK);
		$this->redirect('index');
	}

}
?>