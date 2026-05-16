<?php
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";
    $rid = @$_GET['rid'];
    if (isset($rid) && !empty($rid)) {
        $sql1 = "select * from review where id = '$rid'";
        $db = new DB();
        $result1 = $db->selectOne($sql1);
        if ($result1) {
                // 编写SQL语句 物理删除
                // $sql = "delete from user where id = '$id'";
                // // 编写SQL语句 软删除
                $sql2 = "delete from review where id = '$rid'";    
                $result2 = $db -> delete($sql2);
                if ($result2) {
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
                "msg" => "删除失败1"
            ]);
        }
?>