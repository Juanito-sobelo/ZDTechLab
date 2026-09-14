# ZD.TechLab v1.0 — Panel gestion tienda tecnologica
## Instalacion (XAMPP)
1. Copiar carpeta `ZDTechLab` a `C:\xampp\htdocs\ZDTechLab`.
2. Encender Apache + MySQL en XAMPP.
3. En phpMyAdmin importar en orden: `sql/estructura.sql`, `sql/datos.sql`, `sql/vistas.sql`.
4. Abrir `http://localhost/ZDTechLab/login.php`.
## Credenciales prueba (clave: Clave.2026*)
- admin@zdtechlab.co — administrador
- vendedor@zdtechlab.co — vendedor
- consultor@zdtechlab.co — consultor (solo tablero+reportes)
## Estructura
/assets/img, /css (tokens, estilos, reporte), /js, /app (config, seguridad, modelos, vistas), /sql, /api
## Paleta (dia 1-2)
Esquema analogo verde (hsl 100-140) + acento naranja complementario. Regla 60-30-10.
Contrastes: texto #15231A 13.4:1 AAA, tenue 6.2:1 AA, marca #1F5E14 sobre blanco 8.9:1 AAA.
## Seguridad
PDO prepares reales, password_hash/verify, CSRF random_bytes+hash_equals, bloqueo 5 intentos 15min, sesiones regenerate_id+HttpOnly+SameSite Strict, guardia+exigirRol, htmlspecialchars en salida, POST para borrar, PRG 303, transaccion en pedidos.
## Pruebas sustentacion
- Buscador `' OR '1'='1` no filtra todo (preparada).
- Usuario `<script>alert(1)</script>` se ve como texto (XSS).
- Consultor abre usuarios.php → 403. Sin sesion dashboard → login.
