<?php
    // 允许的跨域来源
    $allowed_origins = [
        'http://localhost:8081',
        'http://127.0.0.1:8081',
    ];

    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

    if (in_array($origin, $allowed_origins)) {
        header("Access-Control-Allow-Origin: $origin");
        header("Access-Control-Allow-Headers: Content-Type,Authorization,Cache-Control");
        header('Access-Control-Allow-Methods: GET,POST,OPTIONS');
        header('Access-Control-Allow-Credentials: true');
    }

    // 预检请求直接返回，不执行业务逻辑
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit;
    }
?>