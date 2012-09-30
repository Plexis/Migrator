<?php

if( php_sapi_name() != "cli" || isset( $_SERVER["REMOTE_ADDR"] ) )
	die( "This build script can only be run from the command line." );

if( !extension_loaded( "Phar" ) || !in_array( "phar", stream_get_wrappers() ) || !class_exists( "Phar" ) )
	fwrite( STDERR, "The Phar extension must be enabled in order to build this application." );

define( "DS", DIRECTORY_SEPARATOR ); //Because DS is shorter.
define( "SRC", __DIR__ . DS . "src" );
define( "BUILD", __DIR__ . DS . "build" );

print( "\nCleaning build directory...\n" );
$itr = null;

if( !is_dir( BUILD ) )
	mkdir( BUILD );
else
{
	$itr = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( BUILD ) );

	foreach( $itr as $entry )
	{
		if( $entry->isDir() )
			rmdir( $entry->__toString() );
		else
			unlink( $entry->__toString() );
	}
}

print( "Building phar...\n" );

$phar = new Phar(
	BUILD . DS . "migrator.phar",
	FilesystemIterator::CURRENT_AS_FILEINFO | FileSystemIterator::KEY_AS_FILENAME,
	"migrator.phar"
);

$Files = array(
	"App.php",
	"Bootstrap.php",
	"Includes" . DS . "Console.php",
	"Includes" . DS . "ConsoleColors.php",
	"Includes" . DS . "ErrorHandlers.php",
	"Includes" . DS . "Functions.php",
	"Includes" . DS . "StringHelper.php",
	"Includes" . DS . "Converters" . DS . "MWEnhancedV3.php",
);

foreach( $Files as $File )
{
	$FullPath = SRC . DS . $File;
	$phar[$File] = file_get_contents( $FullPath );

	printf( "  -Added %s\n", $File );
}

$phar->setStub( $phar->createDefaultStub( "App.php" ) );

copy(
	SRC . DS . "config.php",
	BUILD . DS . "config.php"
);

unset( $itr, $BuildInfo, $phar );
print( "Done building phar.\n" );

?>