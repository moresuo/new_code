<?php
/** [!] 漏洞文件 — 存在安全漏洞，详情见 CLAUDE.md */
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";

    $username = @$_POST['username'];
    $role = @$_POST['role'];
    $email = @$_POST['email'];
    $password = @$_POST['password'];
    $passwordtoo = @$_POST['passwordtoo'];
    $operatorUid = @$_POST['operatorUid'];
    $operatorName = @$_POST['operatorName'];

    if (isset($username) && !empty($username) && isset($email) && !empty($email) && isset($role) && !empty($role) && isset($password) && !empty($password) && isset($passwordtoo) && !empty($passwordtoo) && $password == $passwordtoo ) {
        $sql = "insert into user(username,password,email,role,status,register) value('$username','$password','$email ','$role','true',NOW())";
        $db = new DB();
        $result = $db->update($sql);
        if ($result) {
            $db->insert("insert into log(uid,username,action,time) values('$operatorUid','$operatorName','添加用户：$username',NOW())");
            echo json_encode([
                "status" => 200,
                "msg" => "添加成功"
            ]);
        } else {
            echo json_encode([
                "status" => 201,
                "msg" => "添加失败"
            ]);
        }
    } else {
        echo json_encode([
            "status" => 202,
            "msg" => "参数错误"
        ]);
    }
?>