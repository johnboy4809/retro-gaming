@echo off
echo Starting MySQL Server with custom data directory...
start /b "MySQL Server" "C:\Program Files\MySQL\MySQL Server 8.4\bin\mysqld.exe" --datadir="C:\Users\jfiel\mysql_data" --console
echo MySQL server started in background.
