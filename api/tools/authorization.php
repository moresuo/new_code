<?php
function authorization($uid,$role) {
    if (isset($uid) && !empty($uid) && isset($role) && !empty($role)) {
        if ($role != "admin") {
            echo json_encode([
                "status" => 401,
                "msg" => "权限不足"
            ]);
            exit;
        }
    } else {
        echo json_encode([
            "status" => 403,
            "msg" => "参数异常 $uid $role"
        ]);
        exit;
    }
}

?>