<?php
declare(strict_types=1);
require_once __DIR__.'/app/seguridad/guardia.php';
require_once __DIR__.'/app/seguridad/csrf.php';
require_once __DIR__.'/app/config/conexion.php';
require_once __DIR__.'/app/modelos/ProductoModelo.php';
exigirRol('administrador','vendedor');
$pdo=Conexion::obtener(); $m=new ProductoModelo($pdo);
if($_SERVER['REQUEST_METHOD']==='POST'){
if(!validarCsrf($_POST['csrf']??null)){http_response_code(419);exit;}
$acc=$_POST['acc']??'crear'; $errores=[];
$nombre=trim((string)($_POST['nombre']??''));
$precio=filter_input(INPUT_POST,'precio',FILTER_VALIDATE_FLOAT);
$stock=filter_input(INPUT_POST,'stock',FILTER_VALIDATE_INT);
$min=filter_input(INPUT_POST,'stock_minimo',FILTER_VALIDATE_INT)?:5;
$cat=filter_input(INPUT_POST,'categoria_id',FILTER_VALIDATE_INT);
$id=(int)($_POST['id']??0);
if(mb_strlen($nombre)<3)$errores[]='El nombre debe tener al menos 3 caracteres.';
if($precio===false||$precio<=0)$errores[]='El precio debe ser mayor que cero.';
if($stock===false||$stock<0)$errores[]='El stock no puede ser negativo.';
if(!$cat)$errores[]='Seleccione una categoria.';
if($acc==='eliminar'&&$id>0){$m->desactivar($id);
$_SESSION['aviso']=['tipo'=>'exito','texto'=>'Producto desactivado.'];header('Location: productos.php',true,303);exit;}
if(!$errores){
$d=['nombre'=>$nombre,'categoria_id'=>$cat,'precio'=>$precio,'stock'=>$stock,'stock_minimo'=>$min];
$id>0?$m->actualizar($id,$d):$m->crear($d);
$_SESSION['aviso']=['tipo'=>'exito','texto'=>'Producto guardado correctamente.'];
header('Location: productos.php',true,303);exit;}
$_SESSION['aviso']=['tipo'=>'error','texto'=>implode(' ',$errores)];
}
$b=trim($_GET['q']??''); $pag=max(1,(int)($_GET['page']??1));
$rows=$m->listar($b,$pag,10); $total=$m->contar($b); $pages=max(1,(int)ceil($total/10));
$cats=$pdo->query("SELECT id,nombre FROM categorias WHERE activo=1 ORDER BY nombre")->fetchAll();
$edit=null; if(isset($_GET['editar'])) $edit=$m->porId((int)$_GET['editar']);
require __DIR__.'/app/vistas/parciales/cabecera.php';
require __DIR__.'/app/vistas/parciales/menu.php';
$av=$_SESSION['aviso']??null; unset($_SESSION['aviso']);
?>
<main class="panel__contenido"><h1>Productos</h1>
<?php if($av):?><p class="alerta-<?=$av['tipo']==='exito'?'exito':'error'?>" role="alert"><?=htmlspecialchars($av['texto'],ENT_QUOTES,'UTF-8')?></p><?php endif;?>
<form method="get" class="no-imprimir"><label for="q">Buscar</label><input id="q" name="q" value="<?=htmlspecialchars($b,ENT_QUOTES,'UTF-8')?>"><button class="boton" type="submit">Buscar</button></form>
<table id="tabla-productos"><caption>Listado (<?=$total?>)</caption>
<thead><tr><th scope="col">Producto</th><th scope="col">Categoria</th><th scope="col">Precio</th><th scope="col">Stock</th><th scope="col">Acciones</th></tr></thead>
<tbody><?php foreach($rows as $r):?><tr>
<td><?=htmlspecialchars($r['nombre'],ENT_QUOTES,'UTF-8')?></td><td><?=htmlspecialchars($r['categoria'],ENT_QUOTES,'UTF-8')?></td>
<td>$ <?=number_format((float)$r['precio'],0,',','.')?></td><td><?=$r['stock']?></td>
<td><a href="productos.php?editar=<?=$r['id']?>#form-edicion">Editar</a>
<form method="post" style="display:inline" onsubmit="return confirm('Desactivar?')"><input type="hidden" name="csrf" value="<?=htmlspecialchars(tokenCsrf(),ENT_QUOTES,'UTF-8')?>"><input type="hidden" name="acc" value="eliminar"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="boton-mini boton-peligro" type="submit">Eliminar</button></form></td></tr>
<?php endforeach;?></tbody></table>
<p><?php for($i=1;$i<=$pages;$i++):?><a href="productos.php?q=<?=urlencode($b)?>&page=<?=$i?>"><?=$i?></a> <?php endfor;?></p>
<h2 id="form-edicion"><?= $edit?'Editando: '.htmlspecialchars($edit['nombre'],ENT_QUOTES,'UTF-8'):'Nuevo producto'?></h2>
<form id="form-producto" method="post">
<input type="hidden" name="csrf" value="<?=htmlspecialchars(tokenCsrf(),ENT_QUOTES,'UTF-8')?>">
<input type="hidden" name="id" value="<?=$edit['id']??0?>">
<label for="nombre">Nombre</label><input id="nombre" name="nombre" required minlength="3" value="<?=htmlspecialchars($edit['nombre']??'',ENT_QUOTES,'UTF-8')?>">
<label for="categoria_id">Categoria</label><select id="categoria_id" name="categoria_id" required><option value="">Seleccione</option>
<?php foreach($cats as $c):?><option value="<?=$c['id']?>" <?=isset($edit)&&$edit['categoria_id']==$c['id']?'selected':''?>><?=htmlspecialchars($c['nombre'],ENT_QUOTES,'UTF-8')?></option><?php endforeach;?></select>
<label for="precio">Precio</label><input id="precio" name="precio" type="number" step="1" min="1" required value="<?=htmlspecialchars((string)($edit['precio']??''),ENT_QUOTES,'UTF-8')?>">
<label for="stock">Stock</label><input id="stock" name="stock" type="number" step="1" min="0" required value="<?=htmlspecialchars((string)($edit['stock']??'0'),ENT_QUOTES,'UTF-8')?>">
<label for="stock_minimo">Stock minimo</label><input id="stock_minimo" name="stock_minimo" type="number" min="0" value="<?=htmlspecialchars((string)($edit['stock_minimo']??'5'),ENT_QUOTES,'UTF-8')?>">
<button class="boton" type="submit">Guardar</button></form></main>
<?php require __DIR__.'/app/vistas/parciales/pie.php'; ?>
