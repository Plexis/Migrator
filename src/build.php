<?php

if( php_sapi_name() != "cli" || isset( $_SERVER["REMOTE_ADDR"] ) )
	die( "This build script can only be run from the command line." );

print( "\nCleaning build directory...\n" );

if( !@dir( "build" ) )
	mkdir( "build" );
else
	$itr = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( "build" ) );

foreach( $itr as $entry )
{
	if( $entry->isDir() )
		rmdir( $entry->__toString() );
	else
		unlink( $entry->__toString() );
}

print( "Building phar...\n" );

$itr = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( __DIR__ ) );
$phar = new Phar( "build/migrator.phar", FilesystemIterator::CURRENT_AS_FILEINFO | FileSystemIterator::KEY_AS_FILENAME, "migrator.phar" );

foreach( $itr as $entry )
{
	$base = basename( $entry->__toString() );

	if( $base == "build.php" || $base == "migrator.phar.config.ini" || $entry->isDir() )
		continue;

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

$phar->setStub( $phar->createDefaultStub( "entry.php" ) );

copy( "src/migrator.phar.config.ini", "build/migrator.phar.config.ini" );

print( "Done building phar.\n" );

?>