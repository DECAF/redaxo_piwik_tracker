<?php

if (!function_exists('rex_get_file_contents'))
{
    function rex_get_file_contents($path)
    {
        return file_get_contents($path);
    }
}

if (!function_exists('rex_info'))
{
    function rex_info($str)
    {
        return file_warning($str);
    }
}
