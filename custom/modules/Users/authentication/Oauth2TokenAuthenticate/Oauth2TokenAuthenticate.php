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
        //$this->redirectToLogin($GLOBALS['app']); 

        $GLOBALS['log']->debug("== req oauth2Name == " . $_REQUEST['oauth2Name']);
        if (isset($_REQUEST['oauth2Name']) && !empty($_REQUEST['oauth2Name'])) {
            $_SESSION['oauth2Name'] = $_REQUEST['oauth2Name'];
            
            $this->redirectToLogin($GLOBALS['app']);
        }

/*        
        $GLOBALS['log']->debug("== session oauth2Name == " . $_SESSION['oauth2Name']);

        foreach (apache_request_headers() as $name => $value) {
            $GLOBALS['log']->debug("header apache $name: $value");

            //$value = "eyJhbGciOiJSUzI1NiIsImtpZCI6IklYZXNMejVOLTBQWkdzTXRUaUJsLVJkRGZENCJ9.eyJzdWIiOiJUODE3MzMyIiwiYXVkIjoiNzU5MWU2YmItZDJiZS00YzA5LTk0NTUtNDAxOGQ3ODdhZjkyIiwianRpIjoibnlpSmZkQWVRU3J5TDZlY2E4bzMxZyIsImlzcyI6Imh0dHBzOi8vdGVhbXNzby1pdHMwNC50ZWx1cy5jb20iLCJpYXQiOjE2NTE2MTgxNTAsImV4cCI6MTY1MTYxODQ1MCwiYWNyIjoidXJuOm9hc2lzOm5hbWVzOnRjOlNBTUw6Mi4wOmFjOmNsYXNzZXM6dW5zcGVjaWZpZWQiLCJhdXRoX3RpbWUiOjE2NTE2MTgxNDksImdpdmVuX25hbWUiOiJDaHJpc3RpYW4iLCJmYW1pbHlfbmFtZSI6IkxldmVzcXVlIiwiZW1haWwiOiJjaHJpc3RpYW4ubGV2ZXNxdWVAdGVsdXMuY29tIiwidXNlckFzc2V0cyI6W10sIm5vbmNlIjoieE5lSHN4N04zRlVJX20ybEZ4eEZCRHV1In0.ZLwLA_Ej2d20Z_RJoBQ_hU4HnY-F9LC22yNu-TcjjfY8gDUSS8qmdr1tSVO8Zt60-weD5efMg8btGv_D57IOBSi6VSZeUbneiU_jSVbFiRUGsfQUX1ZfR6pDOijqV4Oq4CCgV2yMLj6RuKWJiq4ZPi4ud6APOhLHb6iaf2w7HQESeJzC1BU2M19MRWGHRrIyoSnFBFPY8-t0O-3aNCZU8oQg041YRXO96Yo0VsTZfBJPXz7PyXn8gEJE7KkKZzMVsYH2pYdqd5cb_tqaRW0Ect1y0Y6DJJJMM9hwytmmN4DRRFuLKDptKkd0GN5J70D-7LYQn7fzCdG4CE11D_-A1A";

            if ($name == "x-id-token") {            
                $GLOBALS['log']->debug("header apache x-id-token found !");

                $xidToken = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $value)[1]))));

                $_SESSION['oauth2Name'] = $xidToken->sub;

                $GLOBALS['log']->debug("User in x-id-token: " . $_SESSION['oauth2Name']);

                $this->redirectToLogin($GLOBALS['app']);
                
                //parent::redirectToLogin($app);

                break;
            }
        }
*/
    }

    public function redirectToLogin(SugarApplication $app)
    {
//        $_SESSION['oauth2Name'] = "T817332";

        if (isset($_SESSION['oauth2Name']) && !empty($_SESSION['oauth2Name'])) {
            if ($this->userAuthenticate->loadUserOnLogin($_SESSION['oauth2Name'], null)) {
                global $authController;
                $authController->login($_SESSION['oauth2Name'], null);
            }
            SugarApplication::redirect('index.php?module=Users&action=LoggedOut');
        } else {
            return false;
        }
    }

    public function logout()
    {
        parent::logout();
    }

}
