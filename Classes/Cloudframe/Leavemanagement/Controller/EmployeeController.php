<?php
namespace Cloudframe\Leavemanagement\Controller;

/*
 * This file is part of the Cloudframe.Leavemanagement package.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use \Cloudframe\Leavemanagement\Domain\Model\Employee;

class EmployeeController extends ActionController
{

    /**
     * @Flow\Inject
     * @var \Cloudframe\Leavemanagement\Domain\Service\EmployeeService
     */
    protected $employeeService;
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
	 * Injects settings of package
	 *
	 * @param array $settings
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

    /**
     * Shows a list of employees
     *
     * @return void
     */
    public function indexAction()
    {
        //$this->view->assign('currentEmployee', $this->securityContext->getParty());
        $this->view->assign('employees', $this->employeeService->findAllEmployees());
    }

    /**
     * Shows a single employee pbject
     * 
     * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee The employee to show
     * @return void
     */
    public function showAction(Employee $employee)
    {
        $this->view->assign('employee', $employee);
    }

    /**
     * Shows a form for creating new employee object
     *
     * @return void
     */
    public function newAction()
    {
    }

    /**
	 * Displays login form
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
	 *
	 */
	public function loginAction($leave = NULL) {
		$this->view->assign('leave', $leave);
	}

	/**
	 * Search a list of Employees
	 *
	 * @return void
	 */
	public function searchAction() {
		$this->view->assign('employees', $this->employeeService->findAllEmployees());
	}

	/**
	 * Authentication
	 *
	 * @return void
	 */
	public function authenticateAction() {
		try {
			$postArguments = $this->request->getHttpRequest()->getArguments();
			$userName = \TYPO3\Flow\Reflection\ObjectAccess::getPropertyPath($postArguments, '__authentication.TYPO3.Flow.Security.Authentication.Token.UsernamePassword.username');
			$this->employeeService->authenticate($userName);
			$currentUser =  $this->securityContext->getParty();
			$role = $this->employeeService->getRole($currentUser);
			if ($postArguments['leave'] !== '') {
				$leave = $this->leaveService->findByIdentifier($postArguments['leave']['__identity']);
				$this->redirect('approval', 'leave', 'Cloudframe.Leavemanagement', array('leave' => $leave));
			} elseif ($role == $this->settings['roles'][1]) {
				$this->redirect('index', 'Leave');
			} else {
				$this->redirect('profile', 'Employee');
			}
		} catch (\Cloudframe\Leavemanagement\Domain\Service\Exception\EmployeeNotApprovedException $notApproved) {
			$this->addFlashMessage($notApproved->getMessage(), '', \TYPO3\Flow\Error\Message::SEVERITY_ERROR, array(), 1334428113);
			$this->redirect('login', 'Employee');
		}
	}

	/**
	 * Logout
	 *
	 * @return void
	 */
	public function logoutAction() {
		$this->employeeService->logout();
		$this->addFlashMessage('Succesfully Logged out');
		$this->redirect('login', 'Employee');
	}

	/**
	 * Sends notification mail when employee is created
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee
	 * @param \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext
	 * @param string $password
	 * @return void
	 * @Flow\Signal
	 */
	protected function emitEmployeeCreated(\Cloudframe\Leavemanagement\Domain\Model\Employee $employee, \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext, $password) {

	}

	/**
	 * Creates a newEmployee
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $newEmployee
	 * @param string $password
	 * @param integer $role
     * @return void
     */
    public function createAction(\Cloudframe\Leavemanagement\Domain\Model\Employee $newEmployee, $password, $role)
    {
        try {
			$this->employeeService->createEmployee($newEmployee, $password, $role);
			//$this->emitEmployeeCreated($newEmployee, $this->controllerContext, $password);
            //var_dump($newEmployee);exit;
			$this->addFlashMessage('Employee is created.', '', \TYPO3\Flow\Error\Message::SEVERITY_OK, array(), 1334428113);
			$this->redirect('index', 'Employee');
		} catch (\Cloudframe\Leavemanagement\Domain\Service\Exception\EmployeeNotApprovedException $exception) {
			$this->addFlashMessage('Employee email already exist.', '', \TYPO3\Flow\Error\Message::SEVERITY_ERROR, array(), 1334428113);
			$this->redirect('new', 'Employee');
		}
    }

    /**
     * Shows a form for editing and existing employee object
     *
     * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee An employee to edit
     * @return void
     */
    public function editAction(Employee $employee)
    {
        $this->view->assign('employee', $employee);
    }

    /**
     * Shows a form to update an employee
     *
     * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee An employee to update
     * @return void
     */
    public function updateAction(\Cloudframe\Leavemanagement\Domain\Model\Employee $employee)
    {
        $this->employeeService->update($employee);
        $this->addFlashMessage('Updated the employee.');
        $this->redirect('index');
    }

    /**
     * Removes the given employee object from the employee repository
     *
     * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee An employee to delete
     * @return void
     */
    public function deleteAction(\Cloudframe\Leavemanagement\Domain\Model\Employee $employee)
    {
        $this->employeeService->deleteEmployee($employee);
        $this->addFlashMessage('Deleted a employee.');
        $this->redirect('index');
    }

	/**
	 * To show profile
	 *
	 * @return void
	 */
	public function profileAction() {
		$this->view->assign('employee', $this->securityContext->getParty());
	}

	/**
	 * Shows preview for creating Employee
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $newEmployee
	 * @param string $password
	 * @param integer $role
	 * @return void
	 */
	public function previewAction(\Cloudframe\Leavemanagement\Domain\Model\Employee $newEmployee, $password, $role) {
        $this->view->assignMultiple(array('password' => $password, 'employee' => $newEmployee, 'role' => $role));
       // var_dump($newEmployee);exit;
	}

	/**
	 * Validates the given employee object
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee
	 * @param string $token
	 * @return void
	 */
	public function validateAction(\Cloudframe\Leavemanagement\Domain\Model\Employee $employee, $token) {
		try {
			$this->employeeService->updateEmployee($employee, $token);
		} catch (\Cloudframe\Leavemanagement\Domain\Service\Exception\EmployeeNotApprovedException $exception) {
			$this->addFlashMessage('Token does not match.', '', \TYPO3\Flow\Error\Message::SEVERITY_ERROR, array(), 1334428113);
		}
		$this->addFlashMessage('Your account is active now.', '', \TYPO3\Flow\Error\Message::SEVERITY_OK, array(), 1334428113);
		$this->redirect('login', 'Employee');
	}

	/**
	 * Error message is not displayed
	 *
	 * @return boolean
	 */
	public function getErrorFlashMessage() {
		return FALSE;
	}

}
