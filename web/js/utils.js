/**
 * 新闻管理系统 — 公共工具函数
 * 统一管理 cookieToJson、导航栏生成、Cookie 清理等功能
 * 避免在多页面中重复定义
 */

/** 将 document.cookie 字符串解析为 JSON 对象 */
function cookieToJson(cookieString) {
    if (!cookieString || typeof cookieString !== 'string') {
        return {};
    }
    var cookies = cookieString.split(';');
    var result = {};
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        if (!cookie.trim()) { continue; }
        var equalsIndex = cookie.indexOf('=');
        if (equalsIndex === -1) { continue; }
        var name = cookie.substring(0, equalsIndex).trim();
        var value = cookie.substring(equalsIndex + 1).trim();
        try { value = decodeURIComponent(value); } catch (e) {}
        result[name] = value;
    }
    return result;
}

/** 根据用户角色生成导航栏 HTML */
function getNavHtml(cookie) {
    if (!cookie || !cookie.uid || cookie.uid == 0) {
        // 访客
        return '<a href="index.html">首页</a>' +
               '<a href="vulns.html">漏洞中心</a>' +
               '<a href="login.html">登录</a>';
    } else if (cookie.role == 'admin') {
        // 管理员
        return '<a href="index.html">首页</a>' +
               '<a href="vulns.html">漏洞中心</a>' +
               '<a href="news.html">新闻管理</a>' +
               '<a href="files.html">文件管理</a>' +
               '<a href="users.html">用户管理</a>' +
               '<a href="reviews.html">评论管理</a>' +
               '<a href="log.html">系统日志</a>' +
               '<a href="logout.html">退出登录</a>';
    } else {
        // 普通用户
        return '<a href="index.html">首页</a>' +
               '<a href="vulns.html">漏洞中心</a>' +
               '<a href="messages.html">留言板</a>' +
               '<a href="profile.html">个人信息</a>' +
               '<a href="logout.html">退出登录</a>';
    }
}

/** 清空指定名称的 Cookie */
function deleteCookies(names) {
    var expireDate = 'Thu, 01 Jan 1970 00:00:00 UTC';
    for (var i = 0; i < names.length; i++) {
        document.cookie = names[i] + '=; expires=' + expireDate + '; path=/';
    }
}

/** 复制文本到剪贴板 */
function copyLink(link) {
    var textArea = document.createElement('textarea');
    textArea.value = link;
    textArea.style.position = 'fixed';
    textArea.style.left = '-9999px';
    textArea.style.opacity = '0';
    document.body.appendChild(textArea);
    textArea.select();
    textArea.setSelectionRange(0, 99999);
    document.execCommand('copy');
    document.body.removeChild(textArea);
    showToast('复制成功！', 'success');
}

/** 页面增强：粒子背景、滚动进度条、返回顶部、导航阴影 */
(function initUIEnhancements() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setup);
    } else {
        setup();
    }

    function setup() {
        // 粒子背景容器
        var particles = document.createElement('div');
        particles.className = 'floating-particles';
        var dots = '';
        for (var i = 1; i <= 8; i++) {
            dots += '<div class="particle"></div>';
        }
        particles.innerHTML = dots;
        document.body.appendChild(particles);

        // 滚动进度条
        var progress = document.createElement('div');
        progress.className = 'scroll-progress';
        document.body.appendChild(progress);

        // 回到顶部按钮
        var btn = document.createElement('button');
        btn.className = 'back-to-top';
        btn.innerHTML = '&#8593;';
        btn.title = '回到顶部';
        document.body.appendChild(btn);

        // 滚动事件
        var ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(function() {
                    var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    var docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                    var scrollPercent = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
                    progress.style.width = scrollPercent + '%';

                    if (scrollTop > 400) {
                        btn.classList.add('visible');
                    } else {
                        btn.classList.remove('visible');
                    }

                    // 导航阴影
                    var nav = document.querySelector('nav');
                    if (nav) {
                        if (scrollTop > 10) {
                            nav.classList.add('scrolled');
                        } else {
                            nav.classList.remove('scrolled');
                        }
                    }
                    ticking = false;
                });
                ticking = true;
            }
        });

        // 回到顶部点击
        btn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
})();

/** Toast 消息提示（非阻塞，自动消失） */
function showToast(msg, type) {
    var toast = document.createElement('div');
    toast.className = 'toast ' + (type || 'success');
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(function() {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 3500);
}

/** 覆写原生 alert — 使用自定义模态弹窗 */
(function overrideAlert() {
    var _originalAlert = window.alert;

    window.alert = function(message) {
        // 如果已有弹窗则先移除
        var existing = document.querySelector('.custom-alert-overlay');
        if (existing) { existing.parentNode.removeChild(existing); }

        var overlay = document.createElement('div');
        overlay.className = 'custom-alert-overlay';

        var dialog = document.createElement('div');
        dialog.className = 'custom-alert-dialog';

        var icon = document.createElement('div');
        icon.className = 'custom-alert-icon';
        icon.innerHTML = '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>';

        var title = document.createElement('div');
        title.className = 'custom-alert-title';
        title.textContent = '提示';

        var body = document.createElement('div');
        body.className = 'custom-alert-body';
        body.textContent = String(message || '');

        var btn = document.createElement('button');
        btn.className = 'custom-alert-btn';
        btn.textContent = '确定';

        dialog.appendChild(icon);
        dialog.appendChild(title);
        dialog.appendChild(body);
        dialog.appendChild(btn);
        overlay.appendChild(dialog);
        document.body.appendChild(overlay);

        function close() {
            overlay.classList.add('closing');
            setTimeout(function() {
                if (overlay.parentNode) {
                    overlay.parentNode.removeChild(overlay);
                }
            }, 250);
        }

        btn.addEventListener('click', close);
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) { close(); }
        });
        document.addEventListener('keydown', function onEsc(e) {
            if (e.key === 'Escape') {
                close();
                document.removeEventListener('keydown', onEsc);
            }
        });

        // 入场动画触发
        requestAnimationFrame(function() {
            overlay.classList.add('visible');
        });
    };
})();
