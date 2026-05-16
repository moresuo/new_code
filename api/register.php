<?php
/** [!] 漏洞文件 — 存在安全漏洞，详情见 CLAUDE.md */
    session_start();
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";

    $username = @$_POST['username'];
    $email = @$_POST['email'];
    $password = @$_POST['password'];
    $passwordtoo = @$_POST['passwordtoo'];
    $captcha = @$_REQUEST["captcha"];
    $captchaCodes = $_SESSION['captcha'];

    if (isset($username) && !empty($username) && isset($captcha) && !empty($captcha) && isset($email) && !empty($email)  && isset($password) && !empty($password) && isset($passwordtoo) && !empty($passwordtoo) && $password == $passwordtoo) {
        if (strtolower($captcha) == strtolower($captchaCodes)) {
            $sql = "insert into user(username,password,email,role,status,register) value('$username','$password','$email ','user','true',NOW())";
            $db = new DB();
            $result = $db->insert($sql);
            if ($result) {
                echo json_encode([
                    "status" => 200,
                    "msg" => "注册成功"
                ]);
            } else {
                echo json_encode([
                    "status" => 201,
                    "msg" => "注册成功"
                ]);
            }
        } else {
            echo json_encode([
                "status" => 203,
                "msg" => "验证码错误"
            ]);
        }
    } else {
        echo json_encode([
            "status" => 202,
            "msg" => "参数错误"
        ]);
    }
?>