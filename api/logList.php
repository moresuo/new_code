<?php
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";

    $sql = "select * from log order by id desc";
    $search = @$_GET['search'];

    if (isset($search) && !empty($search)) {
        $sql = "select * from log where username like '%$search%' or action like '%$search%' order by id desc";
    }

    $db = new DB();
    $result = $db->selectAll($sql);

    if ($result) {
        echo json_encode([
            "status" => 200,
            "msg" => "查询成功",
            "data" => $result
        ]);
    } else {
        echo json_encode([
            "status" => 201,
            "msg" => "暂无日志数据"
        ]);
    }
