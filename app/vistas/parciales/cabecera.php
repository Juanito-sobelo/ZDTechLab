<?php
require_once __DIR__.'/../../seguridad/guardia.php';
$u=$_SESSION['usuario'];
?><!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>ZD.TechLab</title><link rel="stylesheet" href="<?=BASE_URL?>css/tokens.css"><link rel="stylesheet" href="<?=BASE_URL?>css/estilos.css"></head>
<body><div class="panel">
<header class="panel__barra">
<div style="display:flex;align-items:center;gap:.7rem">
<button class="boton-menu boton-mini" aria-expanded="false" aria-controls="menu-lateral">☰</button>
<img src="<?=BASE_URL?>assets/img/logo.svg" alt="Logo de ZD.TechLab" width="36" height="36">
<strong>ZD.TechLab</strong></div>
<div><span class="t5"><?=htmlspecialchars($u['nombre'],ENT_QUOTES,'UTF-8')?> (<?=htmlspecialchars($u['rol'],ENT_QUOTES,'UTF-8')?>)</span></div>
</header>
