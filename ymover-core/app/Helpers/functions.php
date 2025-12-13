<?php

use App\Core\Translator;

if (!function_exists('__')) {
    function __(string $key): string
    {
        return Translator::getInstance()->translate($key);
    }
}
