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
	 * @var \Cloudframe\Leavemanagement\Domain\Service\LeaveService
	 */
	protected $leaveService;

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
	 * @param string $username
	 * @param string $password
	 * @param string $firstName
	 * @param string $lastName
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
		//Getting error for primary address
		$this->employeeRepository->add($employee);
		$this->accountRepository->add($account);
	}

	/**
	 * Authenticates Employee
	 *
	 * @param string $userName The username of the employee
	 * @return void
	 * @throws \Cloudframe\Leavemanagement\Domain\Service\Exception\EmployeeNotApprovedException
	 */
	public function authenticate($userName) {
		if (count($this->accountRepository->findByAccountIdentifier($userName)) !== 0) {
			if ($this->accountRepository->findByAccountIdentifier($userName)->getFirst()->getParty()->getPrimaryElectronicAddress()->isApproved()) {
				$this->authenticationManager->authenticate();
			} else {
				throw new \Cloudframe\Leavemanagement\Domain\Service\Exception\EmployeeNotApprovedException(sprintf(('User not approved.')));
			}
		} else {

			throw new \Cloudframe\Leavemanagement\Domain\Service\Exception\EmployeeNotApprovedException(sprintf(('Username does not exist.')));
		}
	}

	/**
	 * Creates Employee
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $newEmployee
	 * @param string $password
	 * @param integer $role
	 * @return void
	 */
	public function createEmployee(\Cloudframe\Leavemanagement\Domain\Model\Employee $newEmployee, $password, $role) {
		$emailAddress = $newEmployee->getPrimaryElectronicAddress()->getIdentifier();
		$accounts = $this->accountRepository->findAll();
		foreach ($accounts as $account) {
			$existingAccount = $account->getAccountIdentifier();
			if ($emailAddress == $existingAccount) {
				throw new \Cloudframe\Leavemanagement\Domain\Service\Exception\EmployeeNotApprovedException();
			}
		}
		if ($role == 1) {
			$roleIdentifiers = array('Cloudframe.Leavemanagement:Teamleader');
		} else {
			$roleIdentifiers = array('Cloudframe.Leavemanagement:Developer');
		}
		$account = $this->accountFactory->createAccountWithPassword($emailAddress, $password, $roleIdentifiers);
		$this->accountRepository->add($account);
		$newEmployee->addAccount($account);
		$newEmployee->setToken($this->hashService->generateHmac($emailAddress));
		$this->employeeRepository->add($newEmployee);
	}

	/**
	 * Logout
	 *
	 * @return void
	 */
	public function logout() {
		$this->authenticationManager->logout();
	}

	/**
	 * Return all employees
	 *
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findAllEmployees() {
		return $this->employeeRepository->findAll();
	}

	/**
	 * Returns Login Employee Role
	 *
	 * @return string $role
	 */
	public function getRole($employee) {
		$accounts = $this->accountRepository->findByParty($employee);
		foreach ($accounts as $account) {
			$roles = $account->getRoles();
			foreach ($roles as $role) {
				return $role;
			}
		}
	}

	/**
	 * Updates electronic address once employee clicks on verification link
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee
	 * @param string $token
	 * @return void
	 * @throws \Cloudframe\Leavemanagement\Domain\Service\Exception\EmployeeNotApprovedException
	 */
	public function updateEmployee(\Cloudframe\Leavemanagement\Domain\Model\Employee $employee, $token) {
		if ($token == $employee->getToken()) {
			$employee->getPrimaryElectronicAddress()->setApproved(TRUE);
			$this->employeeRepository->update($employee);
			$this->persistenceManager->persistAll();
		} else {
			throw new \Cloudframe\Leavemanagement\Domain\Service\Exception\EmployeeNotApprovedException();
		}
	}

	/**
	 * Removes employee from the repository
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee
	 * @return void
	 */
	public function delete(\Cloudframe\Leavemanagement\Domain\Model\Employee $employee) {
		$this->employeeRepository->remove($employee);
	}

	/**
	 * Removes the given employee object from the employee repository
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee
	 */
	public function deleteEmployee(\Cloudframe\Leavemanagement\Domain\Model\Employee $employee) {
		$role = $this->getRole($employee);
		if ($role == 'Cloudframe.Leavemanagement:Teamleader') {
			$leaves = $this->leaveService->findByTeamLeader($employee);
			foreach ($leaves as $leave) {
				$leave->removeTeamLeader($employee);
				$this->persistenceManager->persistAll();
				$this->leaveService->update($leave);
			}
		}
		$accounts = $this->accountRepository->findByParty($employee);
		foreach ($accounts as $account) {
			$this->accountRepository->remove($account);
			$this->persistenceManager->persistAll();
		}

		$this->delete($employee);
		$this->persistenceManager->persistAll();
	}

	/**
	 * Updates the given employee object in the employee repository
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee The employee to update
	 * @return void
	 */
	public function update(\Cloudframe\Leavemanagement\Domain\Model\Employee $employee) {
		$this->employeeRepository->update($employee);
	}

}

?>