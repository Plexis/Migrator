<?php

function path()
{
	// Determine if we are one windows, And get our path parts
	$args = func_get_args();
	$parts = array();

	// Trim our paths to remvove spaces and new lines
	foreach( $args as $part )
		$parts[] = (is_array( $part )) ? trim( implode(DIRECTORY_SEPARATOR, $part) ) : trim($part);

	// Get our cleaned path into a variable with the correct directory seperator
	$newPath = implode( DIRECTORY_SEPARATOR, $parts );

	// Do some checking for illegal path chars
	if( WINDOWS )
	{
		$IllegalChars = "\\/:?*\"<>|\r\n";
		$Pattern = "~[" . $IllegalChars . "]+~";
		$tempPath = preg_replace( "~^[A-Z]{1}:~", "", $newPath );
		$tempPath = trim( $tempPath, DIRECTORY_SEPARATOR );
		$tempPath = explode( DIRECTORY_SEPARATOR, $tempPath );

		foreach( $tempPath as $part )
		{
			if( preg_match( $Pattern, $part ) )
				return null;
		}
	}

	return $newPath;
}

?>