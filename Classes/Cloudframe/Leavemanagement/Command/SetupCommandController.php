<?php

namespace Cloudframe\Leavemanagement\Command;

/*
 * This file is part of the Cloudframe.Leavemanagement package.
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class SetupCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @Flow\Inject
	 * @var \Cloudframe\Leavemanagement\Domain\Service\EmployeeService
	 */
	protected $employeeService;

	/**
	 * Create new admin
	 * @param string $username
	 * @param string $password
	 * @param string $firstName
	 * @param string $lastName
	 *
	 * @return void
	 */
	public function createAdminCommand($username, $password, $firstName, $lastName) {
		$this->employeeService->addAdmin($username, $password, $firstName, $lastName);
		$this->outputLine('Admin Created Succesfully.');
	}

}
