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
	 * @ORM\Column(nullable=true)
	 */
	protected $gender;

	/**
	 * The address
	 * @var string
	 * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=100 })
	 * @ORM\Column(nullable=true)
	 */
	protected $address;

	/**
	 * The contact number
	 * @var string
	 * @ORM\Column(nullable=true)
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
		parent::__construct();
		$this->createDate = new \DateTime();
	}

	/**
	 * Get the Employee's gender
	 *
	 * @return boolean The Employee's gender
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * Sets this Employee's gender
	 *
	 * @param boolean $gender The Employee's gender
	 * @return void
	 */
	public function setGender($gender) {
		$this->gender = $gender;
	}

	/**
	 * Get the Employee's address
	 *
	 * @return string The Employee's address
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * Sets this Employee's address
	 *
	 * @param string $address The Employee's address
	 * @return void
	 */
	public function setAddress($address) {
		$this->address = $address;
	}

	/**
	 * Get the Employee's contact number
	 *
	 * @return integer The Employee's contact number
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
	 * Get the Employee's create date
	 *
	 * @return \DateTime The Employee's create date
	 */
	public function getCreateDate() {
		return $this->createDate;
	}

	/**
	 * Sets this Employee's create date
	 *
	 * @param \DateTime $createDate The Employee's create date
	 * @return void
	 */
	public function setCreateDate($createDate) {
		$this->createDate = $createDate;
	}

	/**
	 * Get the Employee's token
	 *
	 * @return string The Employee's token
	 */
	public function getToken() {
		return $this->token;
	}

	/**
	 * Sets this Employee's token
	 *
	 * @param string $token The Employee's token
	 * @return void
	 */
	public function setToken($token) {
		$this->token = $token;
	}

	/**
	 * Get the Employee's leaves
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getLeaves() {
		return $this->leaves;
	}

	/**
	 * Sets this Employee's leaves
	 *
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
