<?php
/**
 * Created by PhpStorm.
 * User: changba-176
 * Date: 2019/2/15
 * Time: 上午12:16
 */

namespace org\fedis\transaction;

class TransactionManager
{
    private static $_instance;

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new TransactionManager();
        }
        return self::$_instance;
    }

    public function getTransactionId(){

    }
}