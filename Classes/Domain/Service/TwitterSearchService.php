<?php
namespace Planetflow3\Domain\Service;

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
 * A Twitter search service
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class TwitterSearchService {

	/**
	 *
	 * @param string $query
	 * @return void
	 */
	public function findTweets($query) {
		$url = 'http://search.twitter.com/search.json?q=' . urlencode($query);
		$result = file_get_contents($url);
		$tweets = json_decode($result, TRUE);
	}

}
?>