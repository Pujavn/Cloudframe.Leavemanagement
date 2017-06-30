<?php
namespace Cloudframe\Leavemanagement\Domain\Repository;

/*
 * This file is part of the Cloudframe.Leavemanagement package.
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Leaves
 * 
 * @Flow\Scope("singleton")
 */
class LeaveRepository extends \TYPO3\Flow\Persistence\Repository {

	/**
	 * Returns matching fromdate
	 * @param \DateTime $fromdate
	 * @param \DateTime $todate
	 * @return integer $query
	 */
	public function getLeavesFromRangeDate($fromdate, $todate) {
		$query = $this->createQuery();
		if ($fromdate && $todate) {
			return $query->matching($query->logicalAnd($query->greaterThanOrEqual('fromDate', $fromdate), $query->lessThanOrEqual('toDate', $todate)))->execute()->count();
		} elseif ($fromdate) {
			return $query->matching($query->greaterThanOrEqual('fromDate', $fromdate))->execute()->count();
		} else {
			return $query->matching($query->lessThanOrEqual('toDate', $todate))->execute()->count();
		}
	}

	/**
	 * Returns records on page selected
	 *
	 * @param string $sidx
	 * @param string $sord
	 * @param integer $start
	 * @param string $limit
	 * @param \DateTime $fromdate
	 * @param \DateTime $todate
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function filterRangeLeaves($sidx = '', $sord = 'ASC', $start = 0, $limit = NULL, $fromdate, $todate) {
		if (strtoupper($sord) == 'ASC') {
			$order = \TYPO3\Flow\Persistence\QueryInterface::ORDER_ASCENDING;
		} else {
			$order = \TYPO3\Flow\Persistence\QueryInterface::ORDER_DESCENDING;
}
		$query = $this->createQuery();
		if ($fromdate && $todate) {
			return $query->matching($query->logicalAnd($query->greaterThanOrEqual('fromDate', $fromdate), $query->lessThanOrEqual('toDate', $todate)))->execute();
		} elseif ($fromdate) {
			return $query->matching($query->greaterThanOrEqual('fromDate', $fromdate))->execute();
		} else {
			return $query->matching($query->lessThanOrEqual('toDate', $todate))->execute();
		}
	}

	/**
	 * Find Leaves by employee Name
	 *
	 * @param string $name
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findLeaveByEmployeeName($name) {
		$query = $this->createQuery();
		$query->matching(
				$query->like('employee.name.firstName', '%' . $name . '%', FALSE)
		);
		return $query->execute();
	}

}

?>
