<?php   

    class Module
    {

        private static $modules = null;

        public static function get($moduleName)
        {
            if(self::$modules == null) 
            {
                self::$modules = require_once APPROOT.DS.'modules'.DS.'all.php';
            }
            
            return self::$modules[$moduleName];
        }
        public static function all($name = null)
        {
            if(self::$modules == null) {
                $modules = require_once APPROOT.DS.'modules'.DS.'all.php';
            }

            if(!is_null($name)) {
                return $modules[$name];
            }
            
            return $modules;
        }

        
    }