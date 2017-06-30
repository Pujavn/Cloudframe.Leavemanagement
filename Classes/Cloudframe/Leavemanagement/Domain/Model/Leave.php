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
	 * The has director approved
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
	 * Get the Leave's reason
	 *
	 * @return string The Leave's reason
	 */
	public function getReason() {
		return $this->reason;
	}

	/**
	 * Sets this Leave's reason
	 *
	 * @param string $reason The Leave's reason
	 * @return void
	 */
	public function setReason($reason) {
		$this->reason = $reason;
	}

	/**
	 * Get the Leave's create date
	 *
	 * @return \DateTime The Leave's create date
	 */
	public function getCreateDate() {
		return $this->createDate;
	}

	/**
	 * Sets this Leave's create date
	 *
	 * @param \DateTime $createDate The Leave's create date
	 * @return void
	 */
	public function setCreateDate($createDate) {
		$this->createDate = $createDate;
	}

	/**
	 * Get the Leave's from date
	 *
	 * @return \DateTime The Leave's from date
	 */
	public function getFromDate() {
		return $this->fromDate;
	}

	/**
	 * Sets this Leave's from date
	 *
	 * @param \DateTime $fromDate The Leave's from date
	 * @return void
	 */
	public function setFromDate($fromDate) {
		$this->fromDate = $fromDate;
	}

	/**
	 * Get the Leave's to date
	 *
	 * @return \DateTime The Leave's to date
	 */
	public function getToDate() {
		return $this->toDate;
	}

	/**
	 * Sets this Leave's to date
	 *
	 * @param \DateTime $toDate The Leave's to date
	 * @return void
	 */
	public function setToDate($toDate) {
		$this->toDate = $toDate;
	}

	/**
	 * Get the Leave's has team leader approved
	 *
	 * @return boolean The Leave's has teamleader approved
	 */
	public function getHasTeamLeaderApproved() {
		return $this->hasTeamLeaderApproved;
	}

	/**
	 * Sets this Leave's has team leader approved
	 *
	 * @param boolean $hasTeamLeaderApproved The Leave's has teamleader approved
	 * @return void
	 */
	public function setHasTeamLeaderApproved($hasTeamLeaderApproved) {
		$this->hasTeamLeaderApproved = $hasTeamLeaderApproved;
	}

	/**
	 * Get the Leave's has director approved
	 *
	 * @return boolean The Leave's has director approved
	 */
	public function getHasDirectorApproved() {
		return $this->hasDirectorApproved;
	}

	/**
	 * Sets this Leave's has director approved
	 *
	 * @param boolean $hasDirectorApproved The Leave's has director approved
	 *
	 * @return void
	 */
	public function setHasDirectorApproved($hasDirectorApproved) {
		$this->hasDirectorApproved = $hasDirectorApproved;
	}

	/**
	 * Get the Leave's team leader
	 *
	 * @return \Cloudframe\Leavemanagement\Domain\Model\Employee The Leave's team leader
	 */
	public function getTeamLeader() {
		return $this->teamLeader;
	}

	/**
	 * Sets this Leave's team leader
     * 
	 * @param \Cloudframe\Leavemanagement\Domain\Model\TeamLeader $teamLeader
	 * @return void
	 */
	public function setTeamLeader($teamLeader) {
		$this->teamLeader = $teamLeader;
	}

	/**
	 * Get the Leave's employee
     * 
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
