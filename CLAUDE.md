# CLAUDE.md

此文件为 Claude Code (claude.ai/code) 在此仓库中工作时提供指导。

## 项目概述

这是一个 PHP 原生开发的**新闻管理系统靶场**（漏洞演练平台），采用前后端分离架构，用于 Web 安全测试与渗透训练。系统覆盖 SQL注入、XSS、越权、文件上传、文件下载、文件包含、逻辑漏洞等 7 大类 Web 安全漏洞。赛博朋克风格 UI。

**注意：此项目刻意包含大量安全漏洞，仅供安全教学使用，严禁用于生产环境。**

## 技术栈

- **后端**: 纯 PHP（无框架），MySQL 数据库，22 个独立 API 端点
- **前端**: 22 个 HTML 页面，jQuery 3.7.1 + 赛博朋克 CSS 主题
- **邮件**: PHPMailer（QQ邮箱 SMTP）
- **环境要求**: PHP 7.x+，MySQL 5.7+，Apache/Nginx

## 快速开始

1. 导入数据库: `mysql -u root -p < news.sql`
2. 修改 `api/tools/database.config.php` 中数据库连接参数
3. 后端 API 部署到 `api/` 目录（默认 8082 端口），前端部署到 `web/` 目录（默认 8081 端口）
4. 修改 `web/js/config.js` 中的 `API_BASE` 为后端实际地址
5. PHP 需开启 `gd` 扩展（验证码）

## 数据库设计（6 张表）

| 表名 | 说明 | 关键字段 |
|------|------|----------|
| `user` | 用户表 | id, username, password(明文), email, role(admin/user), status(true/false) |
| `new` | 新闻表 | id, title, uid, context, time, newImg |
| `review` | 评论表 | id, context, time, uid, nid |
| `authcode` | 邮件验证码表 | id, email, authcode(4位数字) |
| `log` | 操作日志表 | id, uid, username, action, time |
| `message` | 留言板表 | id, name, content, time |

默认管理员: `admin / 666888`

## 漏洞分类索引

### SQL 注入（全端点覆盖）
- `api/login.php` — 登录绕过，万能密码 `' OR '1'='1`
- `api/searchNews.php` — 联合查询注入演示，回显完整 SQL
- `api/newList.php` — 搜索注入，响应泄露 SQL 语句
- `api/userList.php` — LIKE 注入
- 所有其他 CRUD 端点 — 字符串拼接 SQL，无参数化查询

### XSS（存储型）
- `api/sendMessage.php` + `web/messages.html` — 留言板，无过滤入库 + innerHTML 渲染
- `web/news_detail.html` — 新闻内容 innerHTML 渲染，可嵌入 `<script>`
- `web/index.html` — 新闻标题 innerHTML 渲染

### 越权漏洞
- `api/myInfo.php` — 水平越权：改 uid 参数查任意用户信息
- `api/updateProfile.php` — 水平越权：改 uid 修改任意用户邮箱
- `api/updateUserById.php` — 垂直越权：无权限校验，可改任意用户 role=admin
- `api/addUser.php` — 垂直越权：无权限校验可创建 admin 账户
- `api/resetPassword.php` — 垂直越权：知道 uid 即可重置任意用户密码

### 文件漏洞
- `api/upload.php` — 任意文件上传，无类型校验
- `api/download.php` — 路径遍历下载，`../` 读取任意文件
- `api/template.php` — LFI 文件包含，`page` 参数直接 include
- `api/deleteFileByName.php` — 路径遍历删除，`../` 删除任意文件

### 逻辑漏洞
- `api/login.php` — 万能验证码 `0000`
- `api/register.php` — 验证码判断条件写反（`!=` 应为 `==`）
- `api/resetPassword.php` — 重置密码无需旧密码验证

### 其他
- 硬编码数据库密码 (`api/tools/database.config.php`)
- 硬编码 SMTP 凭据 (`api/submitEmail.php`)
- 明文存储密码 (`user` 表)
- 无 CSRF 防护
- CORS 仅限制 localhost:8081
- 仅 `addNew.php` 调用 `authorization()`，其余 21 个接口无权限校验

## 架构要点

### API 模式
所有接口: `include DB.php + cors.php` → `@$_GET`/`@$_POST` 获取参数 → 字符串拼接 SQL → `DB` 类方法 → `json_encode` 返回

### 前端模式
- 统一 CSS: `web/css/cyberpunk.css`（赛博朋克主题，霓虹色系，等宽字体，暗色UI）
- 公共 JS: `web/js/utils.js`（cookieToJson, getNavHtml, deleteCookies, copyLink）
- API 配置: `web/js/config.js`（`API_BASE` 变量）
- 认证: Cookie 存储 uid/username/role，前端根据 role 显示不同导航

### 页面导航
- 访客: 首页 / 漏洞中心 / 登录
- 普通用户: 首页 / 漏洞中心 / 留言板 / 个人信息 / 退出
- 管理员: 首页 / 漏洞中心 / 新闻管理 / 文件管理 / 用户管理 / 评论管理 / 系统日志 / 退出
