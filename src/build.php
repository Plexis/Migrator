<?php

if( php_sapi_name() != "cli" || isset( $_SERVER["REMOTE_ADDR"] ) )
	die( "This build script can only be run from the command line." );

if( !defined( "PHP_VERSION_ID" ) )
{
	$nums = array_map( 'intval', explode( '.', PHP_VERSION, 3 ) );
	$nums[0] *= 10000;
	$nums[1] *= 100;

	define( "PHP_VERSION_ID", array_sum( $nums ) );
	unset( $nums );
}

if( PHP_VERSION_ID < 50300 )
	die( "PHP 5.3.0 or greater required to run this build script." );

define( "ROOT", __DIR__ );
define( "LOGS_ROOT", "Logs" );
define( "WINDOWS", strtoupper( substr( PHP_OS, 0, 3 ) ) === "WIN" );
require( __DIR__ . DIRECTORY_SEPARATOR . "Includes" . DIRECTORY_SEPARATOR . "Functions.php" );
require( __DIR__ . DIRECTORY_SEPARATOR . "Includes" . DIRECTORY_SEPARATOR . "ErrorHandlers.php" );

print( "\nCleaning build directory...\n" );

if( !@dir( "build" ) )
	mkdir( "build" );
else
{
	$itr = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( "build" ) );

	foreach( $itr as $entry )
	{
		if( $entry->isDir() )
			rmdir( $entry->__toString() );
		else
			unlink( $entry->__toString() );
	}
}

print( "Building phar...\n" );
$BuildInfo = parse_ini_file( "src/build.ini" );

$itr = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( __DIR__ ) );
$phar = new Phar( "build/migrator.phar", FilesystemIterator::CURRENT_AS_FILEINFO | FileSystemIterator::KEY_AS_FILENAME, "migrator.phar" );

foreach( $itr as $entry )
{
	$base = basename( $entry->__toString() );
	$continue = true;

	if( $entry->isDir() )
		continue;
	else
	{
		foreach( $BuildInfo["Ignore"] as $Pattern )
		{
			if( $continue == false )
				break;

			if( fnmatch( $Pattern, $base ) )
				$continue = false;
		}

		if( $continue == false )
			continue; //Ironic eh?
	}

	$file_long  = $entry->__toString();
	$file_short = str_replace(
		DIRECTORY_SEPARATOR, "/",
		str_replace(
			__DIR__ . DIRECTORY_SEPARATOR, "", $file_long
		)
	);

	$phar[$file_short] = file_get_contents( $file_long );
	printf( "  -Added %s\n", $file_short );
}

$phar->setStub( $phar->createDefaultStub( $BuildInfo["DefaultStub"] ) );

foreach( $itr as $entry )
{
	$base = basename( $entry->__toString() );

	foreach( $BuildInfo["Copy"] as $Pattern )
	{
		if( fnmatch( $Pattern, $base ) )
			copy( path( ROOT, $base ) , path( ROOT, $base ) );
	}
}

unset( $itr, $BuildInfo, $phar );
print( "Done building phar.\n" );

?>