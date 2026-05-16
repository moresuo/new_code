<?php
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";

    $uid = @$_GET['uid'];
    $username = @$_GET['username'];
    $action = @$_GET['action'];

    if (isset($uid) && !empty($uid) && isset($username) && !empty($username) && isset($action) && !empty($action)) {
        $sql = "insert into log(uid,username,action,time) values('$uid','$username','$action',NOW())";
        $db = new DB();
        $result = $db->insert($sql);
        if ($result) {
            echo json_encode([
                "status" => 200,
                "msg" => "日志记录成功"
            ]);
        } else {
            echo json_encode([
                "status" => 201,
                "msg" => "日志记录失败"
            ]);
        }
    } else {
        echo json_encode([
            "status" => 202,
            "msg" => "参数错误"
        ]);
    }
