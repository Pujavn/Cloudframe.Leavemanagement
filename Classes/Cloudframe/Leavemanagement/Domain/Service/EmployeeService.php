<?php

namespace Cloudframe\Leavemanagement\Domain\Service;

use TYPO3\Flow\Package\Package as BasePackage;
use TYPO3\Flow\Annotations as Flow;

/**
 * Service for employee
 *
 * @Flow\Scope("singleton")
 */
class EmployeeService {

	/**
	 * @Flow\Inject
	 * @var \Cloudframe\Leavemanagement\Domain\Repository\EmployeeRepository
	 */
	protected $employeeRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountFactory
	 */
	protected $accountFactory;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @FLow\Inject
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Cryptography\HashService
	 */
	protected $hashService;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persitenceManger;

	/**
	 * Creates admin account
	 *
	 * @param type $username
	 * @param type $password
	 * @param type $firstName
	 * @param type $lastName
	 * @return void
	 */
	public function addAdmin($username, $password, $firstName, $lastName) {
		$account = $this->accountFactory->createAccountWithPassword($username, $password, array('Cloudframe.Leavemanagement:Director'));
		$personName = new \TYPO3\Party\Domain\Model\PersonName('', $firstName, $lastName);
		$employee = new \Cloudframe\Leavemanagement\Domain\Model\Employee();
		$employee->addAccount($account);
		$employee->setName($personName);
		$employee->setToken('1');
		$email = new \TYPO3\Party\Domain\Model\ElectronicAddress();
		$email->setIdentifier($username);
		$email->setApproved('TRUE');
		$email->setType('Email');
		$email->setUsage('Work');
		$employee->setPrimaryElectronicAddress($email);
		$this->employeeRepository->add($employee);
		$this->accountRepository->add($account);
	}

}
