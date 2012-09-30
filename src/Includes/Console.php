<?php

/* A partial clone of C#'s Console class, because that's what I'm used to. */

class Console
{
	private static $ColorFormat = null; //The format string (to be used with sprintf) for colorized console output (Bash only).
	private static $BackgroundColor = null;
	private static $ForegroundColor = null;

	public static function Write( $message )
	{
		if( self::$ColorFormat != null && !WINDOWS && $config["App"]["ConsoleColors"] )
			$message = sprintf( self::$ColorFormat, $message );

		fwrite( STDOUT, $message );
	}

	public static function WriteLine( $message )
	{
		self::Write( $message . PHP_EOL );
	}

	public static function ReadLine()
	{
		return rtrim( fgets( STDIN ) );
	}

	public static function SetForegroundColor( $color )
	{
		if( !ForegroundColors::IsValid( $color ) )
			return;
		elseif( $color == null )
		{
			self::$ForegroundColor = null;
			return;
		}

		self::$ForegroundColor = $color;
		self::BuildColorFormatString();
	}

	public static function GetForegroundColor()
	{
		return self::$ForegroundColor;
	}

	public static function SetBackgroundColor( $color )
	{
		if( !BackgroundColors::IsValid( $color ) )
			return;
		elseif( $color == null )
		{
			self::$BackgroundColor = null;
			return;
		}

		self::$BackgroundColor = $color;
		self::BuildColorFormatString();
	}

	public static function GetBackgroundColor()
	{
		return self::$BackgroundColor;
	}

	public static function ResetColor()
	{
		self::$BackgroundColor = null;
		self::$ForegroundColor = null;
		self::$ColorFormat     = null;
	}

	protected static function BuildColorFormatString()
	{
		if( self::$BackgroundColor == null && self::$ForegroundColor == null )
		{
			self::$ColorFormat = null;
			return;
		}

		$Format;

		if( self::$ForegroundColor != null )
			$Format = "\033[" . self::$ForegroundColor . "m";

		if( self::$BackgroundColor != null )
			$Format .= "\033[" . self::$BackgroundColor . "m";

		$Format .= "%s\033[0m";
		self::$ColorFormat = $Format;
	}
}

?>