<?php

namespace routes;

class security
{
    public static function logged(): bool
    {
        return true;
    }
    public static function valid(): bool
    {
        return false;
    }
}
