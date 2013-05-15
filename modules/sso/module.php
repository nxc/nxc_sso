<?php
/**
 * @author VaL <vd@nxc.no>
 * @copyright Copyright (C) 2013 NXC AS.
 * @package nxc_sso
 */

$Module = array( 'name' => 'sso',
                 'variable_params' => true );

$ViewList = array();
$ViewList['login'] = array(
    'script' => 'login.php',
    'params' => array(),
    'single_post_actions' => array(),
    'unordered_params' => array(),
);

$FunctionList = array();

?>
