@echo off
::set your PHP Path
SET PHPpath=C:\xampp\php\

taskkill /F /FI "WINDOWTITLE eq Server"
title Server
taskkill /F /IM php.exe
cls
:start

%PHPpath%php.exe .\main.php



echo \
echo "------ Server stopped --------"
timeout /T 10  > nul
echo "  ||                      ||  "
echo "  ||                      ||  "
echo "  \/                      \/  "
echo "------ Server restarting -----"
echo \

goto start;