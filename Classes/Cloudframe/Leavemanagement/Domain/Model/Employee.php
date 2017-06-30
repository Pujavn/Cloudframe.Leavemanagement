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
class Employee extends \TYPO3\Party\Domain\Model\Person {

	/**
	 * The gender
	 * @var boolean
	 *
	 */
	protected $gender;

	/**
	 * The address
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=100 })
	 */
	protected $address;

	/**
	 * The contact number0
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $contactNumber;

	/**
	 * The create date
	 * @var \DateTime
	 */
	protected $createDate;

	/**
	 * The token
	 * @var string
	 */
	protected $token;

	/**
	 * The leaves
	 * @var \Doctrine\Common\Collections\Collection<\Cloudframe\Leavemanagement\Domain\Model\Leave>
	 * @ORM\OneToMany(mappedBy="employee", cascade="remove")
	 */
	protected $leaves;

	/**
	 * Constructs Date
	 */
	public function __construct() {
		$this->createDate = new \DateTime();
	}

	/**
	 * @return boolean
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @param boolean $gender
	 * @return void
	 */
	public function setGender($gender) {
		$this->gender = $gender;
	}

	/**
	 * @return string
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param string $address
	 * @return void
	 */
	public function setAddress($address) {
		$this->address = $address;
	}

	/**
	 * @return string
	 */
	public function getContactNumber() {
		return $this->contactNumber;
	}

	/**
	 * @param string $contactNumber
	 * @return void
	 */
	public function setContactNumber($contactNumber) {
		$this->contactNumber = $contactNumber;
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
	 * @return string
	 */
	public function getToken() {
		return $this->token;
	}

	/**
	 * @param string $token
	 * @return void
	 */
	public function setToken($token) {
		$this->token = $token;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getLeaves() {
		return $this->leaves;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $leaves
	 * @return void
	 */
	public function setLeaves($leaves) {
		$this->leaves = $leaves;
	}

	/**
	 * Adds a leave for employee
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
	 * @return void
	 */
	public function addLeave(\Cloudframe\Leavemanagement\Domain\Model\Leave $leave) {
		$this->leaves->add($leave);
	}

	/**
	 * Removes leave of an employee
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
	 */
	public function removeLeave(\Cloudframe\Leavemanagement\Domain\Model\Leave $leave) {
		$this->leaves->removeElement($leave);
	}

}
