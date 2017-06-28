<?php

namespace Cloudframe\Leavemanagement\Domain\Model;

/*
 * This file is part of the Cloudframe.Leavemanagement package.
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Leave {

	/**
	 * The reason
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $reason;

	/**
	 * The create date
	 * @var \DateTime
	 */
	protected $createDate;

	/**
	 * The from date
	 * @var \DateTime
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $fromDate;

	/**
	 * The to date
	 * @var \DateTime
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $toDate;

	/**
	 * The has team leader approved
	 * @var boolean
	 */
	protected $hasTeamLeaderApproved;

	/**
	 * The has directory approved
	 * @var boolean
	 */
	protected $hasDirectorApproved;

	/**
	 * The team leader
	 * @var \Cloudframe\Leavemanagement\Domain\Model\Employee
	 * @ORM\ManyToOne
	 */
	protected $teamLeader;

	/**
	 * The employee
	 * @var \Cloudframe\Leavemanagement\Domain\Model\Employee
	 * @ORM\ManyToOne(inversedBy="leaves")
	 */
	protected $employee;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->createDate = new \DateTime();
		$this->hasDirectorApproved = 0;
		$this->hasTeamLeaderApproved = 0;
	}

	/**
	 * @return string
	 */
	public function getReason() {
		return $this->reason;
	}

	/**
	 * @param string $reason
	 * @return void
	 */
	public function setReason($reason) {
		$this->reason = $reason;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreateDate() {
		return $this->createDate;
	}

	/**
	 * @param \DateTime $createDate
	 * @return void
	 */
	public function setCreateDate($createDate) {
		$this->createDate = $createDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getFromDate() {
		return $this->fromDate;
	}

	/**
	 * @param \DateTime $fromDate
	 * @return void
	 */
	public function setFromDate($fromDate) {
		$this->fromDate = $fromDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getToDate() {
		return $this->toDate;
	}

	/**
	 * @param \DateTime $toDate
	 * @return void
	 */
	public function setToDate($toDate) {
		$this->toDate = $toDate;
	}

	/**
	 * @return boolean
	 */
	public function getHasTeamLeaderApproved() {
		return $this->hasTeamLeaderApproved;
	}

	/**
	 * @param boolean $hasTeamLeaderApproved
	 * @return void
	 */
	public function setHasTeamLeaderApproved($hasTeamLeaderApproved) {
		$this->hasTeamLeaderApproved = $hasTeamLeaderApproved;
	}

	/**
	 * @return boolean
	 */
	public function getHasDirectorApproved() {
		return $this->hasDirectorApproved;
	}

	/**
	 * @param boolean $hasDirectorApproved
	 * @return void
	 */
	public function setHasDirectorApproved($hasDirectorApproved) {
		$this->hasDirectorApproved = $hasDirectorApproved;
	}

	/**
	 * @return \Cloudframe\Leavemanagement\Domain\Model\TeamLeader
	 */
	public function getTeamLeader() {
		return $this->teamLeader;
	}

	/**
	 * @param \Cloudframe\Leavemanagement\Domain\Model\TeamLeader $teamLeader
	 * @return void
	 */
	public function setTeamLeader($teamLeader) {
		$this->teamLeader = $teamLeader;
	}

	/**
	 * @return \Cloudframe\Leavemanagement\Domain\Model\Employee
	 */
	public function getEmployee() {
		return $this->employee;
	}

	/**
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee
	 * @return void
	 */
	public function setEmployee($employee) {
		$this->employee = $employee;
	}

}
