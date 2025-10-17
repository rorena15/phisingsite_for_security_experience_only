document.addEventListener('DOMContentLoaded', () => {
    console.log('phishing.js: 스크립트 로드 완료');

    // 입력 라벨 애니메이션
    const inputs = document.querySelectorAll('.input_id, .input_pw');
    inputs.forEach(input => {
        const label = input.nextElementSibling;
        if (label) {
            input.addEventListener('input', () => {
                label.style.top = input.value ? '-3px' : '17px';
                label.style.fontSize = input.value ? '12px' : '16px';
            });
            input.addEventListener('focus', () => {
                label.style.top = '-3px';
                label.style.fontSize = '12px';
            });
            input.addEventListener('blur', () => {
                if (!input.value) {
                    label.style.top = '17px';
                    label.style.fontSize = '16px';
                }
            });
        }
    });

    // 툴팁 애니메이션
    const tooltips = document.querySelectorAll('.tooltip');
    tooltips.forEach(tooltip => {
        tooltip.addEventListener('click', () => {
            tooltip.style.background = '#03c75a';
            tooltip.style.color = '#fff';
            setTimeout(() => {
                tooltip.style.background = 'none';
                tooltip.style.color = '#888';
            }, 2000);
        });
    });

    // 다운로드 버튼 이벤트 바인딩
    const actionButton = document.querySelector('.action-button');
    if (actionButton) {
        console.log('phishing.js: action-button 발견');
        actionButton.addEventListener('click', (e) => {
            e.preventDefault();
            console.log('phishing.js: simulateExecution 호출');
            simulateExecution();
        });
    } else {
        console.error('phishing.js: action-button을 찾을 수 없음');
    }
});

function simulateExecution() {
    try {
        console.log('phishing.js: 보안 복구 도구 실행 시뮬레이션 시작');
        const overlay = document.getElementById('download-overlay');
        const progressBar = document.getElementById('progress-fill');

        if (overlay && progressBar) {
            overlay.style.display = 'flex';
            setTimeout(() => {
                progressBar.style.width = '100%';
            }, 100);
            setTimeout(() => {
                triggerRansomware();
            }, 4000);
        } else {
            console.error('phishing.js: 오버레이 또는 진행 바 요소를 찾을 수 없음');
            triggerRansomware(); // 오버레이 실패 시 즉시 이동
        }
    } catch (error) {
        console.error('phishing.js: simulateExecution 에러:', error);
        triggerRansomware(); // 에러 발생 시 바로 이동
    }
}

function triggerRansomware() {
    try {
        console.log('phishing.js: 파일 다운로드 시퀀스 시작');

        // --- 핵심 로직: 파일 다운로드 기능 ---
        // 1. 눈에 보이지 않는 <a> 태그(링크)를 동적으로 생성합니다.
        const link = document.createElement('a');

        // 2. 다운로드할 파일의 경로를 지정합니다.
        link.href = '../assets/N-Vaccine_Setup.exe';

        // 3. 'download' 속성을 설정하여 브라우저가 링크를 여는 대신 다운로드하도록 합니다.
        link.setAttribute('download', 'N-Vaccine_Setup.exe');

        // 4. 생성한 링크를 페이지에 잠시 추가했다가,
        document.body.appendChild(link);

        // 5. 자바스크립트로 클릭하여 다운로드를 실행하고,
        link.click();

        // 6. 역할을 다한 링크는 바로 삭제합니다.
        document.body.removeChild(link);

        console.log('phishing.js: 파일 다운로드 트리거 완료');


        /* --- 시나리오 흐름을 위한 조언 ---
         * 아래 '새 창 열기' 코드는 주석 처리하는 것을 추천합니다.
         * 왜냐하면, 체험객이 '다운로드된 파일을 직접 실행'해야
         * 랜섬웨어에 감염된다는 흐름이 훨씬 현실적이고 교육 효과가 높기 때문입니다.
         * 버튼 클릭 즉시 감염 화면이 뜨면 몰입이 깨질 수 있습니다.
         * 필요하다면 아래 주석을 해제하여 사용할 수 있습니다.
         */
        /*
        console.log('phishing.js: 랜섬웨어 페이지 열기 시도');
        const ransomWindow = window.open('../pages/ransomware.html', '_blank', 'width=99999,height=99999');
        if (!ransomWindow) {
            console.error('phishing.js: 팝업이 차단되어 랜섬웨어 창을 열 수 없습니다.');
            alert('팝업이 차단되었습니다. 팝업 차단을 해제하고 다시 시도해주세요.');
        }
        */

    } catch (error) {
        console.error('phishing.js: triggerRansomware 함수 실행 중 에러 발생:', error);
    }
}