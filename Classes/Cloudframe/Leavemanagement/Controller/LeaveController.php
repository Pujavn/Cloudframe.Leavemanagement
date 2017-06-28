<?php
namespace Cloudframe\Leavemanagement\Controller;

/*
 * This file is part of the Cloudframe.Leavemanagement package.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use Cloudframe\Leavemanagement\Domain\Model\Leave;

class LeaveController extends ActionController
{

    /**
     * @Flow\Inject
     * @var \Cloudframe\Leavemanagement\Domain\Repository\LeaveRepository
     */
    protected $leaveRepository;

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign('leaves', $this->leaveRepository->findAll());
    }

    /**
     * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
     * @return void
     */
    public function showAction(Leave $leave)
    {
        $this->view->assign('leave', $leave);
    }

    /**
     * @return void
     */
    public function newAction()
    {
    }

    /**
     * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $newLeave
     * @return void
     */
    public function createAction(Leave $newLeave)
    {
        $this->leaveRepository->add($newLeave);
        $this->addFlashMessage('Created a new leave.');
        $this->redirect('index');
    }

    /**
     * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
     * @return void
     */
    public function editAction(Leave $leave)
    {
        $this->view->assign('leave', $leave);
    }

    /**
     * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
     * @return void
     */
    public function updateAction(Leave $leave)
    {
        $this->leaveRepository->update($leave);
        $this->addFlashMessage('Updated the leave.');
        $this->redirect('index');
    }

    /**
     * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
     * @return void
     */
    public function deleteAction(Leave $leave)
    {
        $this->leaveRepository->remove($leave);
        $this->addFlashMessage('Deleted a leave.');
        $this->redirect('index');
    }

}
