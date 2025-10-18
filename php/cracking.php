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
        document.addEventListener('DOMContentLoaded', async () => { // async 추가
    const consoleEl = document.getElementById('console');
    const userId = <?php echo json_encode($userId); ?>;
    const crackedPassword = <?php echo json_encode($password); ?>;

    // 잠시 대기하는 헬퍼 함수
    const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));

    // 메시지를 하나씩 출력하는 함수
    const typeMessage = (message) => {
        const p = document.createElement('p');
        p.textContent = message;
        consoleEl.appendChild(p);
        window.scrollTo(0, document.body.scrollHeight);
    };
    
    // 1. 초기 메시지 출력
    const initialMessages = [
        '[*] Initiating penetration test...',
        '[*] Target Acquired: ' + userId,
        '[*] Loading dictionary attack module...',
        '[*] running cracking: qwecaas1^$?!23asx1x@#/123$!%(224_+{:Ll!21',
        '[*] Starting attack...',
    ];

    for (const msg of initialMessages) {
        typeMessage(msg);
        await sleep(500); // 0.5초 대기
    }

    // 2. 크래킹 진행 바 표시
    const progressP = document.createElement('p');
    progressP.style.color = '#fff';
    consoleEl.appendChild(progressP);
    
    const crackingTimeSeconds = 5;
    for (let progress = 0; progress <= 100; progress += 5) {
        const bar = '='.repeat(progress / 5) + '>';
        const spaces = ' '.repeat(20 - (progress / 5));
        progressP.textContent = `[${bar}${spaces}] Cracking in progress... ${progress}%`;
        window.scrollTo(0, document.body.scrollHeight);
        if (progress === 100) {
            progressP.textContent = `[====================>] Cracking in progress... 100%`;
        }
        await sleep(crackingTimeSeconds * 1000 / 20); // 전체 시간에 맞춰 대기
    }

    // 3. 최종 결과 메시지 출력
    const finalMessages = [
        ' ',
        '[+] SUCCESS! Password has been compromised!',
        '------------------------------------------',
        `   Account: ${userId}`,
        `   Password: ${crackedPassword}`,
        '------------------------------------------'
    ];

    for (const msg of finalMessages) {
        const p = document.createElement('p');
        p.textContent = msg;
        if (msg.includes('Password:')) {
            p.className = 'warning';
        }
        consoleEl.appendChild(p);
        window.scrollTo(0, document.body.scrollHeight);
        await sleep(500);
    }

    // 4. 최종 경고 메시지
    const finalWarning = document.createElement('p');
    finalWarning.className = 'blink warning';
    finalWarning.textContent = `[!] 보안 경고 계정 "${userId}" 는 암호 및 계정 정보가 탈취 되었습니다.
    보안 정책에 걸맞는 암호를 생활화 하세요.`;
    consoleEl.appendChild(finalWarning);
});
    </script>
</body>
</html>