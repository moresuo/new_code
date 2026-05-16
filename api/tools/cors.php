<?php
    // 仅允许 localhost:8081 跨域访问
    $allowed_origin = 'http://localhost:8081';
    header("Access-Control-Allow-Origin: $allowed_origin");
    header("Access-Control-Allow-Headers: Content-Type,Authorization,Cache-Control");
    header('Access-Control-Allow-Methods: GET,POST,OPTIONS');
    header('Access-Control-Allow-Credentials: true');
?>