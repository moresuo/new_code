<?php
/** [!] 漏洞文件 — 存在安全漏洞，详情见 CLAUDE.md */
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";

    $uid = @$_POST['uid'];
    $password = @$_POST['password'];
    $passwordtoo = @$_POST['passwordtoo'];

    if (isset($uid) && !empty($uid) && isset($password) && !empty($password) && isset($passwordtoo) && !empty($passwordtoo) && $password == $passwordtoo ) {
        $sql = "update user set password = '$password ' where id = '$uid'";
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
?>