<?php
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";

    $nid = @$_GET['nid'];
    $uid = @$_GET['uid'];
    $context = @$_GET['context'];

    if (isset($nid) && !empty($nid) && isset($uid) && !empty($uid) && isset($context) && !empty($context) ) {
        $sql = "insert into review(nid,uid,context,time) value('$nid','$uid','$context ',NOW())";
        $db = new DB();
        $result = $db->update($sql);
        if ($result) {
            echo json_encode([
                "status" => 200,
                "msg" => "评论成功"
            ]);
        } else {
            echo json_encode([
                "status" => 201,
                "msg" => "评论失败"
            ]);
        }
    } else {
        echo json_encode([
            "status" => 202,
            "msg" => "参数错误"
        ]);
    }
?>