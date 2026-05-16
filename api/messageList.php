<?php
/**
 * 功能：留言列表查询接口
 * [!] 漏洞点：存储型XSS — 查询结果原样返回JSON，前端innerHTML渲染不转义
 * [!] 漏洞点：SQL注入 — 无参数化查询
 */
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";

    // [!] SQL注入点：直接拼接SQL
    $sql = "select * from message order by id desc";

    $db = new DB();
    $result = $db->selectAll($sql);

    if ($result) {
        echo json_encode([
            "status" => 200,
            "msg" => "查询成功",
            "data" => $result
        ]);
    } else {
        echo json_encode([
            "status" => 201,
            "msg" => "暂无留言"
        ]);
    }
