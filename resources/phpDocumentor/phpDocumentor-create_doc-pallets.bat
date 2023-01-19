REM @echo off

SET MY_PATH=%USERPROFILE%\OneDrive\Dokumenty\Prog\WWW\XAMPP\proj

C:\work\xampp\php\php.exe "%MY_PATH%\resources\phpDocumentor\phpDocumentor.phar" run -d "%MY_PATH%" -t "%MY_PATH%\resources\doc"

pause