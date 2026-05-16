<?php
/** [!] 漏洞文件 — 存在安全漏洞，详情见 CLAUDE.md */
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";
    include __DIR__."/tools/authorization.php";

    $uid = @$_POST['uid'];
    $role = @$_POST['role'];
    $id = @$_POST['id'];
    $username = @$_POST['username'];
    $email = @$_POST['email'];
    $newRole = @$_POST['newRole'];
    $status = @$_POST['status'];

    authorization($uid, $role);

    if (isset($uid) && !empty($uid)) {
        $sql = "update user set username = '$username',email = '$email',role = '$newRole',status = '$status' where id = '$id'";
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