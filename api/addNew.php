<?php
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";
    include __DIR__."/tools/authorization.php";

    $uid = @$_POST['uid'];
    $role = @$_POST['role'];
    $username = @$_POST['username'];
    $title = @$_POST['title'];
    $context = @$_POST['context'];
    $newImg = @$_POST['newImg'];

    authorization($uid,$role);

    if (isset($uid) && !empty($uid)) {
        $sql = "insert into new(uid,title,context,newImg,time) values('$uid','$title','$context','$newImg',NOW())";
        $db = new DB();
        $result = $db->update($sql);
        if ($result) {
            $db->insert("insert into log(uid,username,action,time) values('$uid','$username','发布新闻：$title',NOW())");
            echo json_encode([
                "status" => 200,
                "msg" => "新增成功"
            ]);
        } else {
            echo json_encode([
                "status" => 201,
                "msg" => "新增失败"
            ]);
        }
    }
?>