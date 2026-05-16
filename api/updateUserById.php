<?php
/** [!] 漏洞文件 — 存在安全漏洞，详情见 CLAUDE.md */
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";

    $uid = @$_POST['uid'];
    $username = @$_POST['username'];
    $email = @$_POST['email'];
    $role = @$_POST['role'];
    $status = @$_POST['status'];

    if (isset($uid) && !empty($uid)) {
        $sql = "update user set username = '$username',email = '$email',role = '$role',status = '$status' where id = '$uid'";
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
    }
?>