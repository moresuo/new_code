<?php
/** [!] 漏洞文件 — 存在安全漏洞，详情见 CLAUDE.md */
    session_start();
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";

    $username = @$_POST['username'];
    $password = @$_POST['password'];
    $captcha = @$_REQUEST["captcha"];
    $captchaCodes = $_SESSION['captcha'];

    if (isset($username) && !empty($username) && isset($captcha) && !empty($captcha) &&  isset($password) && !empty($password)) {
        // [!] 逻辑漏洞：万能验证码 0000 可绕过验证码校验
        if (strtolower($captcha) == strtolower($captchaCodes) || $captcha === '0000') {
            $sql = "select * from user where username = '$username' and password = '$password'";
            $db = new DB();
            $result = $db->selectOne($sql);
            if ($result) {
                $_SESSION['username'] = $result['username'];
                $_SESSION['uid'] = $result['id'];
                $_SESSION['role'] = $result['role'];

                setcookie("username",$result['username'],time()+60*60*24*7,"/");
                setcookie("uid",$result['id'],time()+60*60*24*7,"/");
                setcookie("role",$result['role'],time()+60*60*24*7,"/");

                $db->insert("insert into log(uid,username,action,time) values('{$result['id']}','{$result['username']}','登录系统',NOW())");

                echo json_encode([
                    "status" => 200,
                    "msg" => "登录成功",
                    "data" =>  $result
                ]);
            } else {
                echo json_encode([
                    "status" => 201,
                    "msg" => "登录失败 "
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