<?php
/**
 * @author VaL <vd@nxc.no>
 * @copyright Copyright (C) 2013 NXC AS.
 * @package nxc_sso
 */

/**
 * Module to process logging in using SSO.
 * It creates one time token and redirects back to log user in on another domain
 */

$http = eZHTTPTool::instance();
$Module = $Params['Module'];

$redirectURL = $http->hasVariable( 'redirect_url' ) ? $http->variable( 'redirect_url' ) : ( isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : false );
// Redirect url must exist to know where it should be redirected
if ( !$redirectURL )
{
    return $Module->redirectTo( '/user/login' );
}

$user = eZUser::instance();
if ( !$user->attribute( 'is_logged_in' ) )
{
    $r = 'http://' . eZSys::hostname() . '/sso/login?redirect_uri=' . $redirectURL;
    return $Module->redirectTo( '/user/login?redirect_url=' . $r );
}

$token = nxcAuthToken::get( $user )->create();
$p = parse_url( $redirectURL );
$q = ( isset( $p['query'] ) and $p['query'] ) ? $p['query'] . '&auth_token=' . $token : 'auth_token=' . $token;
$e = explode( '?', $redirectURL );
$url = $e[0] . '?' . $q;

header( "Location: $url" );
eZExecution::cleanExit();

?>
