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
 * Channel controller for the Planetflow3 package
 *
 */
class ChannelController extends \TYPO3\FLOW3\MVC\Controller\ActionController {

	/**
	 * @FLOW3\Inject
	 * @var \Planetflow3\Domain\Repository\ChannelRepository
	 */
	protected $channelRepository;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$channels = $this->channelRepository->findAll();
		$this->view->assign('channels', $channels);
	}

	/**
	 * New action
	 *
	 * @param \Planetflow3\Domain\Model\Channel $channel
	 * @return void
	 * @FLOW3\IgnoreValidation("$channel")
	 */
	public function newAction(\Planetflow3\Domain\Model\Channel $channel = NULL) {
		$this->view->assign('channel', $channel);
	}

	/**
	 * Create action
	 *
	 * @param \Planetflow3\Domain\Model\Channel $channel
	 * @return void
	 */
	public function createAction(\Planetflow3\Domain\Model\Channel $channel) {
		$this->channelRepository->add($channel);

		$this->flashMessageContainer->add('Channel created.');
		$this->redirect('index');
	}

}
?>