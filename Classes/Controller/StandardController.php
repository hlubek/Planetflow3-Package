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
 * Standard controller for the Planetflow3 package
 *
 */
class StandardController extends \TYPO3\FLOW3\Mvc\Controller\ActionController {

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
	 * Items per page
	 * @var integer
	 */
	protected $perPage = 10;

	/**
	 * Apply settings to use a different template for the planet
	 *
	 * @param \TYPO3\FLOW3\Mvc\View\ViewInterface $view
	 * @return void
	 */
	protected function initializeView(\TYPO3\FLOW3\Mvc\View\ViewInterface $view) {
		if ($view instanceof \TYPO3\Fluid\View\TemplateView) {
			if (isset($this->settings['frontend']['view']['templateRootPath'])) {
				$view->setTemplateRootPath($this->settings['frontend']['view']['templateRootPath']);
			}
			if (isset($this->settings['frontend']['view']['layoutRootPath'])) {
				$view->setLayoutRootPath($this->settings['frontend']['view']['layoutRootPath']);
			}
			if (isset($this->settings['frontend']['view']['partialRootPath'])) {
				$view->setPartialRootPath($this->settings['frontend']['view']['partialRootPath']);
			}
		}
	}

	/**
	 * Index action
	 *
	 * Displays a list of items with endless scrolling. Allows to filter by language.
	 *
	 * @param integer $page The current page
	 * @param string $language Filter by language, NULL for all languages
	 */
	public function indexAction($page = 1, $language = NULL) {
		$offset = ($page - 1) * $this->perPage;

		$filter = new \Planetflow3\Domain\Dto\ItemFilter();
		$filter->setLanguage($language);
		$filter->setDisabled(FALSE);
		$result = $this->itemRepository->findByFilter($filter);

		$count = $result->count();
		$query = $result->getQuery();
		$query->setLimit(10);
		$query->setOffset($offset);
		$items = $query->execute();

		$this->view->assign('items', $items);

		$this->view->assign('language', $language);

		$this->view->assign('page', $page);
		$this->view->assign('count', $count);
		$this->view->assign('offset', $offset);
		$this->view->assign('perPage', $this->perPage);
		$this->view->assign('hasNext', $offset + $this->perPage <= $count);
		$this->view->assign('nextPage', $page + 1);

		$channels = $this->channelRepository->findAll();
		$this->view->assign('channels', $channels);
		$this->view->assign('languages', array('en', 'de'));

		// TODO Send correct cache control including last modified
	}

	/**
	 * Feed action
	 *
	 * Render an aggregated feed for a given language.
	 *
	 * @param string $language Filter by language, NULL for all languages
	 */
	public function feedAction($language = NULL) {
		$filter = new \Planetflow3\Domain\Dto\ItemFilter();
		$filter->setLanguage($language);
		$filter->setDisabled(FALSE);
		$result = $this->itemRepository->findByFilter($filter);

		$query = $result->getQuery();
		$query->setLimit(20);
		$items = $query->execute();

		$this->view->assign('language', $language);
		$this->view->assign('items', $items);

		$this->response->setHeader('Content-Type', 'application/rss+xml; charset=UTF-8');

		// TODO Send correct cache control including last modified
	}

}
?>