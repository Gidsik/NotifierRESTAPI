set time=60
:loop

powershell wget http://notifierrestapi.gidsik-dev.ru/api/testShit/send

ping 127.0.0.1 -n %time% >nul
Goto :loop 