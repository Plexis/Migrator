<?php

class StringHelper
{
	public static function IsNullOrEmpty( $Input )
	{
		if( $Input == null || !is_string( $Input ) || $Input == "" )
			return true;

		return false;
	}

	public static function IsNullEmptyOrWhitespace( $Input )
	{
		if( $Input == null || !is_string( $Input ) || $Input == "" || trim( $Input ) == "" )
			return true;

		return false;
	}
}

?>