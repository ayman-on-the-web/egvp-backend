@echo off
REM PHP Version Launcher
REM Usage: Just call this batch file with any parameters you would normally pass to PHP
REM Example: php74.bat -v
REM Example: php74.bat myscript.php

REM Path to the specific PHP executable
SET PHP_PATH="C:\xampp_8.2\php\php.exe .\composer.phar"

REM Redirect all parameters to the PHP executable
C:\xampp_8.2\php\php.exe .\composer.phar %*

REM If you want to pause the console after execution, uncomment the line below
REM pause