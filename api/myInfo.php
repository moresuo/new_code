<?php
/**
 * 功能：个人信息查询接口
 * [!] 漏洞点：水平越权(IDOR) — 通过uid参数可查看任意用户信息，无所有权校验
 * [!] 漏洞点：SQL注入 — 参数直接拼接
 * 演示：myInfo.php?uid=202601001 可查看admin用户信息
 */
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";

    $uid = @$_GET['uid'];

    if (isset($uid) && !empty($uid)) {
        // [!] SQL注入点 + 水平越权：无所有权校验，可查任意用户
        $sql = "select id,username,email,role,status,register from user where id = '$uid'";
        $db = new DB();
        $result = $db->selectOne($sql);
        if ($result) {
            echo json_encode([
                "status" => 200,
                "msg" => "查询成功",
                "data" => $result
            ]);
        } else {
            echo json_encode([
                "status" => 201,
                "msg" => "用户不存在"
            ]);
        }
    } else {
        echo json_encode([
            "status" => 202,
            "msg" => "参数错误"
        ]);
    }
