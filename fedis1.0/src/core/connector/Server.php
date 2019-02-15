<?php
/*
 * Description:
 * Author: feipeixuan
 */


namespace org\fedis\connector;

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

        }
        // 监听socket,最大等会数为4
        $ret = socket_listen($this->socket, 2);
        // 设置为非阻塞模式
        socket_set_block($this->socket);
    }

    public function execute()
    {
        // 单线程的模式
        do {
            $msgsock = socket_accept($this->socket);
            if (!$msgsock) {
                //TODO
            }
            $content = socket_read($msgsock, 4096);
            if ($content != "start transaction") {

            } else {
                $tranctionId = 1024;
                socket_write($msgsock, $tranctionId);
                // TODO 收到回应
                do {
                    $content=socket_read($msgsock,4096);
                    if(strpos($content,"end tranction") !=false){
                        echo "222333";
                        print $content;
                        break;
                    }
                } while (true);
            }

        } while (true);
    }
}

Server::getInstance()->execute();