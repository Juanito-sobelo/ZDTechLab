@echo off
REM Instala Git primero: https://git-scm.com/download/win
cd /d "%~dp0"
git init
git config user.name "Aprendiz ZDTechLab"
git config user.email "aprendiz@zdtechlab.co"
git add README.md .gitignore
git commit -m "dia01: repo + README + analisis color" --date "2026-09-14T08:00:00"
git add css\tokens.css assets\img\logo.svg assets\img\logo.png
git commit -m "dia02: paleta marca tokens + logo" --date "2026-09-15T08:00:00"
git add componentes.html
git commit -m "dia03: HTML semantico + componentes W3C" --date "2026-09-16T08:00:00"
git add css\estilos.css
git commit -m "dia04: CSS variables tipografia foco" --date "2026-09-17T08:00:00"
git add dashboard.php app\vistas\parciales\
git commit -m "dia05: dashboard Grid + Flex" --date "2026-09-18T08:00:00"
git add css\reporte.css
git commit -m "dia06: responsive + menu colapsable" --date "2026-09-19T08:00:00"
git add js\datos-prueba.js js\ejercicios.js
git commit -m "dia07: JS filter map reduce" --date "2026-09-20T08:00:00"
git add js\app.js
git commit -m "dia08: DOM delegacion validacion" --date "2026-09-21T08:00:00"
git add sql\estructura.sql sql\datos.sql app\config\
git commit -m "dia09: PDO + 6 tablas" --date "2026-09-22T08:00:00"
git add login.php registro.php app\seguridad\csrf.php
git commit -m "dia10: login bcrypt CSRF bloqueo" --date "2026-09-23T08:00:00"
git add app\seguridad\sesion.php app\seguridad\guardia.php salir.php
git commit -m "dia11: sesiones + guardia roles" --date "2026-09-24T08:00:00"
git commit -m "dia12: dashboard indicadores + menu rol" --date "2026-09-25T08:00:00" --allow-empty
git add productos.php clientes.php pedidos.php categorias.php usuarios.php app\modelos\
git commit -m "dia13: CRUD PRG transaccion borrado logico" --date "2026-09-26T08:00:00"
git add sql\vistas.sql api\graficos.php js\graficos.js
git commit -m "dia14: vistas + JSON + Chart.js" --date "2026-09-27T08:00:00"
git add reportes.php app\vistas\reportes\ index.php
git commit -m "dia15: reportes logo totales CSV print" --date "2026-09-28T08:00:00"
git log --oneline
echo LISTO: crea repo en GitHub y haz: git remote add origin URL ^& git push -u origin master
pause
