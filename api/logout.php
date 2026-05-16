<?php
    include __DIR__."/tools/cors.php";
    session_start();
    $result = session_destroy();
    if ($result) {
        echo json_encode([
            "status" => 200,
            "msg" => "退出成功"
        ]);
    } else {
        echo json_encode([
            "status" => 201,
            "msg" => "退出失败"
        ]);
    }
?>