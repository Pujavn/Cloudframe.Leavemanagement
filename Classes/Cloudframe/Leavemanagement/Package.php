<?php

namespace Cloudframe\Leavemanagement;

use TYPO3\Flow\Package as BasePackage;
use TYPO3\Flow\Annotations as Flow;

/**
 * Package base class of the Cloudframe.LeaveManagement package.
 *
 * @Flow\Scope("singleton")
 */
class Package extends BasePackage {

	/**
	 * Invokes custom PHP code directly after the package manage has been initialized
	 *
	 * @param \TYPO3\Flow\Core\Bootstrap $bootstrap The current bootstrap
	 * @return void
	 */
	public function boot(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
		$dispatcher = $bootstrap->getSignalSlotDispatcher();
		$dispatcher->connect('Cloudframe\Leavemanagement\Controller\EmployeeController', 'employeeCreated', 'Cloudframe\Leavemanagement\Service\Notification', 'sendEmployeeRegistrationNotification');
		$dispatcher->connect('Cloudframe\Leavemanagement\Controller\LeaveController', 'leaveCreated', 'Cloudframe\Leavemanagement\Service\Notification', 'sendTeamLeaderNotification');
		$dispatcher->connect('Cloudframe\Leavemanagement\Controller\LeaveController', 'teamLeaderLeaveApproval', 'Cloudframe\Leavemanagement\Service\Notification', 'sendDirectorNotification');
		$dispatcher->connect('Cloudframe\Leavemanagement\Controller\LeaveController', 'directorLeaveApproval', 'Cloudframe\Leavemanagement\Service\Notification', 'sendEmployeesNotification');
	}

}

?>