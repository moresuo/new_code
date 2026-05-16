<?php
/** [!] 漏洞文件 — 存在安全漏洞，详情见 CLAUDE.md */
    include __DIR__ . "/tools/cors.php";
    $filename = @$_GET['filename'];
    $bastpath = __DIR__ . "/upload/";

    if (isset($filename) && !empty($filename)) {
        $result = unlink($bastpath . $filename);
        if($result) {
            echo json_encode([
                "status"=>200,
                "msg"=> "文件删除成功"
            ]);
        }else {
            echo json_encode([
                "status"=>202,
                "msg"=> "文件删除失败"
            ]);
        }
    } else {
        echo json_encode([
            "status"=>201,
            "msg"=> "参数非法"
        ]);
    }

?>