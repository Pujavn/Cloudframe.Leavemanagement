<?php

namespace Cloudframe\Leavemanagement\Service;

/**
 *  This script belongs to the TYPO3 Flow package "Cloudframe.Leavemanagement".
 */
use TYPO3\Flow\Annotations as Flow;

/**
 * A notification service to send emails
 */
class Notification {

	/**
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Fluid\View\StandaloneView
	 */
	protected $standaloneView;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * Injects settings of this package
	 *
	 * @param array $settings
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * Send Notification email to employee at the time of registraion
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee
	 * @param \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext
	 * @param string $password
	 * @return void
	 */
	public function sendEmployeeRegistrationNotification(\Cloudframe\Leavemanagement\Domain\Model\Employee $employee, \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext, $password) {

		$baseUrl = $this->standaloneView->getRequest()->getHttpRequest()->getBaseUri();
		$this->standaloneView->assign('baseUrl', $baseUrl);

		$url = $controllerContext->getUriBuilder()->reset()->setCreateAbsoluteUri(TRUE)->uriFor(
				'validate', array('employee' => $employee, 'token' => $employee->getToken()), 'Employee', 'Cloudframe.Leavemanagement'
		);
		$this->standaloneView->setFormat('html');
		$templatepath = FLOW_PATH_PACKAGES . 'Application/Cloudframe.Leavemanagement/Resources/Private/Templates/Emails/EmployeeRegistration.html';
		$this->standaloneView->setTemplatePathAndFilename($templatepath);
		$this->standaloneView->assign('employee', $employee);
		$this->standaloneView->assign('url', $url);
		$this->standaloneView->assign('password', $password);
		$toEmail = $employee->getPrimaryElectronicAddress()->getIdentifier();
		$toName = $employee->getName()->getFirstName() . ' ' . $employee->getName()->getLastName();
		$this->standaloneView->assign('username', $toEmail);
		$emailBody = $this->standaloneView->render();
		$mail = new \TYPO3\SwiftMailer\Message();
		$mail->setFrom(array($this->settings['notifications']['from']['email'] => $this->settings['notifications']['from']['name']))
				->setTo(array($toEmail => $toName))
				->setSubject('Cloudframe Leave Registration')
				->setBody($emailBody, 'text/html')
				->send();
	}

	/* Sends confirmation mail to user
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Employee $employee
	 * @param \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext
	 * @param type $password
	 */

	public function sendNotification(\Cloudframe\Leavemanagement\Domain\Model\Employee $employee, \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext, $password) {
		$mail = new \TYPO3\SwiftMailer\Message();
		$mail->setFrom(array('emailid@Cloudframe.com' => 'emailid@Cloudframe.com'));
		$mail->setTo(array('email@Cloudframe.com' => 'emailid@Cloudframe.com'));
		$mail->setSubject('Confirmation mail');
		$mail->setBody('Hello ' . $employee->getName()->getFirstName() . 'This is a test mail' . $password);
		$mail->send();
	}

	/**
	 * Send notification email to team leader at the time of leave application
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
	 * @param \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext
	 * @return void
	 */
	public function sendTeamLeaderNotification(\Cloudframe\Leavemanagement\Domain\Model\Leave $leave, \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext) {
		$currentUser = $this->securityContext->getParty();
		$baseUrl = $this->standaloneView->getRequest()->getHttpRequest()->getBaseUri();
		$this->standaloneView->assign('baseUrl', $baseUrl);
		$url = $controllerContext->getUriBuilder()->reset()->setCreateAbsoluteUri(TRUE)->uriFor('approval', array('leave' => $leave), 'Leave', 'Cloudframe.Leavemanagement');
		$this->standaloneView->setFormat('html');
		$templatepath = FLOW_PATH_PACKAGES . 'Application/Cloudframe.Leavemanagement/Resources/Private/Templates/Emails/LeaveApproval.html';
		$employee = $leave->getEmployee();
		$teamLeader = $leave->getTeamLeader();
		$this->standaloneView->setTemplatePathAndFilename($templatepath);
		$this->standaloneView->assign('employee', $employee);
		$this->standaloneView->assign('url', $url);
		$emailBody = $this->standaloneView->render();
		$toEmail = $teamLeader->getPrimaryElectronicAddress()->getIdentifier();
		$toName = $teamLeader->getName()->getFirstName() . ' ' . $teamLeader->getName()->getLastName();
		$mail = new \TYPO3\SwiftMailer\Message();
		$mail->setFrom(array($currentUser->getPrimaryElectronicAddress()->getIdentifier() => $currentUser->getName()->getFullName()))->setTo(array($toEmail => $toName))->setSubject('Cloudframe Leave Approval')->setBody($emailBody, 'text/html')->send();
	}

	/**
	 * Send notification email to director at the time of leave approval
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
	 * @param \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext
	 * return void
	 */
	public function sendDirectorNotification(\Cloudframe\Leavemanagement\Domain\Model\Leave $leave, \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext) {
		$baseUrl = $this->standaloneView->getRequest()->getHttpRequest()->getBaseUri();
		$this->standaloneView->assign('baseUrl', $baseUrl);
		$url = $controllerContext->getUriBuilder()->reset()->setCreateAbsoluteUri(TRUE)->uriFor('approval', array('leave' => $leave), 'Leave', 'Cloudframe.Leavemanagement');
		$this->standaloneView->setFormat('html');
		$templatepath = FLOW_PATH_PACKAGES . 'Application/Cloudframe.Leavemanagement/Resources/Private/Templates/Emails/DirectorLeaveApproval.html';
		$employee = $leave->getEmployee();
		$teamLeader = $leave->getTeamLeader();
		$this->standaloneView->setTemplatePathAndFilename($templatepath);
		$teamLeaderApproval = $leave->getHasTeamLeaderApproved();
		$approval = ($teamLeaderApproval == 1) ? 'approved' : 'dissapproved';
		$this->standaloneView->assign('approval', $approval);
		$this->standaloneView->assign('employee', $employee);
		$this->standaloneView->assign('url', $url);
		$emailBody = $this->standaloneView->render();
		$fromEmail = $teamLeader->getPrimaryElectronicAddress()->getIdentifier();
		$fromName = $teamLeader->getName()->getFirstName() . ' ' . $teamLeader->getName()->getLastName();
		$mail = new \TYPO3\SwiftMailer\Message();
		$mail->setFrom(array($fromEmail => $fromName))->setTo(array($this->settings['notifications']['from']['email'] => $this->settings['notifications']['from']['name']))->setSubject('Cloudframe Leave Approval')->setBody($emailBody, 'text/html')->send();
	}

	/**
	 * Sends notification email to teamleader and teamleader/developer who has applied for leave
	 *
	 * @param \Cloudframe\Leavemanagement\Domain\Model\Leave $leave
	 * @param \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext
	 * @return void
	 */
	public function sendEmployeesNotification(\Cloudframe\Leavemanagement\Domain\Model\Leave $leave, \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext) {
		$baseUrl = $this->standaloneView->getRequest()->getHttpRequest()->getBaseUri();
		$this->standaloneView->assign('baseUrl', $baseUrl);
		$this->standaloneView->setFormat('html');
		$templatepath = FLOW_PATH_PACKAGES . 'Application/Cloudframe.Leavemanagement/Resources/Private/Templates/Emails/EmployeeLeaveStatus.html';
		$teamLeaderEmail = $leave->getTeamLeader()->getPrimaryElectronicAddress()->getIdentifier();
		$developerEmail = $leave->getEmployee()->getPrimaryElectronicAddress()->getIdentifier();
		$directorLeaveStatus = $leave->getHasDirectorApproved();
		$approval = ($directorLeaveStatus == 1) ? 'approved' : 'dissapproved';
		$this->standaloneView->setTemplatePathAndFilename($templatepath);
		$this->standaloneView->assign('approval', $approval);
		$emailBody = $this->standaloneView->render();
		$mail = new \TYPO3\SwiftMailer\Message();
		$mail->setFrom(array($this->settings['notifications']['from']['email'] => $this->settings['notifications']['from']['name']))
				->setTo(array($teamLeaderEmail => $developerEmail))
				->setSubject('Cloudframe Leave Approval')
				->setBody($emailBody, 'text/html')
				->send();
	}

}

?>