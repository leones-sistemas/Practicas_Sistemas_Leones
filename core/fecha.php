<?php
namespace core;
class fecha{
    public static function now(): string{
        return date('Y-m-d H:i:s');
    }
    public static function expiration($time) : string {
        return date('Y-m-d H:i:s',$time);
    }
    public static function this(): string{
        return date('Y-m-d');
    }
    public static function time(): string{
        return date('H:i:s');
    }
}