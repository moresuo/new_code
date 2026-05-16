<?php
    include __DIR__."/tools/DB.php";
    include __DIR__."/tools/cors.php";
    // 导入核心包
    include_once './PHPMailer/PHPMailer.php';
    include_once './PHPMailer/Exception.php';
    include_once './PHPMailer/SMTP.php';
    // 引入PHPMailer核心类
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $email = @$_GET['email'];
    // 编写SQL语句
    $sql1 = "select * from user where email = '$email'";

    $db = new DB();
    $result = $db -> selectOne($sql1);

    if ($result) {
        // 添加邮件正文,并生成 四位随机验证码
        $authcode = mt_rand(1000,9999);
        $sql2 = "insert into authcode(email,authcode) values('$email','$authcode')";
        $result2 = $db -> insert($sql2);
        if ($result2) {
            // 实例化PHPMailer核心类
            $mail = new PHPMailer();
            try {
                // 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
                $mail->SMTPDebug = 1;
                // 使用smtp鉴权方式发送邮件
                $mail->isSMTP();
                // smtp需要鉴权 这个必须是true
                $mail->SMTPAuth = true;
                // 链接qq域名邮箱的服务器地址
                $mail->Host = 'smtp.qq.com';
                // 设置使用ssl加密方式登录鉴权
                $mail->SMTPSecure = 'ssl';
                // 设置ssl连接smtp服务器的远程服务器端口号
                $mail->Port = 465;
                // 设置发送的邮件的编码
                $mail->CharSet = 'UTF-8';
                // 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
                $mail->FromName = '新闻系统管理员';
                // smtp登录的账号 QQ邮箱即可
                $mail->Username = '1740651705@qq.com';
                // smtp登录的密码 使用生成的授权码
                $mail->Password = 'gcfhfiyxbkrshieh';
                // 设置发件人邮箱地址 同登录账号
                $mail->From = '1740651705@qq.com';
                // 邮件正文是否为html编码 注意此处是一个方法
                $mail->isHTML(true);
                // 设置收件人邮箱地址
                $mail->addAddress($result['email']);
                // 添加该邮件的主题
                $mail->Subject = '新闻系统邮件验证码';
                $mail->Body = "<h3>新闻系统邮件验证码: $authcode </h3>";
                // 为该邮件添加附件
                # $mail->addAttachment('./example.pdf');
                // 不查看日志
                $mail->SMTPDebug = 0;
                // 发送邮件 返回状态 发送邮件的时候去数据库判断该邮箱是否存在于数据库并获得 uid
                $status = $mail->send();
                $mail -> smtpClose();
                if ($status) {
                    // 将 uid 验证码 存在数据 方便再次比对
                    echo json_encode([
                        "status"=>200,
                        "msg"=> "邮件发送成功,请注意查收",
                        "data" => $result
                    ]);
                }else {
                    echo json_encode([
                        "status"=>202,
                        "msg"=> "邮件发送失败,请稍后重试"
                    ]);
                }
            }catch (Exception $e) {
                echo json_encode([
                    "status"=>201,
                    "msg"=> "邮件发送失败,请稍后重试"
                ]);
            }
        } else {
            echo json_encode([
                "status" => 203,
                "msg" => "报错请联系管理员重置"
            ]);
        }

    }else {
        echo json_encode([
            "status" => 202,
            "msg" => "暂无数据"
        ]);
    }
?>