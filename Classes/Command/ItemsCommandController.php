<?php
namespace Planetflow3\Command;

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
 * Command controller to set up the Planetflow3 package
 *
 */
class ItemsCommandController extends \TYPO3\FLOW3\MVC\Controller\CommandController {

	/**
	 * @FLOW3\Inject
	 * @var \Planetflow3\Domain\Repository\ChannelRepository
	 */
	protected $channelRepository;

	/**
	 * @FLOW3\Inject
	 * @var \Planetflow3\Domain\Service\ChannelService
	 */
	protected $channelService;

	/**
	 * @FLOW3\Inject
	 * @var \Planetflow3\Domain\Repository\ItemRepository
	 */
	protected $itemRepository;

	/**
	 * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * Fetch new items from all channels
	 *
	 * This command should be run by a cronjob to do periodical
	 * updates to the channel feeds.
	 *
	 * @return void
	 */
	public function fetchCommand() {
		$command = $this;
		$channels = $this->channelRepository->findAll();
		foreach ($channels as $channel) {
			echo "Fetching items for {$channel->getFeedUrl()}..." . PHP_EOL;
			$this->channelService->fetchItems($channel, function($message, $severity = LOG_INFO) use ($command) {
				echo $message . PHP_EOL;
			});
			echo "Done fetching items." . PHP_EOL;
		}
	}

	/**
	 * Detect languages of items
	 *
	 * Should be used after an update of the language
	 * recognition files to re-classify items.
	 *
	 * @return void
	 */
	public function classifyLanguagesCommand() {
		$textcat = new \Libtextcat\Textcat();
		$items = $this->itemRepository->findAll();
		foreach ($items as $item) {
			$language = $textcat->classify($item->getDescription() . ' ' . $item->getContent());
			if ($language !== FALSE) {
				echo "Detected language $language for " . $item->getUniversalIdentifier() . PHP_EOL;
				$item->setLanguage($language);
			}
		}
	}

	/**
	 * Apply filters
	 *
	 * Should be used after an update of the filter rules to re-apply filters
	 * to description and content of an item.
	 *
	 * @return void
	 */
	public function applyFiltersCommand() {
		$items = $this->itemRepository->findAll();
		foreach ($items as $item) {
			$item->setDescription($item->getDescription());
			$item->setContent($item->getContent());
			$this->itemRepository->update($item);
		}
	}

}
?>