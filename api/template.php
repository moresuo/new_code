<?php
/**
 * 功能：模板包含接口
 * [!] 漏洞点：文件包含(LFI) — page参数直接拼入include，可读取任意文件
 * 演示：template.php?page=../tools/database.config 可包含配置文件
 * 注意：include会执行PHP代码，.php后缀由代码自动追加
 */
    include __DIR__."/tools/cors.php";

    $page = @$_GET['page'];

    if (isset($page) && !empty($page)) {
        // [!] LFI漏洞点：用户输入直接拼入include路径
        $file = $page . '.php';
        include($file);
    } else {
        echo json_encode([
            "status" => 202,
            "msg" => "参数错误，请提供page参数"
        ]);
    }
