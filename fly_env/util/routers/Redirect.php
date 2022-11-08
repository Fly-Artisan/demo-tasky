<?php namespace FLY\Routers;
/**
 * @author K.B Brew <flyartisan@gmail.com>
 * @package FLY\Routers
 * @version 2.0.0
 */
class Redirect 
{ 
    public static function to($uri) {
        header('Location:'.$uri);
        exit(); 
    }
}