<?php
/**
 * Created by PhpStorm.
 * User: changba-176
 * Date: 2019/2/13
 * Time: 下午10:19
 */

namespace org\fedis\connector;


class Client
{
    private static $_instance;

    private $socket;

    function __construct()
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        // 设置为非阻塞模式
        socket_set_block($this->socket);
    }

    // 建立连接
    public function buildConnection()
    {
        if (socket_connect($this->socket, "127.0.0.1", 3359) == false) {

        }
    }

    // 开启事务
    public function beginTransaction()
    {
        socket_write($this->socket,"start transaction");
        while (($data=socket_read($this->socket,1024))==false){
        }
        print $data;
        socket_write($this->socket,"end transaction");
        print ("222");
    }

    // 提交事务
    public function commit()
    {

    }

    // 提交sql命令
    public function execute($sql)
    {
        socket_write($this->socket,$sql);
        socket_write($this->socket,$sql);
    }
}

$instance = new Client();
$instance->buildConnection();
$instance->beginTransaction();