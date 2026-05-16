<?php
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";
    // 编写SQL语句
    $sql = "select * from user where 1=1";
    $search = @$_GET['search'];
    $uid = @$_GET['uid'];
    if (isset($search) && !empty($search)) {
        $sql .= " and username like '%$search%'";
        // select * from user where 1=1 and username like '%$search%'
    }
    if (isset($uid) && !empty($uid)) {
        $sql .= " and id = '$uid'";
        // select * from user where 1=1 and username like'%$search%' and id = '$uid'
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