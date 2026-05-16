<?php
/**
 * 功能：文件下载接口
 * [!] 漏洞点：路径遍历 — filename参数未过滤，可通过../读取任意文件
 * 演示：download.php?filename=../tools/database.config.php 可读取数据库配置
 */
    include __DIR__."/tools/cors.php";

    $filename = @$_GET['filename'];

    if (isset($filename) && !empty($filename)) {
        // [!] 路径遍历漏洞：未校验filename是否包含../
        $filepath = __DIR__ . "/upload/" . $filename;

        if (file_exists($filepath)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
            readfile($filepath);
            exit;
        } else {
            echo json_encode([
                "status" => 201,
                "msg" => "文件不存在: $filepath"
            ]);
        }
    } else {
        echo json_encode([
            "status" => 202,
            "msg" => "参数错误"
        ]);
    }
