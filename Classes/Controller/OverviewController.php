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
 * Overview controller
 */
class OverviewController extends AbstractBackendController {

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
	 * @FLOW3\SkipCsrfProtection
	 */
	public function indexAction() {
		$this->view->assign('itemCount', $this->itemRepository->countAll());
		$this->view->assign('recentItems', $this->itemRepository->findRecent(0, 5));
		$this->view->assign('channelCount', $this->channelRepository->countAll());
		$this->view->assign('topChannels', $this->channelRepository->findTopChannels());
	}

}
?>