<?php

set_exception_handler( "ExceptionHandler" );
set_error_handler( "ErrorHandler" );

function ExceptionHandler( $e )
{
	global $Lang;

	$exString = "";
	$HasInnerExceptions = $e->getPrevious() != null;
	$Primario = true;
	$First = null;

	while( $e != null )
	{
		$First = $e;
		$type = get_class( $e );
		$trace = $e->getTrace();

		$exString .= str_repeat( "=", strlen( $type ) ) . PHP_EOL;
		$exString .= $type . PHP_EOL;
		$exString .= str_repeat( "=", strlen( $type ) ) . PHP_EOL;
		$exString .= "Message: " . $e->getMessage() . PHP_EOL;
		$exString .= "Code: " . $e->getCode() . PHP_EOL;
		$exString .= PHP_EOL . "------------------------------------------" . PHP_EOL;
		$exString .= "- Debug Trace" . PHP_EOL;
		$exString .= "------------------------------------------" . PHP_EOL;

		if( sizeof( $trace ) == 0 )
			$exString .= "(Not Available)";
		else
		{
			$exString .= "File: " . $trace["file"] . PHP_EOL;
			$exString .= "Line: " . $trace["line"] . PHP_EOL;
			$exString .= "Function: " . $trace["function"] . PHP_EOL;
			$exString .= "Arguments: ";

			if( sizeof( $trace["args"] ) == 0 )
				$exString .= "N/A" . PHP_EOL;
			else
			{
				$exString .= PHP_EOL;

				foreach( $trace["args"] as $arg )
					$exString .= "  -" . var_export( $arg, true ) . PHP_EOL;
			}

			$exString .= "------------------------------------------" . PHP_EOL;
		}

		if( !$HasInnerExceptions )
			break;
		else
		{
			$e = $e->getPrevious();

			if( $Primario )
			{
				$exString .= PHP_EOL . "------------------------------------------" . PHP_EOL;
				$exString .= "- Stack Trace (Inner Exceptions)" . PHP_EOL;
				$exString .= "------------------------------------------" . PHP_EOL . PHP_EOL;
			}
		}
	}

	$FileName = "Exception" . date( "d.m.Y.H.i.s") . ".log";
	$FullPath = path( ROOT, LOGS_ROOT, $FileName );
	$Folder   = path( ROOT, LOGS_ROOT );

	if( !is_dir( $Folder ) )
		mkdir( $Folder );

	file_put_contents( $FullPath, $exString );

	unset( $exString );

	Console::SetForegroundColor( ForegroundColors::RED );
	Console::WriteLine( sprintf( "%s ( %u ): %s", get_class( $First ), $First->getCode(), $First->getMessage() ) );
	Console::ResetColor();
	Console::WriteLine( "Logged to " . $FullPath );
}

function ErrorHandler( $ncode, $message, $file, $line )
{
	global $Lang;

	$ErrName;
	$Severity;
	$File;
	$Color;
	$Output = "";

	//Some of these switches are superfluous, I know. But better safe than sorry.
	switch( $ncode )
	{
		case E_USER_ERROR:
			$ErrName = "E_USER_ERROR";
			$Severity = "Fatal Error";
			$File = "Errors.log";
			$Color = ForegroundColors::RED;
			break;
		case E_ERROR:
			$ErrName = "E_ERROR";
			$Severity = "Fatal Error";
			$File = "Errors.log";
			$Color = ForegroundColors::RED;
			break;
		case E_USER_WARNING:
			$ErrName = "E_USER_WARNING";
			$Severity = "Warning";
			$File = "Warnings.log";
			$Color = ForegroundColors::YELLOW;
			break;
		case E_WARNING:
			$ErrName = "E_WARNING";
			$Severity = "Warning";
			$File = "Warnings.log";
			$Color = ForegroundColors::YELLOW;
			break;
		case E_NOTICE:
			$ErrName = "E_NOTICE";
			$Severity = "Notice";
			$File = "Notices.log";
			$Color = ForegroundColors::CYAN;
			break;
		case E_USER_NOTICE:
			$ErrName = "E_USER_NOTICE";
			$Severity = "Notice";
			$File = "Notices.log";
			$Color = ForegroundColors::CYAN;
			break;
		case E_DEPRECATED:
			$ErrName = "E_DEPRECATED";
			$Severity = "Deprecation Warning";
			$File = "Warnings.log";;
			$Color = ForegroundColors::YELLOW;
			break;
		case E_USER_DEPRECATED:
			$ErrName = "E_USER_DEPRECATION";
			$Severity = "Deprecation Warning";
			$Color = ForegroundColors::YELLOW;
			$File = "Warnings.log";;
			break;
		case E_PARSE:
			$ErrName = "E_PARSE";
			$Severity = "Parse Error";
			$File = "Errors.log";
			$Color = ForegroundColors::RED;
			break;
		case E_CORE_ERROR:
			$ErrName = "E_CORE_ERROR";
			$Severity = "Core Error";
			$File = "Errors.log";
			$Color = ForegroundColors::RED;
			break;
		case E_COMPILE_ERROR:
			$ErrName = "E_COMPILE_ERROR";
			$Severity = "Compile Error";
			$File = "Errors.log";
			$Color = ForegroundColors::RED;
			break;
		case E_CORE_WARNING:
			$ErrName = "E_CORE_WARNING";
			$Severity = "Core Warning";
			$File = "Warnings.log";
			$Color = ForegroundColors::YELLOW;
			break;
		case E_COMPILE_WARNING:
			$ErrName = "E_COMPILE_WARNING";
			$Severity = "Compile Warning";
			$File = "Warnings.log";
			$Color = ForegroundColors::YELLOW;
			break;
		default: //We should never end up here. If we do this function isn't being called courtesy of set_error_handler()
			return;
	}

	$trace = array_chunk( debug_backtrace(), 6, true ); //Trace back a maximum of 5 levels (6 because the first element will be useless to us).
	array_shift( $trace );

	$Output .= "Type: {$Severity} ({$ErrName})" . PHP_EOL;
	$Output .= "Message: " . $message . PHP_EOL;
	$Output .= "File: " . $file . PHP_EOL;
	$Output .= "Line: " . $line . PHP_EOL;

	if( sizeof( $trace ) > 0 )
	{
		$Output .= "----------------------------------------" . PHP_EOL;
		$Output .= "- Backtrace (" . sizeof( $trace ) . ")" . PHP_EOL;
		$Output .= "----------------------------------------" . PHP_EOL;

		foreach( $trace as $level )
		{
			if( strtolower( $level["function"] ) == "trigger_error" )
				$level["args"][1] = $ErrName;

			$Output .= "File: " . $level["file"] . PHP_EOL;
			$Output .= "Line: " . $level["line"] . PHP_EOL;
			$Output .= "Function: " . ( isset( $level["class"] ) ? $level["class"] . $level["type"] : "" ) . $level["function"] . PHP_EOL;
			$Output .= "Arguments (" . sizeof( $level["args"] ) . ")" . PHP_EOL . "{";

			foreach( $level["args"] as $arg )
			{
				$dump = trim( var_export( $arg, true ) );
				$Output .= "\t" . $dump . PHP_EOL;
			}

			$Output .= "}" . PHP_EOL;
		}
	}

	$Output .= "----------------------------------------" . PHP_EOL;

	$FullPath = path( ROOT, LOGS_ROOT, $File );
	$Folder   = path( ROOT, LOGS_ROOT );

	if( !is_dir( $Folder ) )
		mkdir( $Folder );

	if( file_exists( $FullPath ) )
		file_put_contents( $FullPath, $Output, FILE_APPEND );
	else
		file_put_contents( $FullPath, $Output );

	unset( $Output );

	Console::SetForegroundColor( $Color );
	Console::WriteLine( "PHP triggered {$ErrName}" . PHP_EOL . "Message: {$message}" );
	Console::ResetColor();
	Console::WriteLine( "Logged to " . $FullPath );
}

?>