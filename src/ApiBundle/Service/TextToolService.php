<?php

namespace ApiBundle\Service;

/**
 * Class TextToolService
 * @package ApiBundle\Service
 */
class TextToolService
{
    private static $characterTranslations = array(
        '/ą/' => 'a',
        '/Ą/' => 'A',
        '/ć/' => 'c',
        '/Ć/' => 'C',
        '/ę/' => 'e',
        '/Ę/' => 'E',
        '/ł/' => 'l',
        '/Ł/' => 'L',
        '/ń/' => 'n',
        '/Ń/' => 'N',
        '/ś/' => 's',
        '/Ś/' => 'S',
        '/ò/' => 'o',
        '/ó/' => 'o',
        '/Ó/' => 'O',
        '/ź/' => 'z',
        '/Ź/' => 'Z',
        '/ż/' => 'z',
        '/Ż/' => 'Z',
    );

    public static function stripForSeo($text)
    {
        $text = self::stripLocalCharacter($text);
        $text = self::stripNonWordCharacter($text);
        $text = strtolower($text);
        return $text;
    }

    private static function stripLocalCharacter($text)
    {
        $text = preg_replace(array_keys(self::$characterTranslations), array_values(self::$characterTranslations), $text);
        return $text;
    }

    private static function stripNonWordCharacter($text)
    {
        $text = preg_replace('/\W|_/', '-', $text);
        $text = preg_replace('/-+/', '-', $text);
        return $text;
    }
}