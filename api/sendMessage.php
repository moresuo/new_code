<?php
/**
 * 功能：留言写入接口
 * [!] 漏洞点：存储型XSS — name和content参数无任何过滤直接入库，前端输出时也不转义
 * [!] 漏洞点：SQL注入 — 参数直接拼接SQL语句
 */
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";

    $name = @$_GET['name'];
    $content = @$_GET['content'];

    if (isset($name) && !empty($name) && isset($content) && !empty($content)) {
        // [!] SQL注入点：参数直接拼接
        $sql = "insert into message(name,content,time) values('$name','$content',NOW())";
        $db = new DB();
        $result = $db->insert($sql);
        if ($result) {
            echo json_encode([
                "status" => 200,
                "msg" => "留言成功"
            ]);
        } else {
            echo json_encode([
                "status" => 201,
                "msg" => "留言失败"
            ]);
        }
    } else {
        echo json_encode([
            "status" => 202,
            "msg" => "参数错误"
        ]);
    }
