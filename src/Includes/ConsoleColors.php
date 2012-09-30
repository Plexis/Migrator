<?php

class ForegroundColors
{
	const BLACK        = "0;30";
	const DARK_GRAY    = "1;30";
	const BLUE         = "0;34";
	const LIGHT_BLUE   = "1;34";
	const GREEN        = "0;32";
	const LIGHT_GREEN  = "1;32";
	const CYAN         = "0;36";
	const LIGHT_CYAN   = "1;36";
	const RED          = "0;31";
	const LIGHT_RED    = "1;31";
	const PURPLE       = "0;35";
	const LIGHT_PURPLE = "1;35";
	const BROWN        = "0;33";
	const YELLOW       = "1;33";
	const LIGHT_GRAY   = "0;37";
	const WHITE        = "1;37";

	private static $Colors = array(
		self::BLACK,
		self::BLUE,
		self::BROWN,
		self::CYAN,
		self::DARK_GRAY,
		self::GREEN,
		self::LIGHT_BLUE,
		self::LIGHT_CYAN,
		self::LIGHT_GRAY,
		self::LIGHT_GREEN,
		self::LIGHT_PURPLE,
		self::LIGHT_RED,
		self::PURPLE,
		self::RED,
		self::WHITE,
		self::YELLOW
	);

	public static function IsValid( $ColorString )
	{
		return in_array( $ColorString, self::$Colors );
	}
}

class BackgroundColors
{
	const BLACK      = "40";
	const RED        = "41";
	const GREEN      = "42";
	const YELLOW     = "43";
	const BLUE       = "44";
	const MAGENTA    = "45";
	const CYAN       = "46";
	const LIGHT_GRAY = "47";

	private static $Colors = array(
		self::BLACK,
		self::BLUE,
		self::CYAN,
		self::GREEN,
		self::LIGHT_GRAY,
		self::MAGENTA,
		self::RED,
		self::YELLOW
	);

	public static function IsValid( $ColorString )
	{
		return in_array( $ColorString, self::$Colors );
	}
}

?>