<?php
declare(ENCODING = 'utf-8');
namespace F3\Planetflow3\Command;

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
 * Command controller to set up the Planetflow3 package
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class SetupCommandController extends \F3\FLOW3\MVC\Controller\CommandController {

	/**
	 * @inject
	 * @var \F3\Planetflow3\Domain\Repository\ChannelRepository
	 */
	protected $channelRepository;

	/**
	 * @inject
	 * @var \F3\Planetflow3\Domain\Repository\ItemRepository
	 */
	protected $itemRepository;

	/**
	 * @inject
	 * @var \F3\Planetflow3\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * Create sample data
	 *
	 * @return void
	 */
	public function sampleDataCommand() {
		$this->itemRepository->removeAll();
		$this->channelRepository->removeAll();
		$this->categoryRepository->removeAll();

		$flow3Category = new \F3\Planetflow3\Domain\Model\Category('FLOW3');
		$this->categoryRepository->add($flow3Category);
		$phpCategory = new \F3\Planetflow3\Domain\Model\Category('PHP');
		$this->categoryRepository->add($phpCategory);
		$phoenixCategory = new \F3\Planetflow3\Domain\Model\Category('Phoenix');
		$this->categoryRepository->add($phoenixCategory);

		$channels = array();

		$channel = new \F3\Planetflow3\Domain\Model\Channel();
		$channel->setName('news.typo3.org: FLOW3');
		$channel->setUrl('http://news.typo3.org/news/teams/flow3/');
		$channel->setFeedUrl('http://news.typo3.org/news/teams/flow3/rss.xml');
		$channel->setDefaultCategory($flow3Category);
		$this->channelRepository->add($channel);
		$channels[] = $channel;

		$channel = new \F3\Planetflow3\Domain\Model\Channel();
		$channel->setName('Karsten Dambekalns - Code & Content');
		$channel->setUrl('http://blog.k-fish.de');
		$channel->setFeedUrl('http://blog.k-fish.de/feeds/posts/default');
		$channel->setFetchedCategories(array('PHP', 'FLOW3'));
		$this->channelRepository->add($channel);
		$channels[] = $channel;

		$channel = new \F3\Planetflow3\Domain\Model\Channel();
		$channel->setName('networkteam Blog - FLOW3');
		$channel->setUrl('http://www.networkteam.com/blog.html');
		$channel->setFeedUrl('http://www.networkteam.com/rss.xml');
		$channel->setFilter('FLOW3');
		$channel->setDefaultCategory($flow3Category);
		$this->channelRepository->add($channel);
		$channels[] = $channel;

		$channel = new \F3\Planetflow3\Domain\Model\Channel();
		$channel->setName('Robert Lemke - Fluent Code Artisan');
		$channel->setUrl('http://robertlemke.de/blog/');
		$channel->setFeedUrl('http://robertlemke.de/blog/feeds/posts.rss.xml');
		$channel->setFilter('FLOW3,PHP');
		$channel->setDefaultCategory($flow3Category);
		$this->channelRepository->add($channel);
		$channels[] = $channel;

		$channel = new \F3\Planetflow3\Domain\Model\Channel();
		$channel->setName('t3blog.de');
		$channel->setUrl('http://t3blog.de');
		$channel->setFeedUrl('http://t3blog.de/feed/');
		$channel->setFilter('FLOW3');
		$channel->setDefaultCategory($flow3Category);
		$this->channelRepository->add($channel);
		$channels[] = $channel;

		$channel = new \F3\Planetflow3\Domain\Model\Channel();
		$channel->setName('layh.com');
		$channel->setUrl('http://www.layh.com');
		$channel->setFeedUrl('http://www.layh.com/wordpress/feed/');
		$channel->setFetchedCategories(array('FLOW3'));
		$this->channelRepository->add($channel);
		$channels[] = $channel;

		$this->response->appendContent('Created channels');
	}

	/**
	 * Fetches new items from all channels
	 *
	 * @return void
	 */
	public function fetchCommand() {
		$channels = $this->channelRepository->findAll();
		foreach ($channels as $channel) {
			echo "Fetching items for {$channel->getFeedUrl()}..." . PHP_EOL;
			$channel->fetchItems();
			echo "Done fetching items." . PHP_EOL;
		}
	}

}
?>