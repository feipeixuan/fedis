<?php

namespace org\fedis\transaction;
include __DIR__ . "/../config.php";

class TransactionManager
{
    private static $_instance;

    private $startId;

    private $currentId;

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new TransactionManager();
        }
        return self::$_instance;
    }

    function __construct()
    {
        $this->currentId = 1;
        $this->startId = 1;
    }

    public function getTransactionId()
    {
        return $this->currentId++;
    }

    /*
     * 恢复函数
     */
    public function recovery()
    {
        $this->currentId = getLatestTransactionId();
        $this->recoveryTransaction();
    }

    /**
     * 系统启动时获取最近事务id
     */
    public function getLatestTransactionId()
    {
        $logFiles = scandir(\Config::getInstance()->logDir);
        $logNums = array();
        foreach ($logFiles as $logFile) {
            if ($logFile != '.' && $logFile != '..') {
                $logNum = explode(".", $logFile)[0];
                $logNums[] = $logNum;
            }
        }
        if (count($logNums) == 0) {
            return 1;
        }
        rsort($logNums);
        $laterstLogNum = $logNums[0];
        $latestLog = \Config::getInstance()->logDir . "/$laterstLogNum" . ".log";
        $resource = fopen($latestLog, "r");
        $maxTransactionId = intval($laterstLogNum);
        while (!feof($resource)) {
            //日志文件格式暂定第一列是事务数字
            $line = fgets($resource);
            $line = str_replace("\n", "", $line);
            $transactionId = intval(explode(":", $line)[0]);
            $maxTransactionId = max($maxTransactionId, $transactionId);
        }
        fclose($resource);
        return $maxTransactionId;
    }

    /**
     * 写日志文件，格式如右:事务id:动作:表名:ID:旧值:新值
     */
    public function recordLog($log)
    {
        $logFile = \Config::getInstance()->logDir . "/$this->startId" . ".log";
        return file_put_contents($logFile, $log . "\n", FILE_APPEND) > 0 ? true : false;
    }

    /**
     * 恢复事务
     */
    public function recoveryTransaction()
    {
        $logFiles = scandir(\Config::getInstance()->logDir);
        $logNums = array();
        $logFile = null;
        foreach ($logFiles as $file) {
            if ($file != '.' && $file != '..') {
                $logFile = $file;
                break;
            }
        }
        if ($logFile == null) {
            return;
        }
        $this->currentId = $this->getLatestTransactionId() + 1;
        // 进行事务恢复或者撤销操作

    }
}

$transactionManager = TransactionManager::getInstance();
echo $transactionManager->getLatestTransactionId();