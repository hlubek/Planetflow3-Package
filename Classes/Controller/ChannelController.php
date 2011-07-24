<?php
declare(ENCODING = 'utf-8');
namespace Planetflow3\Controller;

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
 * Channel controller for the Planetflow3 package 
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class ChannelController extends \TYPO3\FLOW3\MVC\Controller\ActionController {

	/**
	 * @inject
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
	 * @dontvalidate $channel
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