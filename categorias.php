<?php
require_once __DIR__.'/app/seguridad/guardia.php';
require_once __DIR__.'/app/seguridad/csrf.php';
require_once __DIR__.'/app/config/conexion.php';
exigirRol('administrador','vendedor');
$pdo=Conexion::obtener();
if($_SERVER['REQUEST_METHOD']==='POST'&&validarCsrf($_POST['csrf']??null)){
$n=trim($_POST['nombre']??'');
if(mb_strlen($n)>=3){try{$pdo->prepare("INSERT INTO categorias(nombre,descripcion) VALUES(:n,:d)")->execute([':n'=>$n,':d'=>trim($_POST['descripcion']??'')]);}catch(Throwable $e){}}}
$cats=$pdo->query("SELECT * FROM categorias ORDER BY nombre")->fetchAll();
require __DIR__.'/app/vistas/parciales/cabecera.php';require __DIR__.'/app/vistas/parciales/menu.php';
?>
<main class="panel__contenido"><h1>Categorias</h1>
<table><thead><tr><th>Nombre</th><th>Descripcion</th></tr></thead><tbody>
<?php foreach($cats as $c):?><tr><td><?=htmlspecialchars($c['nombre'],ENT_QUOTES,'UTF-8')?></td><td><?=htmlspecialchars($c['descripcion']??'',ENT_QUOTES,'UTF-8')?></td></tr><?php endforeach;?>
</tbody></table>
<h2>Nueva categoria</h2><form method="post"><input type="hidden" name="csrf" value="<?=htmlspecialchars(tokenCsrf(),ENT_QUOTES,'UTF-8')?>">
<label for="nombre">Nombre</label><input id="nombre" name="nombre" required minlength="3">
<label for="descripcion">Descripcion</label><input id="descripcion" name="descripcion">
<button class="boton" type="submit">Guardar</button></form></main>
<?php require __DIR__.'/app/vistas/parciales/pie.php'; ?>
