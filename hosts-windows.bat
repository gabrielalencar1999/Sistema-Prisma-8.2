@ECHO OFF



@SET REG_HOSTS_KEY=HKLM\SYSTEM\CurrentControlSet\services\Tcpip\Parameters

@SET REG_HOSTS_PARAM=DataBasePath

@SET HOSTS_PATH=%WINDIR%\System32\drivers\etc

@SET HOSTS_URL1=127.0.0.1	prisma.local



@FOR /F "tokens=3" %%a IN ('REG QUERY "%REG_HOSTS_KEY%" /v %REG_HOSTS_PARAM%') DO IF "%%a"=="%REG_HOSTS_PARAM%" SET HOSTS_PATH=%%c

@ECHO Temporario...>> "%HOSTS_PATH%\hosts_tmp"

@IF EXIST "%HOSTS_PATH%\hosts_tmp" (IF EXIST "%HOSTS_PATH%\hosts_tmp" DEL "%HOSTS_PATH%\hosts_tmp" /F /Q

@IF EXIST "%HOSTS_PATH%\hosts" ATTRIB -R "%HOSTS_PATH%\hosts"

@CMD /c TYPE "%HOSTS_PATH%\hosts" | FINDSTR /I /V "%HOSTS_URL1%"> "%HOSTS_PATH%\hosts_tmp"
@CMD /c TYPE "%HOSTS_PATH%\hosts" | FINDSTR /I /V "%HOSTS_URL2%"> "%HOSTS_PATH%\hosts_tmp"

@ECHO %HOSTS_URL1%>> "%HOSTS_PATH%\hosts_tmp"
@ECHO %HOSTS_URL2%>> "%HOSTS_PATH%\hosts_tmp"

@IF EXIST "%HOSTS_PATH%\hosts" DEL "%HOSTS_PATH%\hosts" /F /Q

@REN "%HOSTS_PATH%\hosts_tmp" "hosts") ELSE (GOTO ERROR)



@CLS



@COLOR 06

@ECHO -------------------------------------------------------------------------------

@ECHO Entradas prisma.local adicionada com sucesso ao arquivo hosts

@ECHO -------------------------------------------------------------------------------

@PING -n 3 127.0.0.1 > nul

@EXIT



:ERROR

@CLS

@COLOR 06

@ECHO ----------------------------------------------------------------------------------------------

@ECHO Sem permissao para executar o procedimento, por gentileza execute o script como administrador

@ECHO ----------------------------------------------------------------------------------------------

@PAUSE