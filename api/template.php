<?php
/**
 * 功能：文件包含接口（漏洞演示）
 * [!] 漏洞点：文件包含(LFI) — page参数直接拼入include，可读取/执行任意文件
 * 演示：template.php?page=../tools/database.config 可包含配置文件
 * 演示：template.php?page=../tools/DB 可包含并执行DB类中的PHP代码
 * 注意：include会执行PHP代码，如需查看源码请使用download.php
 */
    include __DIR__."/tools/cors.php";

    $page = @$_GET['page'];

    if (isset($page) && !empty($page)) {
        // [!] LFI漏洞点：用户输入直接拼入include路径
        // 如果用户已经指定了.php后缀，不再重复追加
        $hasPhpExt = (substr($page, -4) === '.php');
        $file = $hasPhpExt ? $page : $page . '.php';
        if (file_exists($file)) {
            include($file);
        } else {
            // 尝试不加后缀直接包含
            if (file_exists($page)) {
                include($page);
            } else {
                echo json_encode([
                    "status" => 201,
                    "msg" => "文件不存在: $file"
                ]);
            }
        }
    } else {
        echo json_encode([
            "status" => 202,
            "msg" => "参数错误，请提供page参数"
        ]);
    }
