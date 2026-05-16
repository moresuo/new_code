<?php
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";
    include __DIR__."/tools/authorization.php";

    $id = @$_GET['id'];
    $uid = @$_GET['uid'];
    $role = @$_GET['role'];
    $operatorName = @$_GET['operatorName'];

    authorization($uid, $role);
    if (isset($id) && !empty($id)) {
        $sql1 = "select status from user where id = '$id'";
        $db = new DB();
        $result1 = $db->selectOne($sql1);
        if ($result1 && (@$result1['status'])) {
                // 编写SQL语句 物理删除
                // $sql = "delete from user where id = '$id'";
                // // 编写SQL语句 软删除
                $sql2 = "update user set status = 'false' where id = '$id'";    
                $result2 = $db -> delete($sql2);
                if ($result2) {
                    $db->insert("insert into log(uid,username,action,time) values('$uid','$operatorName','删除用户ID：$id',NOW())");
                    echo json_encode([
                        "status" => 200,
                        "msg" => "删除成功"
                    ]);
                } else {
                    echo json_encode([
                        "status" => 201,
                        "msg" => "删除失败"
                    ]);
                }
            } else {
                echo json_encode([
                    "status" => 202,
                    "msg" => "参数异常"
                ]);
            }
        } else {
            echo json_encode([
                "status" => 201,
                "msg" => "删除失败"
            ]);
        }
?>