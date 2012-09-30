<?php

if( php_sapi_name() !== "cli" || isset( $_SERVER["REMOTE_ADDR"] ) )
	die( "Sorry! This script is meant to be run from the command line only." );

define( "WINDOWS", substr( strtoupper( PHP_OS ), 0, 3 ) === "WIN" );

//Get the root directory on the filesystem.
$root = str_ireplace( "phar://", "", __DIR__ );
$root = str_ireplace( basename( $root ), "", $root );
$root = rtrim( $root, "/\\" );
$root = str_replace( ( WINDOWS ) ? "/" : "\\", DIRECTORY_SEPARATOR, $root );

define( "ROOT", $root );
define( "INCLUDES", __DIR__ . "/Includes" );
define( "LOGS_ROOT", "Logs" );
unset( $root );

//Check the PHP_VERSION_ID constant
if( !defined( "PHP_VERSION_ID" ) )
{
	$v = array_map( "intval", explode( ".", PHP_VERSION, 3 ) );
	$v[0] *= 10000;
	$v[1] *= 100;
	$v = array_sum( $v );

	define( "PHP_VERSION_ID", $v );
	unset( $v );
}

if( file_exists( ROOT . DIRECTORY_SEPARATOR . "config.php" ) )
	require( ROOT . DIRECTORY_SEPARATOR . "config.php" );
else
{
	trigger_error( "Config.php not found." );
	exit;
}

/*if( PHP_VERSION_ID < 50300 )
{
	trigger_error( "", E_USER_ERROR );
	exit;
}*/

require( INCLUDES . "/Functions.php" );
require( INCLUDES . "/StringHelper.php" );
require( INCLUDES . "/ConsoleColors.php" );
require( INCLUDES . "/Console.php" );
require( INCLUDES . "/ErrorHandlers.php" );

?>