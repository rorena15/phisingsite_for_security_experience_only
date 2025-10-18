<?php
// 세션 시작
session_start();

// 세션에서 값을 가져오고, 없으면 기본값을 사용합니다.
// htmlspecialchars()를 사용해 XSS 공격을 방지합니다.
$userId = isset($_SESSION['cracked_id']) ? htmlspecialchars($_SESSION['cracked_id']) : 'N/A';
$password = isset($_SESSION['cracked_pw']) ? htmlspecialchars($_SESSION['cracked_pw']) : 'N/A';

// 보안을 위해 사용한 세션 변수는 즉시 파기합니다.
unset($_SESSION['cracked_id']);
unset($_SESSION['cracked_pw']);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>패스워드 크래킹</title>
    <style>
        body { background: #000; color: #0f0; font-family: 'Courier New', monospace; padding: 20px; }
        .console { max-width: 800px; margin: 50px auto; }
        .console p { margin: 5px 0; white-space: pre-wrap; }
        .warning { color: #ff0; }
        .blink { animation: blink 1s infinite; }
        @keyframes blink { 50% { opacity: 0; } }
    </style>
</head>
<body>
    <div class="console" id="console"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const consoleEl = document.getElementById('console');

            //  핵심 변경점: PHP 변수를 JavaScript 변수로 가져옵니다.
            const userId = '<?php echo $userId; ?>';
            const crackedPassword = '<?php echo $password; ?>';

            // 동적인 메시지 생성 (이전과 동일)
            const messages = [
                '[*] Initiating penetration test...',
                '[*] Target Acquired: ' + userId,
                '[*] Loading dictionary attack module...',
                '[*] Using wordlist: rockyou.txt (14,344,391 words)',
                '[*] Starting attack... (ETA: 3 seconds)',
                ' ',
                '[+] SUCCESS! Password has been compromised!',
                '------------------------------------------',
                `   Account: ${userId}`,
                `   Password: ${crackedPassword}`,
                '------------------------------------------'
            ];

            let i = 0;
            const interval = setInterval(() => {
                if (i < messages.length) {
                    const p = document.createElement('p');
                    p.textContent = messages[i];
                    if (messages[i].includes('Password:')) {
                        p.className = 'warning';
                    }
                    consoleEl.appendChild(p);
                    window.scrollTo(0, document.body.scrollHeight);
                    i++;
                } else {
                    clearInterval(interval);
                    const finalMsg = document.createElement('p');
                    finalMsg.className = 'blink warning';
                    finalMsg.textContent = `[!] SECURITY ALERT: Account "${userId}" has been breached. Implement a stronger password policy immediately.`;
                    consoleEl.appendChild(finalMsg);
                }
            }, 500);
        });
    </script>
</body>
</html>