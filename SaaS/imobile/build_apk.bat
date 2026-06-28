@echo off
cd /d "C:\xampp\htdocs\projet_SAAS\projet-Saas\SaaS\imobile"
set GRADLE_OPTS=-Xmx512m -XX:MaxMetaspaceSize=256m
call flutter build apk --no-pub
