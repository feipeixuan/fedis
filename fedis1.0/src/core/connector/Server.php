<?php
/*
 * Description:
 * Author: feipeixuan
 */

namespace org\fedis\connector;
use org\fedis\exception\ConnectionException;

include __DIR__ . "/Message.php";
include __DIR__ . "/../exception/Exception.php";

class Server
{

    private static $_instance;

    private $socket;

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new Server();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (socket_bind($this->socket, "127.0.0.1", 3359) == false) {
            throw new ConnectionException("端口占用导致服务器失败");
        }
        // 监听socket,最大等会数为4
        $ret = socket_listen($this->socket, 2);
        // 设置为非阻塞模式
        socket_set_nonblock($this->socket);
    }

    public function execute()
    {
        // 单线程的模式
        do {
            $msgSocket = socket_accept($this->socket);
            if ($msgSocket) {
                $this->executeSession($msgSocket);
            }
        } while (true);
    }

    public function executeSession($msgSocket)
    {
        $flag = true;
        do {
            $startTime = time();
            while (($clientMessage = socket_read($msgSocket, 4096)) == false) {
                if ((time() - $startTime) >= 3) {
                    throw new ConnectionException("连接中断");
                }
            }
            $clientMessage = unserialize($clientMessage);
            print_r($clientMessage);
            //开启事务操作
            if ($clientMessage->content == 'start transaction') {
                $serverMessage = new Message("", 23);
                socket_write($msgSocket, serialize($serverMessage));
            } //关闭事务操作
            elseif ($clientMessage->commitFlag) {
                // TODO 执行命令
                socket_close($msgSocket);
                return;
            }
        } while ($flag);
    }
}

Server::getInstance()->execute();