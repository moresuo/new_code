<?php
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";
    include __DIR__."/tools/authorization.php";

    $uid = @$_GET['uid'];
    $role = @$_GET['role'];

    authorization($uid, $role);

    // 编写SQL语句
    $nid = @$_GET['nid'];
    $sql = "select new.id,new.title,user.username,review.id,review.context,review.time from new,user,review where review.nid = new.id and review.uid = user.id";
    
    if (isset($nid) && !empty($nid)) {
        $sql .= " and new.id = '$nid'";
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