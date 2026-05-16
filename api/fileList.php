<?php
    include __DIR__ . "/tools/cors.php";

   // 定义返回给前端的关联数组
   $fileList = [];
   // 本地物理路径
   $local_path =  __DIR__ . "/upload/";
   // 远程文件路径
   $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'http';
   $file_url = $scheme . "://" . $_SERVER["HTTP_HOST"] . "/upload/";
   // 循环读取物理路径下的文件
   $files = scandir($local_path);
   if (count($files) > 2) {
       foreach ($files as $file) {
            // 排除 . 和 ..
            if ($file != "." && $file != "..") {
               // 进行文件下载准备
               // 1. 文件大小
               $file_size = round(filesize($local_path.$file) / 1024,2);
               // 2. 文件类型
               $file_type = pathinfo($file, PATHINFO_EXTENSION);
               // 3. 文件下载地址
               $download_url = $file_url . $file;
               // 4. 关联数组的构建
               array_push($fileList,[
                "filename" => $file,
                "filesize" => $file_size,
                "filetype" => $file_type,
                "download_url" => $download_url
               ]);
            }
       }
       echo json_encode([
         "status" => 200,
         "msg" => "查询成功",
         "data" => $fileList
       ]);
   } else {
    echo json_encode([
        "status" => 201,
        "msg" => "当前无文件",
        "data" => []
      ]);
   } 
?>