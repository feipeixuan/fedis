<?php
/*
 * Description:
 * Author: feipeixuan
 */

class Config{

    public $logDir;

    private static $_instance;

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new Config();
        }
        return self::$_instance;
    }
    function __construct()
    {
        $this->logDir=__DIR__."/../../resource/logs";
    }
}


