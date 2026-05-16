<?php
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";

    $email = @$_GET['email'];
    $authcode = @$_GET['authcode'];
    // 编写SQL语句
    $sql1 = "select * from authcode where email = '$email' and authcode = '$authcode'";
    $sql2 = "select id from user where email = '$email'";
    $db = new DB();
    $result1 = $db -> selectOne($sql1);
    $result2 = $db -> selectOne($sql2);
    if ($result1 && $result2) {
            echo json_encode([
                "status" => 200,
                "msg" => "验证码验证成功",
                "uid" => $result2['id']
            ]);
        }else {
        echo json_encode([
            "status" => 201,
            "msg" => "验证码错误"
        ]);
    }
?>