@echo off
taskkill /F /FI "WINDOWTITLE eq Server"
title Server
taskkill /F /IM php.exe
cls
:start

C:\xampp\php\php.exe .\main.php



echo \
echo "------ Server stopped --------"
timeout /T 10  > nul
echo "  ||                      ||  "
echo "  ||                      ||  "
echo "  \/                      \/  "
echo "------ Server restarting -----"
echo \

goto start;