<?php
/** [!] 漏洞文件 — 存在安全漏洞，详情见 CLAUDE.md */
    // include include_once
    // require require_once  
    // 导入其他文件 once表示只导入一次
    include __DIR__ . "/tools/cors.php";

    // 文件数组解构
    $file = @$_FILES['file'];
    // 后缀名获取
    $file_ext = explode(".",$file['name'])[count(explode(".",$file['name']))-1];
    // 临时文件路径
    $file_temp_url = $file['tmp_name'];
    // 时间戳文件名
    $file_name = time() . "." . $file_ext;
    // 目标文件路径
    $file_upload_url = $_SERVER["DOCUMENT_ROOT"]."/upload/" . $file_name;
    // 远程文件路径
    $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'http';
    $file_url = $scheme . "://" . $_SERVER["HTTP_HOST"] . "/upload/" . $file_name;
    // 移动上传文件
    $result = move_uploaded_file($file_temp_url,$file_upload_url);
    // 上传成功返回上传路径和信息，否则返回上传失败信息
    if ($result) {
        echo json_encode([
            "status"=>200,
            "msg"=>"上传成功",
            "url" => $file_url
        ]);
    }else {
        echo json_encode([
            "status"=>201,
            "msg"=>"上传失败 $result"
        ]);
    }
?>