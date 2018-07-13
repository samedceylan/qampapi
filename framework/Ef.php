<?php

/**
 *
 * @author Eight
 * @copyright 2017 EightFramework 2
 */

namespace EF2;

use EF2\Components\i18n;

class Ef
{

	private static $app;

    private static $t;

	public static function app()
	{

		if (self::$app === null) {
			self::$app = new App();
		}

		return self::$app;
	}

	public static function t($str,$arr=array())
    {
        return I18n::t($str,$arr);
    }


}