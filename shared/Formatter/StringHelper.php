<?php
namespace Shared\Formatter;

class StringHelper
{
    /**
     * @param string $word
     * @return string
     */
    public static function underlineToCamel(string $value)
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        return lcfirst(str_replace(' ', '', $value));
    }

    /**
     * @param string $value
     */
    public static function underlineToStudly($value)
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        return str_replace(' ', '', $value);
    }

    /**
     * @param $value
     * @param string $delimiter
     * @return false|string|string[]|null
     */
    public static function humpToUnderline($value, $delimiter = '_')
    {
        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', $value);
            $value = mb_strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
        }
        return $value;
    }
}