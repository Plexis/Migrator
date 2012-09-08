<?php

if( php_sapi_name() !== "cli" || isset( $_SERVER["REMOTE_ADDR"] ) )
	die( "Sorry! This script is meant to be run from the command line only." );

require( __DIR__ . "/Includes/Constants.php" );
require( __DIR__ . "/Includes/Functions.php" );
require( __DIR__ . "/Includes/StringHelper.php" );
require( __DIR__ . "/Includes/ConsoleColors.php" );
require( __DIR__ . "/Includes/Console.php" );
require( __DIR__ . "/Includes/ErrorHandlers.php" );

if( PHP_VERSION_ID < 50300 )
{
	Console::SetForegroundColor( ForegroundColors::RED );
	Console::WriteLine( "PHP 5.3.0 or greater is required to run this script." );
	Console::ResetColor();
	exit;
}

$configPath = path( ROOT, "migrator.phar.config.php" );
$foundConfig = false;

if( file_exists( $configPath ) )
{
	require( $configPath );
	$foundConfig = true;
}
else
{
	$configPath = path( ROOT, "migrator.phar.config.ini" );

	if( file_exists( $configPath ) )
	{
		$config = parse_ini_file( $configPath, true );
		$foundConfig = true;
	}
}

if( !isset( $config ) )
{
	$config = array(
		"Connection" => array(
			"Hostname" => "localhost",
			"Username" => "root",
			"Password" => "",
			"Database" => "",
			"Port"     => 3306
		)
	);
}

if( !$foundConfig )
{
	Console::WriteLine( "Please enter your connection information." );

	foreach( $config["Connection"] as $key => $value )
	{
		Console::Write( "  -$key ($value): " );
		$input = Console::ReadLine();
		$config["Connection"][$key] = StringHelper::IsNullEmptyOrWhitespace( $input ) ? $value : $input;
	}
}

error_reporting( 0 );
$Source = new mysqli( $config["Connection"]["Hostname"], $config["Connection"]["Username"], $config["Connection"]["Password"], $config["Connection"]["Database"], $config["Connection"]["Port"] );
error_reporting( E_ALL );

if( $Source->connect_errno )
{
	Console::SetForegroundColor( ForegroundColors::RED );
	Console::WriteLine( PHP_EOL . "Could not connect to the database server, please check your connection settings and try again." );
	Console::WriteLine( sprintf( "MySQL Error (%u): %s", $Source->connect_errno, $Source->connect_error ) );
	exit;
}



?>