<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once __DIR__ . '/../../../../../modules/Users/authentication/SugarAuthenticate/SugarAuthenticate.php';
require_once 'include/utils.php';

/**
 * Class Oauth2Authenticate for primavera auth
 */
class Oauth2TokenAuthenticate extends SugarAuthenticate
{
    public $userAuthenticateClass = 'Oauth2TokenAuthenticateUser';
    public $authenticationDir = 'Oauth2TokenAuthenticate';

    public function pre_login()
    {
        $GLOBALS['log']->debug('session oauth2Name: ' .  $_SESSION['oauth2Name']);

        if (isset($_REQUEST['oauth2Name']) && !empty($_REQUEST['oauth2Name'])) {
            $_SESSION['oauth2Name'] = $_REQUEST['oauth2Name'];
            
            $this->redirectToLogin($GLOBALS['app']);
        }
    }

    public function redirectToLogin(SugarApplication $app)
    {
        if (isset($_SESSION['oauth2Name']) && !empty($_SESSION['oauth2Name'])) {
            if ($this->userAuthenticate->loadUserOnLogin($_SESSION['oauth2Name'], null)) {
                global $authController;
                $GLOBALS['log']->debug('redirectToLogin login');
                $authController->login($_SESSION['oauth2Name'], null);
            }
            $GLOBALS['log']->debug('redirectToLogin logged out');
            SugarApplication::redirect('index.php?module=Users&action=UserNotFound');
        } else {
            return false;
        }
    }

    public function logout()
    {
        parent::logout();
    }

}
