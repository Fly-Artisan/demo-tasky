<?php namespace FLY_ENV\Util\Wave_Engine;
/**
 * @author K.B Brew <flyartisan@gmail.com>
 * @package FLY_ENV\Util\Wave_Engine
 * @version 2.0.0
 */
class Dictionary {

    public static function callerStacks()
    {
        return [
            'url',
            'statics',
            'usecss',
            'usejs',
            'usecdnjs',
            'usecdncss',
            'cdnurl',
            'import',
            'thisYear',
            'thisMonth',
            'dateQuery',
            ':echo',
            'word_lmt',
            'str_capitalize'
        ];
    }
}