<?php
namespace Cloudframe\Leavemanagement\Controller;

/*
 * This file is part of the Cloudframe.Leavemanagement package.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use Cloudframe\Leavemanagement\Domain\Model\Employee;

class EmployeeController extends ActionController
{

    /**
     * @Flow\Inject
     * @var \Cloudframe\Leavemanagement\Domain\Repository\EmployeeRepository
     */
    protected $employeeRepository;

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign('employees', $this->employeeRepository->findAll());
    }

    /**
     * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee
     * @return void
     */
    public function showAction(Employee $employee)
    {
        $this->view->assign('employee', $employee);
    }

    /**
     * @return void
     */
    public function newAction()
    {
    }

    /**
     * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $newEmployee
     * @return void
     */
    public function createAction(Employee $newEmployee)
    {
        $this->employeeRepository->add($newEmployee);
        $this->addFlashMessage('Created a new employee.');
        $this->redirect('index');
    }

    /**
     * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee
     * @return void
     */
    public function editAction(Employee $employee)
    {
        $this->view->assign('employee', $employee);
    }

    /**
     * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee
     * @return void
     */
    public function updateAction(Employee $employee)
    {
        $this->employeeRepository->update($employee);
        $this->addFlashMessage('Updated the employee.');
        $this->redirect('index');
    }

    /**
     * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee
     * @return void
     */
    public function deleteAction(Employee $employee)
    {
        $this->employeeRepository->remove($employee);
        $this->addFlashMessage('Deleted a employee.');
        $this->redirect('index');
    }

}
