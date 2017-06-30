<?php

namespace Cloudframe\Leavemanagement\ViewHelpers;

use TYPO3\Flow\Annotations as Flow;

/**
 * Declares new variable which are aliases of other variables.
 * Takes a "map" -Parameter which is an associative array which defines the shorthand mapping.
 *
 *
 * @api
 */
class LoginViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @Flow\Inject
	 *
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * Show the name of current login user
	 *
	 * @return string $user
	 */
	public function render() {
		$user = $this->securityContext->getParty()->getName();
		return $user;
	}

}

?>