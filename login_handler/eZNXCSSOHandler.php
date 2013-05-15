<?php
/**
 * @author VaL <vd@nxc.no>
 * @copyright Copyright (C) 2013 NXC AS
 * @license GNU GPL v2
 * @package nxc_sso
 */

/**
 * Single Sign On handler
 *
 * @see site.ini[UserSettings].SingleSignOnHandlerArray
 */
class eZNXCSSOHandler
{
    /**
     * Checks if there is a need to do something if user is not logged in
     *
     * @return (eZUser|false)
     */
    public function handleSSOLogin()
    {
        $http = eZHTTPTool::instance();
        $token = $http->hasVariable( 'auth_token' ) ? $http->variable( 'auth_token' ) : false;

        return $token ? nxcAuthToken::handle( $token ) : false;
    }
}

?>
