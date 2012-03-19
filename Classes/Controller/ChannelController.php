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
 * Channel controller
 *
 */
class ChannelController extends AbstractBackendController {

	/**
	 * @FLOW3\Inject
	 * @var \Planetflow3\Domain\Repository\ChannelRepository
	 */
	protected $channelRepository;

	/**
	 * Index action
	 *
	 * @FLOW3\SkipCsrfProtection
	 */
	public function indexAction() {
		$channels = $this->channelRepository->findAll();
		$this->view->assign('channels', $channels);
	}

	/**
	 * New action
	 *
	 * @param \Planetflow3\Domain\Model\Channel $channel
	 * @FLOW3\IgnoreValidation("$channel")
	 */
	public function newAction(\Planetflow3\Domain\Model\Channel $channel = NULL) {
		$this->view->assign('channel', $channel);
	}

	/**
	 * Create action
	 *
	 * @param \Planetflow3\Domain\Model\Channel $channel
	 */
	public function createAction(\Planetflow3\Domain\Model\Channel $channel) {
		$this->channelRepository->add($channel);

		$this->addFlashMessage('Channel created.', 'Success', \TYPO3\FLOW3\Error\Message::SEVERITY_OK);
		$this->redirect('index');
	}

	/**
	 * Edit action
	 *
	 * @param \Planetflow3\Domain\Model\Channel $channel
	 * @FLOW3\IgnoreValidation("$channel")
	 */
	public function editAction(\Planetflow3\Domain\Model\Channel $channel) {
		$this->view->assign('channel', $channel);
	}

	/**
	 * Update action
	 *
	 * @param \Planetflow3\Domain\Model\Channel $channel
	 */
	public function updateAction(\Planetflow3\Domain\Model\Channel $channel) {
		$this->channelRepository->update($channel);

		$this->addFlashMessage('Channel updated.', 'Success', \TYPO3\FLOW3\Error\Message::SEVERITY_OK);
		$this->redirect('index');
	}

	/**
	 * Delete action
	 *
	 * @param \Planetflow3\Domain\Model\Channel $channel
	 * @FLOW3\IgnoreValidation("$channel")
	 */
	public function deleteAction(\Planetflow3\Domain\Model\Channel $channel) {
		$this->channelRepository->remove($channel);

		$this->addFlashMessage('Channel removed.', 'Success', \TYPO3\FLOW3\Error\Message::SEVERITY_NOTICE);
		$this->redirect('index');
	}

}
?>