<?php

namespace Cloudframe\Leavemanagement\Controller;

/* *
 * This script belongs to the TYPO3 Flow package "Cloudframe.Leavemanagement".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use \Cloudframe\Leavemanagement\Domain\Model\Leave;
use TYPO3\Flow\Property\TypeConverter\DateTimeConverter;

/**
 * Leave controller for the Cloudframe.Leavemanagement package
 *
 * @Flow\Scope("singleton")
 */
class LeaveController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \Cloudframe\Leavemanagement\Domain\Service\LeaveService
	 */
	protected $leaveService;

	/**
	 * @Flow\Inject
	 * @var \Cloudframe\Leavemanagement\Domain\Service\EmployeeService
	 */
	protected $employeeService;

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
	 * Shows a list of leaves
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('leaves', $this->leaveService->findAllLeaves());
	}

	/**
	 * Shows a single leave object
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave The leave to show
	 * @return void
	 */
	public function showAction(Leave $leave) {
		$this->view->assign('leave', $leave);
	}

	/**
	 * Shows a form for creating a new leave object
	 *
	 * @return void
	 */
	public function newAction() {
		$this->view->assign('teamLeaders', $this->leaveService->findTeamLeaders());
	}

	/**
	 * Displays leave of a logged in employee
	 *
	 * @return void
	 */
	public function viewAction() {
		$this->view->assign('employee', $this->securityContext->getParty());
	}

	/**
	 * Lists leaves applied to the team Leader
	 *
	 * @return void
	 */
	public function listAction() {
		$emp = $this->securityContext->getParty();
		$this->view->assign('leaves', $this->leaveService->findByTeamLeader($emp));
	}

	/**
	 * Initialize dates
	 *
	 * @return void
	 */
	public function initializeCreateAction() {
		$this->arguments['newLeave']->getPropertyMappingConfiguration()->forProperty('toDate')->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\DateTimeConverter', \TYPO3\Flow\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'Y-m-d');
		$this->arguments['newLeave']->getPropertyMappingConfiguration()->forProperty('fromDate')->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\DateTimeConverter', \TYPO3\Flow\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'Y-m-d');
	}

	/**
	 * Adds the given new leave object to the leave repository
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $newLeave A new leave to add
	 * @return void
	 */
	public function createAction(\Cloudframe\Leavemanagement\Domain\Model\Leave $newLeave) {
		$this->leaveService->createLeave($newLeave);
		$this->emitLeaveCreated($newLeave, $this->controllerContext);
		$this->addFlashMessage('Leave has been sent for approval to your Team Leader');
		$this->redirect('profile', 'Employee');
	}

	/**
	 * Send email notification to teamleader
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
	 * @param \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext
	 * @return void
	 * @Flow\Signal
	 */
	public function emitLeaveCreated(\Cloudframe\Leavemanagement\Domain\Model\Leave $leave, \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext) {
	}
	/**
	 * Send email notification to teamleader and developer
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
	 * @param \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext
	 * @return void
	 * @Flow\Signal
	 */
	public function emitDirectorLeaveApproval(\Cloudframe\Leavemanagement\Domain\Model\Leave $leave, \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext) {

	}

	/**
	 * Send email notification to Director and developer
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
	 * @param \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext
	 * @return void
	 * @Flow\Signal
	 */
	public function emitTeamLeaderLeaveApproval(\Cloudframe\Leavemanagement\Domain\Model\Leave $leave, \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext) {

	}

	/**
	 * Initialize dates
	 *
	 * @return void
	 */
	public function initializePreviewAction() {
		$this->arguments['newLeave']->getPropertyMappingConfiguration()->forProperty('toDate')->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\DateTimeConverter', \TYPO3\Flow\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'Y-m-d');
		$this->arguments['newLeave']->getPropertyMappingConfiguration()->forProperty('fromDate')->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\DateTimeConverter', \TYPO3\Flow\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'Y-m-d');
	}

	/**
	 * Shows preview for creating Leave
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $newLeave
	 * @return void
	 */
	public function previewAction(\Cloudframe\Leavemanagement\Domain\Model\Leave $newLeave) {

		$this->view->assign('leave', $newLeave);
	}

	/**
	 * Ajax call for listing of leaves
	 * @return string
	 */
	public function ajaxAction() {
		$arguments = $this->request->getArguments();
		return $this->leaveService->ajaxGrid($arguments);
	}

	/**
	 * Team leader Approves Leave
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
	 * @return void
	 */
	public function approvalAction(\Cloudframe\Leavemanagement\Domain\Model\Leave $leave) {
		$emp = $this->securityContext->getParty();
		if ($emp === NULL) {
			$this->redirect('login', 'employee', 'Cloudframe.Leavemanagement', array('leave' => $leave));
		} else {
			$this->view->assign('leave', $leave);
		}
	}

	/**
	 * Approves Leave
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
	 * @return void
	 */
	public function leaveApproveAction(\Cloudframe\Leavemanagement\Domain\Model\Leave $leave) {

		$this->leaveService->update($leave);
		$this->addFlashMessage('Leave is Approved', '', \TYPO3\Flow\Error\Message::SEVERITY_OK, array(), 1334428113);
		$currentUser =  $this->securityContext->getParty();
		$role = $this->employeeService->getRole($currentUser);
		if ($role == $this->settings['roles']['1']) {
			$this->emitDirectorLeaveApproval($leave, $this->controllerContext);
			$this->redirect('index', 'Leave');
		} else {

			$this->emitTeamLeaderLeaveApproval($leave, $this->controllerContext);
			$this->redirect('list', 'Leave');
		}
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

?>