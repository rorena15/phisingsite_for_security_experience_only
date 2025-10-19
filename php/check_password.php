<!-- <?php
// session_start();

// $password = isset($_POST['pw']) ? trim($_POST['pw']) : '';
// $session_id = session_id();
// $is_weak = false; // 취약한 비밀번호인지 여부를 저장할 변수

// // 대용량 파일을 한 줄씩 읽기 위해 파일 핸들을 엽니다.
// $password_file = 'rockyou.txt';
// $handle = @fopen($password_file, "r");

// if ($handle) {
//     // 파일의 끝에 도달할 때까지 한 줄씩 읽는 루프를 실행합니다.
//     while (($line = fgets($handle)) !== false) {
//         // 읽어온 한 줄에서 앞뒤 공백 및 개행 문자를 제거합니다.
//         $weak_password = trim($line);
        
//         // 입력된 비밀번호와 파일에서 읽은 비밀번호가 일치하는지 확인합니다.
//         if ($password === $weak_password) {
//             $is_weak = true; // 일치하면 $is_weak를 true로 설정
//             break; // 더 이상 검사할 필요가 없으므로 루프를 즉시 종료합니다.
//         }
//     }
//     // 파일 사용이 끝나면 핸들을 닫아줍니다.
//     fclose($handle);
// }

// if ($is_weak) {
//     $logLine = date('Y-m-d H:i:s') . " - Session: {$session_id} - Weak Password Detected: {$password}\n";
//     file_put_contents(dirname(__FILE__) . '../log.txt', $logLine, FILE_APPEND | LOCK_EX);
//     echo json_encode(['weak' => true]);
// } else {
//     echo json_encode(['weak' => false]);
// }
?> -->
<!-- 더이상 사용 안하는 구 파일-->