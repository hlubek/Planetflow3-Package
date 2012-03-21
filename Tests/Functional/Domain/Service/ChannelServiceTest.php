<?php
namespace Planetflow3\Tests\Functional\Domain\Service;

/*                                                                        *
 * This script belongs to the FLOW3 package "Planetflow3".                *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Functional tests for the ChannelService
 */
class ChannelServiceTest extends \TYPO3\FLOW3\Tests\FunctionalTestCase {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Planetflow3\Domain\Repository\ChannelRepository
	 */
	protected $categoryRepository;

	/**
	 * @var \Planetflow3\Domain\Repository\ChannelRepository
	 */
	protected $channelRepository;

	/**
	 * @var \Planetflow3\Domain\Repository\ItemRepository
	 */
	protected $itemRepository;

	/**
	 * @var \TYPO3\FLOW3\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * @var \Planetflow3\Domain\Service\ChannelService
	 */
	protected $channelService;

	/**
	 * Set up dependencies
	 */
	public function setUp() {
		parent::setUp();
		$this->categoryRepository = $this->objectManager->get('Planetflow3\Domain\Repository\CategoryRepository');
		$this->channelRepository = $this->objectManager->get('Planetflow3\Domain\Repository\ChannelRepository');
		$this->itemRepository = $this->objectManager->get('Planetflow3\Domain\Repository\ItemRepository');

		$this->channelService = $this->objectManager->get('Planetflow3\Domain\Service\ChannelService');

		$this->persistenceManager = $this->objectManager->get('TYPO3\FLOW3\Persistence\PersistenceManagerInterface');
	}

	/**
	 * @test
	 */
	public function fetchItemsGetsMatchingItemsFromFeed() {
		$channel = new \Planetflow3\Domain\Model\Channel();
		$channel->setFeedUrl('file://' . __DIR__ . '/../../Fixtures/Feeds/news.typo3.org-news-teams-flow3-rss.xml');
		$channel->setName('Test Feed');
		$channel->setUrl('http://www.example.com/test');
		$this->channelRepository->add($channel);

		$this->channelService->fetchItems($channel);

		$this->assertEquals(10, count($channel->getItems()), 'Should fetch all 10 items from feed');


	}

}
?>