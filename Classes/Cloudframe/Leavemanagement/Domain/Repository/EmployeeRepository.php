<?php
namespace Cloudframe\Leavemanagement\Domain\Repository;

/*
 * This file is part of the Cloudframe.Leavemanagement package.
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class EmployeeRepository extends \TYPO3\Flow\Persistence\Repository {

	/**
	 * Find all team leaders
	 *
	 * @param string $type
	 */
	public function findTeamLeaders($type) {
		$query = $this->createQuery();
		$query->matching(
				$query->contains('accounts.roles', $type)
		);
		return $query->execute();
	}

	/**
	 * Find employee by name
	 *
	 * @param string $name
	 * @return \Cloudframe\Leavemanagement\Domain\Model\Employee
	 */
	public function findByEmployeeName($name) {
		$query = $this->createQuery();
		$query->matching(
				$query->like('name.firstName', '%' . $name . '%')
		);
		return $query->execute();
    }

}

?>
