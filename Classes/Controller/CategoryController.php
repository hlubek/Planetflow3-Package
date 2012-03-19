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
 * Category controller
 *
 */
class CategoryController extends AbstractBackendController {

	/**
	 * @FLOW3\Inject
	 * @var \Planetflow3\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * Index action
	 *
	 * @FLOW3\SkipCsrfProtection
	 */
	public function indexAction() {
		$categories = $this->categoryRepository->findAll();
		$this->view->assign('categories', $categories);
	}

	/**
	 * New action
	 *
	 * @param \Planetflow3\Domain\Model\Category $category
	 * @FLOW3\IgnoreValidation("$category")
	 */
	public function newAction(\Planetflow3\Domain\Model\Category $category = NULL) {
		$this->view->assign('category', $category);
	}

	/**
	 * Create action
	 *
	 * @param \Planetflow3\Domain\Model\Category $category
	 */
	public function createAction(\Planetflow3\Domain\Model\Category $category) {
		$this->categoryRepository->add($category);

		$this->addFlashMessage('Category created.', 'Success', \TYPO3\FLOW3\Error\Message::SEVERITY_OK);
		$this->redirect('index');
	}

	/**
	 * Edit action
	 *
	 * @param \Planetflow3\Domain\Model\Category $category
	 * @FLOW3\IgnoreValidation("$category")
	 */
	public function editAction(\Planetflow3\Domain\Model\Category $category) {
		$this->view->assign('category', $category);
	}

	/**
	 * Update action
	 *
	 * @param \Planetflow3\Domain\Model\Category $category
	 */
	public function updateAction(\Planetflow3\Domain\Model\Category $category) {
		$this->categoryRepository->update($category);

		$this->addFlashMessage('Category updated.', 'Success', \TYPO3\FLOW3\Error\Message::SEVERITY_OK);
		$this->redirect('index');
	}

	/**
	 * Delete action
	 *
	 * @param \Planetflow3\Domain\Model\Category $category
	 * @FLOW3\IgnoreValidation("$category")
	 */
	public function deleteAction(\Planetflow3\Domain\Model\Category $category) {
		$this->categoryRepository->remove($category);

		$this->addFlashMessage('Category removed.', 'Success', \TYPO3\FLOW3\Error\Message::SEVERITY_NOTICE);
		$this->redirect('index');
	}

}
?>