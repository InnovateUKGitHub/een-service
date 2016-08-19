<?php

namespace Console\Helper;

class Helper
{
    const VALID_TYPE = [
        'event',
        'opportunity',
        'all',
    ];

    /**
     * @param string $type
     *
     * @return bool
     */
    public static function checkValidType($type)
    {
        return in_array($type, self::VALID_TYPE, true);
    }

    /**
     * @param int $month
     *
     * @return bool
     */
    public static function checkValidMonth($month)
    {
        return $month > 0 && $month < 13;
    }
}