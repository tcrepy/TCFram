<?php
namespace TCFram;

Class Tools
{
    /**
     * permet de faire une recherche dans un array multi dimensionnel
     * FROM GIST https://gist.github.com/Billy-/bc6865066981e80e097f
     *
     * @param $needle
     * @param $haystack
     * @param bool $strict
     * @return bool
     */
    public static function in_array_r($needle, $haystack, $strict = false)
    {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && self::in_array_r($needle, $item, $strict))) {
                return true;
            }
        }
        return false;
    }
}