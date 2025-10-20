@echo off
echo =========================================
echo  Google Chrome Cache Cleaner
echo =========================================
echo.
echo Chrome 브라우저가 완전히 종료되었는지 확인해주세요.
echo 계속하려면 아무 키나 누르십시오...
pause >nul
echo.

echo Chrome 캐시 폴더를 삭제하는 중입니다...

:: 기본 크롬 캐시 경로를 지정하고 해당 폴더로 이동합니다.
cd %localappdata%\Google\Chrome\User Data\Default\

:: Cache 폴더가 존재하는지 확인하고 삭제합니다.
if exist "Cache" (
    rd /s /q "Cache"
    echo Cache 폴더 삭제 완료!
) else (
    echo Cache 폴더를 찾을 수 없습니다. 이미 삭제되었을 수 있습니다.
)

echo.
echo 작업이 완료되었습니다.
pause