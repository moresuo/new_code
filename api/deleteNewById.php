<?php
/** [!] 漏洞文件 — 存在安全漏洞，详情见 CLAUDE.md */
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";
    $nid = @$_GET['nid'];
    $uid = @$_GET['uid'];
    $username = @$_GET['username'];
    if (isset($nid) && !empty($nid)) {
        $sql1 = "select * from new where id = '$nid'";
        $db = new DB();
        $result1 = $db->selectOne($sql1);
        if ($result1) {
                // 编写SQL语句 物理删除
                // $sql = "delete from user where id = '$id'";
                // // 编写SQL语句 软删除
                $sql2 = "delete from new where id = '$nid'";    
                $result2 = $db -> delete($sql2);
                if ($result2) {
                    $db->insert("insert into log(uid,username,action,time) values('$uid','$username','删除新闻ID：$nid',NOW())");
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