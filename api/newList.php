<?php
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";
    // 编写SQL语句
    $sql = "select new.id,new.title,new.context,new.time,new.newImg,user.username from new,user where new.uid = user.id";
    $search = @$_GET['search'];
    $nid = @$_GET['nid'];
    if (isset($search) && !empty($search)) {
        $sql .= " and new.title like '%$search%'";
        // select * from user where 1=1 and username like '%$search%'
    }
    if (isset($nid) && !empty($nid)) {
        $sql .= " and new.id = '$nid'";
        // select * from user where 1=1 and username like'%$search%' and id = '$uid'
    }

    $db = new DB();
    $result = $db -> selectAll($sql);

    if ($result) {
        echo json_encode([
            "status" => 200,
            "msg" => "查询成功" . $sql,
            "data" => $result
        ]);
    }else {
        echo json_encode([
            "status" => 201,
            "msg" => "暂无数据"
        ]);
    }
?>