<?php
declare(strict_types=1);
require_once __DIR__.'/app/seguridad/guardia.php';
require_once __DIR__.'/app/seguridad/csrf.php';
require_once __DIR__.'/app/config/conexion.php';
require_once __DIR__.'/app/modelos/ClienteModelo.php';
exigirRol('administrador','vendedor');
$pdo=Conexion::obtener(); $m=new ClienteModelo($pdo);
if($_SERVER['REQUEST_METHOD']==='POST'){
if(!validarCsrf($_POST['csrf']??null)){http_response_code(419);exit;}
$doc=trim($_POST['documento']??'');$nom=trim($_POST['nombre']??'');$cor=trim($_POST['correo']??'');
$err=[];
if(mb_strlen($nom)<3)$err[]='Nombre min 3.';
if(!filter_var($cor,FILTER_VALIDATE_EMAIL))$err[]='Correo invalido.';
if($doc==='')$err[]='Documento obligatorio.';
$id=(int)($_POST['id']??0);
if(($_POST['acc']??'')==='eliminar'&&$id>0){$m->desactivar($id);$_SESSION['aviso']=['tipo'=>'exito','texto'=>'Cliente desactivado.'];header('Location: clientes.php',true,303);exit;}
if(!$err){try{$d=['documento'=>$doc,'nombre'=>$nom,'correo'=>$cor,'telefono'=>trim($_POST['telefono']??''),'direccion'=>trim($_POST['direccion']??'')];
$id>0?$m->actualizar($id,$d):$m->crear($d);
$_SESSION['aviso']=['tipo'=>'exito','texto'=>'Cliente guardado.'];header('Location: clientes.php',true,303);exit;}
catch(Throwable $e){$err[]='Documento o correo duplicado.';}}
$_SESSION['aviso']=['tipo'=>'error','texto'=>implode(' ',$err)];
}
$b=trim($_GET['q']??'');$pag=max(1,(int)($_GET['page']??1));
$rows=$m->listar($b,$pag,10);$total=$m->contar($b);$pages=max(1,(int)ceil($total/10));
$edit=null;if(isset($_GET['editar']))$edit=$m->porId((int)$_GET['editar']);
require __DIR__.'/app/vistas/parciales/cabecera.php';require __DIR__.'/app/vistas/parciales/menu.php';
$av=$_SESSION['aviso']??null;unset($_SESSION['aviso']);
?>
<main class="panel__contenido"><h1>Clientes</h1>
<?php if($av):?><p class="alerta-<?=$av['tipo']==='exito'?'exito':'error'?>"><?=htmlspecialchars($av['texto'],ENT_QUOTES,'UTF-8')?></p><?php endif;?>
<form method="get"><label for="q">Buscar</label><input id="q" name="q" value="<?=htmlspecialchars($b,ENT_QUOTES,'UTF-8')?>"><button class="boton" type="submit">Buscar</button></form>
<table><caption>Listado (<?=$total?>)</caption><thead><tr><th scope="col">Doc</th><th scope="col">Nombre</th><th scope="col">Correo</th><th scope="col">Acciones</th></tr></thead>
<tbody><?php foreach($rows as $r):?><tr><td><?=htmlspecialchars($r['documento'],ENT_QUOTES,'UTF-8')?></td><td><?=htmlspecialchars($r['nombre'],ENT_QUOTES,'UTF-8')?></td><td><?=htmlspecialchars($r['correo'],ENT_QUOTES,'UTF-8')?></td>
<td><a href="clientes.php?editar=<?=$r['id']?>">Editar</a>
<form method="post" style="display:inline" onsubmit="return confirm('Desactivar?')"><input type="hidden" name="csrf" value="<?=htmlspecialchars(tokenCsrf(),ENT_QUOTES,'UTF-8')?>"><input type="hidden" name="acc" value="eliminar"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="boton-mini boton-peligro" type="submit">Eliminar</button></form></td></tr><?php endforeach;?></tbody></table>
<p><?php for($i=1;$i<=$pages;$i++):?><a href="clientes.php?q=<?=urlencode($b)?>&page=<?=$i?>"><?=$i?></a> <?php endfor;?></p>
<h2><?= $edit?'Editar':'Nuevo'?> cliente</h2>
<form method="post"><input type="hidden" name="csrf" value="<?=htmlspecialchars(tokenCsrf(),ENT_QUOTES,'UTF-8')?>"><input type="hidden" name="id" value="<?=$edit['id']??0?>">
<label for="documento">Documento</label><input id="documento" name="documento" required value="<?=htmlspecialchars($edit['documento']??'',ENT_QUOTES,'UTF-8')?>">
<label for="nombre">Nombre</label><input id="nombre" name="nombre" required minlength="3" value="<?=htmlspecialchars($edit['nombre']??'',ENT_QUOTES,'UTF-8')?>">
<label for="correo">Correo</label><input id="correo" type="email" name="correo" required value="<?=htmlspecialchars($edit['correo']??'',ENT_QUOTES,'UTF-8')?>">
<label for="telefono">Telefono</label><input id="telefono" name="telefono" value="<?=htmlspecialchars($edit['telefono']??'',ENT_QUOTES,'UTF-8')?>">
<label for="direccion">Direccion</label><input id="direccion" name="direccion" value="<?=htmlspecialchars($edit['direccion']??'',ENT_QUOTES,'UTF-8')?>">
<button class="boton" type="submit">Guardar</button></form></main>
<?php require __DIR__.'/app/vistas/parciales/pie.php'; ?>
