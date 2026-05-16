# 新闻管理系统靶场

> PHP 原生 Web 安全漏洞演练平台 | 网络安全渗透测试训练

一个**刻意包含大量安全漏洞**的新闻管理系统，覆盖 SQL 注入、XSS、越权、文件上传、文件下载、文件包含、逻辑漏洞等 7 大类 Web 安全漏洞。专为安全教学与渗透测试训练设计。

---

## ⚠️ 安全警告

**此项目刻意包含大量安全漏洞，仅供安全教学和授权渗透测试使用。**

- 严禁部署在生产环境
- 严禁暴露在公网
- 仅可在隔离的本地/实验环境中运行
- 使用本项目的漏洞知识攻击未授权系统属于违法行为

---

## 系统预览

前端采用温暖编辑风格设计，支持三种角色视图：

| 角色 | 可用功能 |
|------|----------|
| 访客 | 首页浏览、漏洞中心、登录注册、忘记密码 |
| 普通用户 | 访客功能 + 留言板、个人信息、退出登录 |
| 管理员 | 用户功能 + 新闻管理、文件管理、用户管理、评论管理、系统日志 |

---

## 技术栈

| 层级 | 技术 | 说明 |
|------|------|------|
| 后端 | PHP 7.x+ | 纯原生，无框架 |
| 数据库 | MySQL 5.7+ | 6 张表，无参数化查询 |
| 前端 | HTML + CSS + jQuery 3.7.1 | 22 个页面，温暖编辑风格 |
| 邮件 | PHPMailer | QQ 邮箱 SMTP |
| 验证码 | PHP GD 扩展 | `captcha.php` 动态生成 |

---

## 快速开始

### 环境要求

- PHP 7.0+（需开启 `gd` 扩展）
- MySQL 5.7+
- Apache / Nginx

### 部署步骤

```bash
# 1. 克隆仓库
git clone <repo-url> && cd news

# 2. 导入数据库
mysql -u root -p < news.sql

# 3. 配置数据库连接 — 修改 api/tools/database.config.php
# define('DB_HOST', 'localhost');
# define('DB_USER', 'root');
# define('DB_PASS', 'your_password');
# define('DB_NAME', 'news');

# 4. 部署 API（默认 8082 端口）
# 将 api/ 目录配置到 Apache/Nginx，或以 PHP 内建服务器运行：
cd api && php -S 0.0.0.0:8082

# 5. 部署前端（默认 8081 端口）
cd web && php -S 0.0.0.0:8081

# 6. 配置前端 API 地址 — 修改 web/js/config.js
# var API_BASE = "http://localhost:8082";
```

### 默认账户

| 角色 | 用户名 | 密码 |
|------|--------|------|
| 管理员 | `admin` | `666888` |

---

## 数据库设计

| 表名 | 说明 | 关键字段 |
|------|------|----------|
| `user` | 用户表 | id, username, password(明文存储), email, role(admin/user), status(true/false) |
| `new` | 新闻表 | id, title, uid, context, time, newImg |
| `review` | 评论表 | id, context, time, uid, nid |
| `authcode` | 邮件验证码表 | id, email, authcode(4位数字) |
| `log` | 操作日志表 | id, uid, username, action, time |
| `message` | 留言板表 | id, name, content, time |

---

## 漏洞分类索引

### SQL 注入

全端点字符串拼接 SQL，无参数化查询。

| 端点 | 漏洞类型 | 演示 Payload |
|------|----------|-------------|
| `api/login.php` | 登录绕过 | `' OR '1'='1` |
| `api/searchNews.php` | UNION 联合查询 | `' UNION SELECT 1,2,3,4,5,6--` |
| `api/newList.php` | 搜索注入（SQL 泄露） | 任意关键词触发 SQL 回显 |
| `api/userList.php` | LIKE 注入 | `admin%' OR '1'='1` |

**进阶 Payload（searchNews.php）：**
```sql
# 爆数据库版本
' UNION SELECT 1,@@version,3,4,5,6--

# 爆用户表数据
' UNION SELECT id,username,password,email,role,status FROM user--

# 爆所有表名
' AND 1=2 UNION SELECT 1,group_concat(table_name),3,4,5,6
FROM information_schema.tables WHERE table_schema=database()--
```

---

### XSS（跨站脚本）

存储型 XSS，内容无过滤入库 + `innerHTML` 渲染。

| 页面 | 注入点 | 演示 Payload |
|------|--------|-------------|
| `web/messages.html` | 留言内容 | `<script>alert('XSS')</script>` |
| `web/news_detail.html` | 新闻内容 | `<img src=x onerror=alert(1)>` |
| `web/index.html` | 新闻标题 | `<svg onload=alert(1)>` |

---

### 越权漏洞（IDOR）

| 端点 | 漏洞类型 | 演示方法 |
|------|----------|----------|
| `api/myInfo.php` | 水平越权 | 修改 `uid` 参数查看任意用户信息 |
| `api/updateProfile.php` | 水平越权 | 修改 `uid` 参数修改任意用户邮箱 |
| `api/updateUserById.php` | 垂直越权 | 无权限校验，可改任意用户 `role=admin` |
| `api/addUser.php` | 垂直越权 | 无权限校验，可创建 admin 账户 |
| `api/resetPassword.php` | 垂直越权 | 知道 `uid` 即可重置任意用户密码 |

---

### 文件漏洞

| 端点 | 漏洞类型 | 演示方法 |
|------|----------|----------|
| `api/upload.php` | 任意文件上传 | 上传 `.php` Webshell，无类型校验 |
| `api/download.php` | 路径遍历 | `?filename=../tools/database.config.php` |
| `api/template.php` | LFI 文件包含 | `?page=../tools/database.config` |
| `api/deleteFileByName.php` | 路径遍历删除 | `?filename=../somefile.txt` |

**文件读取常用路径：**
```
../tools/database.config.php    # 数据库配置
../tools/DB.php                 # DB 类源码
../../news.sql                  # 数据库 SQL 文件
../../../etc/passwd             # Linux 系统文件
../../../Windows/win.ini        # Windows 系统文件
```

---

### 逻辑漏洞

| 端点 | 漏洞 | 说明 |
|------|------|------|
| `api/login.php` | 万能验证码 | 输入 `0000` 绕过验证码校验 |
| `api/register.php` | 验证码判断反写 | `!=` 应为 `==`，输错验证码反而能注册 |
| `api/resetPassword.php` | 无需旧密码 | 直接重置密码，无旧密码校验 |

---

### 其他安全隐患

- 数据库密码硬编码于 `api/tools/database.config.php`
- SMTP 凭据硬编码于 `api/submitEmail.php`
- 用户密码明文存储于 `user` 表
- 无 CSRF 防护
- CORS 仅限制 `localhost:8081`，可被绕过
- 仅 `addNew.php` 调用授权检查，其余 21 个接口无权限校验

---

## 架构

### 目录结构

```
news/
├── api/                        # PHP 后端 API（22 个端点）
│   ├── tools/
│   │   ├── DB.php              # 数据库操作类
│   │   ├── cors.php            # CORS 配置
│   │   ├── authorization.php   # 授权检查（仅 addNew.php 调用）
│   │   └── database.config.php # 数据库连接配置
│   ├── PHPMailer/              # 第三方邮件库
│   ├── upload/                 # 上传文件目录
│   ├── login.php               # 登录接口
│   ├── register.php            # 注册接口
│   ├── newList.php             # 新闻列表接口
│   ├── searchNews.php          # 新闻搜索接口（SQL注入演示）
│   ├── userList.php            # 用户列表接口
│   ├── myInfo.php              # 个人信息接口（水平越权）
│   ├── updateProfile.php       # 更新个人信息接口（水平越权）
│   ├── updateUserById.php      # 更新用户接口（垂直越权）
│   ├── addUser.php             # 添加用户接口（垂直越权）
│   ├── deleteUserById.php      # 删除用户接口
│   ├── resetPassword.php       # 重置密码接口（逻辑漏洞+垂直越权）
│   ├── upload.php              # 文件上传接口（任意上传）
│   ├── download.php            # 文件下载接口（路径遍历）
│   ├── template.php            # 模板包含接口（LFI）
│   ├── deleteFileByName.php    # 文件删除接口（路径遍历）
│   ├── sendMessage.php         # 发送留言接口（XSS）
│   ├── messageList.php         # 留言列表接口
│   ├── addNew.php              # 添加新闻接口
│   ├── updateNewById.php       # 更新新闻接口
│   ├── deleteNewById.php       # 删除新闻接口
│   ├── buildReview.php         # 发布评论接口
│   ├── reviewList.php          # 评论列表接口
│   ├── deleteReviewById.php    # 删除评论接口
│   ├── logList.php             # 日志列表接口
│   ├── addLog.php              # 日志记录接口
│   ├── logout.php              # 退出登录接口
│   ├── submitEmail.php         # 发送邮件接口
│   ├── verifyCode.php          # 验证码校验接口
│   ├── fileList.php            # 文件列表接口
│   └── captcha.php             # 验证码生成（需 GD 扩展）
├── web/                        # 前端页面（22 个 HTML）
│   ├── css/
│   │   └── cyberpunk.css       # 温暖编辑风格主题
│   ├── js/
│   │   ├── jquery-3.7.1.min.js # jQuery 库
│   │   ├── config.js           # API 地址配置
│   │   └── utils.js            # 公共工具函数
│   ├── img/
│   │   └── captcha.png         # 验证码占位图
│   ├── index.html              # 首页（新闻列表+搜索）
│   ├── login.html              # 登录页
│   ├── register.html           # 注册页
│   ├── forgot-password.html    # 忘记密码（3步向导）
│   ├── logout.html             # 退出确认
│   ├── vulns.html              # 漏洞演示中心
│   ├── news.html               # 新闻管理（管理员）
│   ├── build.html              # 发布新闻
│   ├── edit_news.html          # 编辑新闻
│   ├── news_detail.html        # 新闻详情+评论
│   ├── users.html              # 用户管理（管理员）
│   ├── add_user.html           # 添加用户
│   ├── edit_user.html          # 编辑用户
│   ├── reset_password.html     # 重置密码
│   ├── profile.html            # 个人信息（越权演示）
│   ├── files.html              # 文件管理
│   ├── download.html           # 文件下载（路径遍历演示）
│   ├── template_demo.html      # 文件包含（LFI演示）
│   ├── search_demo.html        # SQL注入演示
│   ├── messages.html           # 留言板（XSS演示）
│   ├── reviews.html            # 评论管理（管理员）
│   └── log.html                # 系统日志（管理员）
├── news.sql                    # 数据库初始化脚本
├── CLAUDE.md                   # Claude Code 项目指南
└── README.md                   # 本文件
```

### API 通用模式

```
include DB.php + cors.php → @$_GET/@$_POST 获取参数
→ 字符串拼接 SQL → DB 类执行 → json_encode 返回
```

### 前端认证机制

Cookie 存储 `uid` / `username` / `role`，前端根据 `role` 动态渲染导航菜单。

---

## 学习路径建议

1. **新手入门**：浏览 `vulns.html` 漏洞中心，按分类逐个体验
2. **SQL 注入**：从 `search_demo.html` 开始，观察 SQL 回显，逐步尝试 UNION 注入爆库
3. **XSS**：在 `messages.html` 提交脚本标签，观察存储型 XSS 效果
4. **越权**：在 `profile.html` 修改 URL 参数 `?uid=` 查看其他用户信息
5. **文件漏洞**：在 `download.html` 尝试 `../` 路径遍历读取配置文件
6. **综合渗透**：结合多个漏洞，尝试从访客提权到管理员

---

## License

本项目仅用于安全教学和授权渗透测试训练。使用者需自行承担所有风险和责任。

---

*Made for Security Education | 安全教学专用*
