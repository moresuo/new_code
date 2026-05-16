<?php
/**
 * 功能：新闻搜索接口（SQL注入专用演示）
 * [!] 漏洞点：SQL注入 — keyword参数直接拼接，回显结果完整，适合联合查询注入演示
 * 演示：searchNews.php?keyword=' UNION SELECT 1,2,3,4,5,6--
 */
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";

    $keyword = @$_GET['keyword'];

    if (isset($keyword) && !empty($keyword)) {
        // [!] SQL注入点：keyword直接拼接，且响应回显SQL语句便于学习
        $sql = "select id,title,context,time,newImg,uid from new where title like '%$keyword%' or context like '%$keyword%'";
        $db = new DB();
        $result = $db->selectAll($sql);
        if ($result) {
            echo json_encode([
                "status" => 200,
                "msg" => "查询成功，执行的SQL: " . $sql,
                "data" => $result
            ]);
        } else {
            echo json_encode([
                "status" => 201,
                "msg" => "未找到结果，执行的SQL: " . $sql
            ]);
        }
    } else {
        echo json_encode([
            "status" => 202,
            "msg" => "请提供keyword参数"
        ]);
    }
