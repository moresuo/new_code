<?php
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";

    $nid = @$_POST['nid'];
    $uid = @$_POST['uid'];
    $title = @$_POST['title'];
    $context = @$_POST['context'];
    $newImg = @$_POST['newImg'];

    if (isset($nid) && !empty($nid)) {
        $sql = "update new set title = '$title',context = '$context',newImg = '$newImg',time = NOW() where id = '$nid' and uid = '$uid'";
        $db = new DB();
        $result = $db->update($sql);
        if ($result) {
            echo json_encode([
                "status" => 200,
                "msg" => "修改成功"
            ]);
        } else {
            echo json_encode([
                "status" => 201,
                "msg" => "修改失败"
            ]);
        }
    }
?>