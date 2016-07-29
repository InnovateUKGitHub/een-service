<?php

namespace Console\Helper;

class Helper
{
    const VALID_TYPE = [
        'event',
        'opportunity',
        'all',
    ];

    const VALID_MERLIN_TYPE = [
        'bo',
        'all',
    ];

    /**
     * @param string $type
     *
     * @return bool
     */
    public static function checkValidType($type)
    {
        if (in_array($type, self::VALID_TYPE, true) === false) {
            return false;
        }

        return true;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public static function checkValidProfileType($type)
    {
        if (in_array($type, self::VALID_MERLIN_TYPE, true) === false) {
            return false;
        }

        return true;
    }
}