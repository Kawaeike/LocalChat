@echo off
taskkill /F /FI "WINDOWTITLE eq Server"
title Server
taskkill /F /IM php.exe
cls
:start
SET version=3
IF %version% == 2 (
	C:\xampp\php\php.exe C:\xampp\htdocs\WS\server.php
)
IF %version% == 3 (
	C:\xampp\php\php.exe C:\xampp\htdocs\WS\main.php
)
IF %version% == all (
	C:\xampp\php\php.exe C:\xampp\htdocs\WS\main.php
)


echo \
echo "------ Server stopped --------"
timeout /T 10  > nul
echo "  ||                      ||  "
echo "  ||                      ||  "
echo "  \/                      \/  "
echo "------ Server restarting -----"
echo \

goto start;