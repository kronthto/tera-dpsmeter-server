<?php

namespace App\Service;

class ViewHelpers
{
    /**
     * @param string $path
     * @param string $name
     *
     * @return null|string
     */
    public static function generateIco($path, $name)
    {
        $name = e($name);
        if ($path) {
            return '<img class="avatar" src="'.$path.'" alt="'.$name.'" title="'.$name.'"> ';
        }

        return null;
    }

    /**
     * @param string $description
     *
     * @return string
     */
    public static function formatTeraDescription($description)
    {
        $description = preg_replace(
            '/\$H_W_BAD\$?(.*?)\$COLOR_END/i',
            '<span class="tera-buff-bad">$1</span>',
            $description
        );
        $description = preg_replace(
            '/\$H_W_GOOD\$?(.*?)\$COLOR_END/i',
            '<span class="tera-buff-good">$1</span>',
            $description
        );
        $description = str_replace('$BR', '<br />', $description);

        return $description;
    }
}
