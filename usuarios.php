<?php
require_once __DIR__.'/app/seguridad/guardia.php';
require_once __DIR__.'/app/config/conexion.php';
exigirRol('administrador');
$u=Conexion::obtener()->query("SELECT id,nombre,correo,rol,activo,creado_en FROM usuarios ORDER BY id")->fetchAll();
require __DIR__.'/app/vistas/parciales/cabecera.php';require __DIR__.'/app/vistas/parciales/menu.php';
?>
<main class="panel__contenido"><h1>Usuarios (solo administrador)</h1>
<table><thead><tr><th>Nombre</th><th>Correo</th><th>Rol</th><th>Activo</th></tr></thead><tbody>
<?php foreach($u as $x):?><tr><td><?=htmlspecialchars($x['nombre'],ENT_QUOTES,'UTF-8')?></td><td><?=htmlspecialchars($x['correo'],ENT_QUOTES,'UTF-8')?></td><td><?=$x['rol']?></td><td><?=$x['activo']?'Si':'No'?></td></tr><?php endforeach;?>
</tbody></table><p><a href="registro.php">Crear usuario</a></p></main>
<?php require __DIR__.'/app/vistas/parciales/pie.php'; ?>
