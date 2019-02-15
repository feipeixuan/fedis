<?php
/*
 * Description:
 * Author: feipeixuan
 */

namespace org\fedis\semantic;

// 查询分析器
class SqlParser{

    // 校验命令
    function validate($sql){

    }

    // 执行命令
    function execute($sql){
        $this->validate($sql);
    }
}