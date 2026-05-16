<?php
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";
    include __DIR__."/tools/authorization.php";

    $uid = @$_GET['uid'];
    $role = @$_GET['role'];

    authorization($uid, $role);

    // 编写SQL语句
    $sql = "select * from user where 1=1";
    $search = @$_GET['search'];
    if (isset($search) && !empty($search)) {
        $sql .= " and username like '%$search%'";
        // select * from user where 1=1 and username like '%$search%'
    }

    $db = new DB();
    $result = $db -> selectAll($sql);

    if ($result) {
        echo json_encode([
            "status" => 200,
            "msg" => "查询成功",
            "data" => $result
        ]);
    }else {
        echo json_encode([
            "status" => 201,
            "msg" => "暂无数据"
        ]);
    }
?>