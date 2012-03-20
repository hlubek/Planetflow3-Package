<?php
namespace Planetflow3\Domain\Repository;

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
 * A repository for Items
 *
 */
class ItemRepository extends \TYPO3\FLOW3\Persistence\Repository {

	/**
	 * @var array
	 */
	protected $defaultOrderings = array('publicationDate' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING);

	/**
	 *
	 * @param integer $offset
	 * @param integer $limit
	 * @param string $language
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
	 */
	public function findRecent($offset = 0, $limit = 10, $language = NULL) {
		$query = $this->createQuery();
		if ($language !== NULL) {
			$query->matching($query->equals('language', $language));
		}
		$query->setOrderings(array('publicationDate' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING));
		$query->setOffset($offset);
		$query->setLimit($limit);
		return $query->execute();
	}

	/**
	 *
	 * @param \Planetflow3\Domain\Dto\ItemFilter $filter
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
	 */
	public function findByFilter(\Planetflow3\Domain\Dto\ItemFilter $filter) {
		$query = $this->createQuery();
		$constraints = array();
		if ($filter->getChannel() !== NULL) {
			$constraints[] = $query->equals('channel', $filter->getChannel());
		}
		if ($filter->getCategory() !== NULL) {
			$constraints[] = $query->contains('categories', $filter->getCategory());
		}
		if (count($constraints) > 0) {
			$query->matching($query->logicalAnd($constraints));
		}
		return $query->execute();
	}

}
?>