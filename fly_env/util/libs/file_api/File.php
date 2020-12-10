<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace FLY\Libs\File_API;


/**
 * Description of File
 *
 */
class File {
    
    private static $file;
    
    private static function assign(&$var, $value) 
    {
        $var = $value;
    }
    
    public static function set_file(string $file_objectname) 
    {
        self::$file = $_FILES[$file_objectname];
    }
    
    public static function is_present(string $file_objectname)
    {
        return (
            isset($_FILES[$file_objectname]['name'])     && 
            is_string($_FILES[$file_objectname]['name']) &&
            trim($_FILES[$file_objectname]['name'])      <> ""
        );
    }

    public static function get_name()
    {
        return self::$file['name'];
    }
    
    public static function set_name($filename) 
    {
        self::$file['name'] = $filename;
        self::assign(self::$file['name'], $filename);
    }
    
    public static function get_temp()
    {
        return self::$file['tmp_name'];
    }
    
    public static function set_temp($tmp_name) 
    {
        self::$file['tmp_name'] = $tmp_name;
        
        self::assign(self::$file['tmp_name'], $tmp_name);
    }
    
    public static function get_type()
    {
        return self::$file['type'];
    }
    
    public static function set_type($type) 
    {
        self::assign(self::$file['type'], $type);
    }

    public static function upload_file_exists($name)
    {
        self::set_file($name);
        return  isset(self::$file['tmp_name']) && trim(self::$file['tmp_name']) !== "";
    }

    public static function get_size()
    {
        return self::$file['size'];
    }
    
    public static function set_size($size) 
    {
        self::assign(self::$file['size'], $size);
    }
    
    public static function get_error()
    {
        return self::$file['error'];
    }
    
    public static function set_error($error) 
    {
        self::assign(self::$file['error'], $error);
    }

    public static function remove(string $fileName)
    {
        $flag = false;
        if(!is_dir(FLY_APP_ROOT_DIR.$fileName) && file_exists(FLY_APP_ROOT_DIR.$fileName)) {
            unlink(FLY_APP_ROOT_DIR.$fileName);
            $flag = true;
        }
        return $flag;
    }
    
    public static function exists(string $fileName)
    {
        return file_exists(FLY_APP_ROOT_DIR.$fileName);
    }

    public static function move_to(string $path) 
    {
        if(file_exists($path.self::$file['name'])) return ['state' => false, 'payload' => 'file_exists'];
        
        if(move_uploaded_file(self::$file['tmp_name'], $path.'/'.self::$file['name'])) {
            return ['state' => true, 'payload' => 'success'];
        }

        return ['state' => false, 'payload' => self::$file['error']];
    }
}
