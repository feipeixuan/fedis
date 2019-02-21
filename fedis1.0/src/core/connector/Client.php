<?php
/**
 * Created by PhpStorm.
 * User: changba-176
 * Date: 2019/2/13
 * Time: 下午10:19
 */

namespace org\fedis\connector;

use org\fedis\exception\ConnectionException;

include __DIR__ . "/Message.php";
include __DIR__ . "/../exception/Exception.php";


class Client
{
    private static $_instance;

    private $socket;

    private $transactionId;

    private $commandQueue = array();

    function __construct()
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    }

    // 建立连接
    public function buildConnection()
    {
        $startTime = time();
        if (socket_connect($this->socket, "127.0.0.1", 3359) == false) {
            if ((time() - $startTime) >= 3) {
                throw new ConnectionException("连接建立失败");
            }
        }
        socket_set_nonblock($this->socket);
    }

    // 开启事务
    public function beginTransaction()
    {
        $startTime = time();
        $requestMessage = new Message("start transaction");
        while ((socket_write($this->socket, serialize($requestMessage))) == false) {
            if ((time() - $startTime) >= 3) {
                throw new ConnectionException("连接中断");
            }
        }
        $startTime = time();
        while (($responseMessage = socket_read($this->socket, 1024)) == false) {
            if ((time() - $startTime) >= 3) {
                throw new ConnectionException("连接中断");
            }
        }
        $responseMessage = unserialize($responseMessage);
        $this->transactionId = $responseMessage->transactionId;
    }

    // 提交事务
    public function commit()
    {
        $startTime = time();
        $requestMessage = new Message($this->commandQueue, $this->transactionId, true);
        while ((socket_write($this->socket, serialize($requestMessage))) == false) {
            if ((time() - $startTime) >= 3) {
                throw new ConnectionException("连接中断");
            }
        }
        print_r($requestMessage);
        socket_close($this->socket);
    }

    // 提交sql命令
    public function execute($command)
    {
        if (!isset($this->transactionId)) {
            $this->beginTransaction();
            $this->execute($command);
            $this->commit();
        } else {
            $this->enqueueCommand($command);
        }
    }

    private function enqueueCommand($command)
    {
        array_push($this->commandQueue, $command);
        echo "入队成功" . "\n";
    }
}

$instance = new Client();

try {
    $instance->buildConnection();
    $instance->execute("insert");
} catch (\Exception $exception) {

}