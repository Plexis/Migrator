<?php

//Are we running on Windows?
define( "WINDOWS", substr( strtoupper( PHP_OS ), 0, 3 ) === "WIN" );

//Define the ROOT const.
$root = str_ireplace( "phar://", "", __DIR__ );
$root = str_ireplace( basename( $root ), "", $root );
$root = rtrim( $root, "/\\" );
$root = str_replace( ( WINDOWS ) ? "/" : "\\", DIRECTORY_SEPARATOR, $root );

define( "ROOT", $root );
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

define( "EXCEPTION_ROOT", "Exceptions" );

?>