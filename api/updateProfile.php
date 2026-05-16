<?php
/**
 * 功能：个人资料修改接口
 * [!] 漏洞点：水平越权(IDOR) — 通过uid参数可修改任意用户的email，无所有权校验
 * [!] 漏洞点：SQL注入 — 参数直接拼接
 */
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";

    $uid = @$_POST['uid'];
    $email = @$_POST['email'];

    if (isset($uid) && !empty($uid) && isset($email) && !empty($email)) {
        // [!] SQL注入点 + 水平越权：无所有权校验，可改任意用户邮箱
        $sql = "update user set email = '$email' where id = '$uid'";
        $db = new DB();
        $result = $db->update($sql);
        if ($result) {
            echo json_encode([
                "status" => 200,
                "msg" => "修改成功"
            ]);
        } else {
            echo json_encode([
                "status" => 201,
                "msg" => "修改失败"
            ]);
        }
    } else {
        echo json_encode([
            "status" => 202,
            "msg" => "参数错误"
        ]);
    }
