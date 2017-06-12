<?php

if (!function_exists('utg')) {
    function utg($text)
    {
        return iconv("UTF-8", "GBK//TRANSLIT", $text);
    }
}
