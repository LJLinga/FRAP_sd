@echo off 
    net start MySQL80
    TIMEOUT 5
    net start Apache2.4
    TIMEOUT 5
    cd "C:\Program Files (x86)\Google\Chrome\Application"
    start chrome.exe 127.0.0.1\FRAP_sd
    exit
