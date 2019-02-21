<?php
/*
 * Description:
 * Author: feipeixuan
 */

namespace org\fedis\connector;

class Message{

    public $content;

    public $transactionId;

    public $commitFlag;

    function __construct($content,$transactionId=null,$commitFlag=false)
    {
        $this->content=$content;
        $this->transactionId=$transactionId;
        $this->commitFlag=$commitFlag;
    }
}
