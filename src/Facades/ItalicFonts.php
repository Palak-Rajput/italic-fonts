<?php

namespace YourName\ItalicFonts\Facades;

use Illuminate\Support\Facades\Facade;

class ItalicFonts extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'italic-fonts';
    }
}