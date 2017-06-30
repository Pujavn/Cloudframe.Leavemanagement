<?php

namespace Cloudframe\Leavemanagement\Domain\Service;

use TYPO3\Flow\Package\Package as BasePackage;
use TYPO3\Flow\Annotations as Flow;

/**
 * Service for leave
 *
 * @Flow\Scope("singleton")
 */
class LeaveService {

	/**
	 * @Flow\Inject
	 * @var \Cloudframe\Leavemanagement\Domain\Repository\EmployeeRepository
	 */
	protected $employeeRepository;

    /**
	 * @Flow\Inject
	 * @var \Cloudframe\Leavemanagement\Domain\Repository\LeaveRepository
	 */
	protected $leaveRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @param array $settings
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * Returns all leaves
	 *
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findAllLeaves() {
		return $this->leaveRepository->findAll();
	}

	/**
	 * Returns all leaves applied to a particular team leader
	 * @param type $employee
	 * @return type
	 */
	public function findByTeamLeader($employee) {
		return $this->leaveRepository->findByTeamLeader($employee);
			}

	/**
	 * Returns all leaves
	 *
	 * @return array
	 */
	public function findTeamLeaders() {

		return $this->employeeRepository->findTeamLeaders($this->settings['roles'][2]);
		}

	public function createLeave(\Cloudframe\Leavemanagement\Domain\Model\Leave $newLeave) {
		$employee = $this->securityContext->getParty();
		$newLeave->setEmployee($employee);
		$newLeave->getFromDate()->setTime('00', '00', '00');
		$newLeave->getToDate()->setTime('23', '59', '59');
		$newLeave->getCreateDate()->setTime('00', '00', '00');
		$this->leaveRepository->add($newLeave);
	}

	public function ajaxGrid($arguments) {
		$page = $arguments['page'];
			// get how many rows we want to have into the grid
		$limit = $arguments['rows'];
			// get index row - i.e. user click to sort
		$sidx = $arguments['sidx'];
			// get the direction
		$sord = $arguments['sord'];
		if (array_key_exists('fromDate', $arguments)) {
			$fromdate = new \DateTime($arguments['fromDate']);
		} else {
			$fromdate = '';
		}
		if (array_key_exists('toDate', $arguments)) {
			$todate = new \DateTime($arguments['toDate']);
			$todate->setTime('23', '59', '59');
		} else {
			$todate = '';
	}

		if ($arguments['_search'] === 'true') {
			if ((array_key_exists('searchField', $arguments) && $arguments['searchField'] === 'employee') || array_key_exists('employee', $arguments)) {
				if (array_key_exists('searchField', $arguments)) {
					$name = $arguments['searchString'];
				} else {
					$name = $arguments['employee'];
}
				$leaves = $this->leaveRepository->findLeaveByEmployeeName($name);
				$count = count($leaves);
				$pageOffset = $this->getPageOffset($count, $page, $limit);
			} else {
				$count = $this->leaveRepository->getLeavesFromRangeDate($fromdate, $todate);
				$pageOffset = $this->getPageOffset($count, $page, $limit);
				$leaves = $this->leaveRepository->filterRangeLeaves($sidx, $sord, (int) $pageOffset['start'], (int) $limit, $fromdate, $todate);
			}

		} else {
			$leaves = $this->leaveRepository->findAll();
			$count = count($leaves);
			$pageOffset = $this->getPageOffset($count, $page, $limit);
	}

		$responce['page'] = $pageOffset['page'];
		$responce['total'] = $pageOffset['total_pages'];
		$responce['records'] = $count;
		$id = 0;
		$n = 0;
		foreach ($leaves as $leave) {
			$teamLeaderApproval = ($leave->getHasTeamleaderApproved() == FALSE) ? 'No' : 'Yes';
			$directorApproval = ($leave->getHasDirectorApproved() == FALSE) ? 'No' : 'Yes';
			$responce['rows'][$n]['id'] = $id;
			$responce['rows'][$n]['cell'] = array(
				$leave->getEmployee()->getName()->getFullName(),
				$leave->getReason(),
				$leave->getFromDate()->format('Y-m-d'),
				$leave->getToDate()->format('Y-m-d'),
				$teamLeaderApproval,
				$directorApproval);
			$n++;
			$id++;
		}
		return json_encode($responce);
	}

	/**
	 * Returns PageOffset
	 *
	 * @param integer $count
	 * @param integer $page
	 * @param integer $limit
	 * @return array
	 */
	private function getPageOffset($count, $page, $limit) {
		$pageOffset = array();
		if ($count > 0) {
			$pageOffset['total_pages'] = ceil($count / $limit);
		} else {
			$pageOffset['total_pages'] = 0;
			}
		if ($page > $pageOffset['total_pages']) {
			$pageOffset['page'] = $pageOffset['total_pages'];
		} else {
			$pageOffset['page'] = $page;
		}
			// do not put  $limit*($page - 1)
		$pageOffset['start'] = $limit * $page - $limit;

		return $pageOffset;
	}

	/**
	 * Updates Leave once Teamleader Accepts or Rejects Leave
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
	 * @return void
	 */
	public function update(\Cloudframe\Leavemanagement\Domain\Model\Leave $leave) {
		$this->leaveRepository->update($leave);
		}

	/**
	 * Updates Leave once Teamleader Accepts or Rejects Leave
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
	 * @return void
	 */
	public function remove(\Cloudframe\Leavemanagement\Domain\Model\Leave $leave) {
		$this->leaveRepository->remove($leave);
}

	/**
	 * Returns all leaves
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findByEmployee($employee) {
		return $this->leaveRepository->findByEmployee($employee);
			}

	/**
	 * Find the leave object by identifier
	 *
	 * @param type $id
	 * @return \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
	 */

	public function findByIdentifier($id) {
		return $this->leaveRepository->findByIdentifier($id);
	}

}

?>