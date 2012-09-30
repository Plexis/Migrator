<?php

require( __DIR__ . "/Bootstrap.php" );

Console::WriteLine( error_reporting() );
Console::WriteLine( "Please verify your information." );

foreach( $config["Connection"] as $key => $value )
{
	Console::Write( "  -{$key} ({$value}): " );
	$input = Console::ReadLine();
	$config["Connection"][$key] = StringHelper::IsNullEmptyOrWhitespace( $input ) ? $value : $input;
}

Console::WriteLine( "Connecting..." );

$db = new mysqli(
	$config["Connection"]["Hostname"],
	$config["Connection"]["Username"],
	$config["Connection"]["Password"],
	$config["Connection"]["Database"],
	$config["Connection"]["Port"]
);

if( $db->connect_errno )
	exit;

$File = rtrim( $config["App"]["DataFormat"], ".php" );
$File = __DIR__ . "/Converters/" . $File . ".php";

require( $File );

?>