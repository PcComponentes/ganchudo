<?php
/**
 * This disaster was designed by
 * @author Juan G. Rodríguez Carrión <juan.rodriguez@pccomponentes.com>
 */
declare(strict_types=1);
namespace Pccomponentes\Ganchudo\Util;

class Output
{
    private const PREFIX = '<';
    private const SUFIX = '>';

    private const OPTIONS = [
        self::PREFIX . 'r' . self::SUFIX => '0', //reset
        self::PREFIX . 'b' . self::SUFIX => '1', //BRIGHT
        self::PREFIX . 'u' . self::SUFIX => '4', //UNDERLINE
        self::PREFIX . 'f' . self::SUFIX => '5', //FLASH
        self::PREFIX . 'fg_black' . self::SUFIX => '30',
        self::PREFIX . 'fg_red' . self::SUFIX => '31',
        self::PREFIX . 'fg_green' . self::SUFIX => '32',
        self::PREFIX . 'fg_yellow' . self::SUFIX => '33',
        self::PREFIX . 'fg_blue' . self::SUFIX => '34',
        self::PREFIX . 'fg_purple' . self::SUFIX => '35',
        self::PREFIX . 'fg_cyan' . self::SUFIX => '36',
        self::PREFIX . 'fg_white' . self::SUFIX => '37',
        self::PREFIX . 'bg_black' . self::SUFIX => '40',
        self::PREFIX . 'bg_red' . self::SUFIX => '41',
        self::PREFIX . 'bg_green' . self::SUFIX => '42',
        self::PREFIX . 'bg_yellow' . self::SUFIX => '43',
        self::PREFIX . 'bg_blue' . self::SUFIX => '44',
        self::PREFIX . 'bg_purple' . self::SUFIX => '45',
        self::PREFIX . 'bg_cyan' . self::SUFIX => '46',
        self::PREFIX . 'bg_white' . self::SUFIX => '47'
    ];


    private static function convert(string $option): string
    {
        return "\e[{$option}m";
    }

    public static function parse(string $str): string
    {
        return \str_replace(
            \array_keys(self::OPTIONS),
            \array_map(
                'self::convert',
                \array_values(self::OPTIONS)
            ),
            $str
        );
    }
}
