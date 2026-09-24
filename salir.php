<?php
require_once __DIR__.'/app/seguridad/sesion.php';
cerrarSesion(); header('Location: login.php'); exit;
