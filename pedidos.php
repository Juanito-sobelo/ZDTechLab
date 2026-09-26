<?php
declare(strict_types=1);
require_once __DIR__.'/app/seguridad/guardia.php';
require_once __DIR__.'/app/seguridad/csrf.php';
require_once __DIR__.'/app/config/conexion.php';
exigirRol('administrador','vendedor');
$pdo=Conexion::obtener();
if($_SERVER['REQUEST_METHOD']==='POST'){
if(!validarCsrf($_POST['csrf']??null)){http_response_code(419);exit;}
$cli=(int)($_POST['cliente_id']??0);$prod=(int)($_POST['producto_id']??0);$cant=(int)($_POST['cantidad']??0);
if($cli<=0||$prod<=0||$cant<=0){$_SESSION['aviso']=['tipo'=>'error','texto'=>'Seleccione cliente, producto y cantidad valida.'];}
else{
$pdo->beginTransaction();
try{
$st=$pdo->prepare("SELECT precio,stock FROM productos WHERE id=:id AND activo=1");$st->execute([':id'=>$prod]);$p=$st->fetch();
if(!$p)throw new Exception('Producto no existe'); if($p['stock']<$cant)throw new Exception('Stock insuficiente: '.$p['stock']);
$total=$cant*(float)$p['precio'];
$pdo->prepare("INSERT INTO pedidos(cliente_id,fecha,total,estado,creado_por) VALUES(:c,NOW(),:t,'confirmado',:u)")
->execute([':c'=>$cli,':t'=>$total,':u'=>$_SESSION['usuario']['id']]);
$pid=(int)$pdo->lastInsertId();
$pdo->prepare("INSERT INTO detalle_pedido(pedido_id,producto_id,cantidad,precio_unitario) VALUES(:p,:pr,:c,:pu)")
->execute([':p'=>$pid,':pr'=>$prod,':c'=>$cant,':pu'=>$p['precio']]);
$pdo->prepare("UPDATE productos SET stock=stock-:c WHERE id=:id")->execute([':c'=>$cant,':id'=>$prod]);
$pdo->commit();$_SESSION['aviso']=['tipo'=>'exito','texto'=>"Pedido #$pid registrado."];
}catch(Throwable $e){$pdo->rollBack();error_log($e->getMessage());$_SESSION['aviso']=['tipo'=>'error','texto'=>$e->getMessage()];}
}
header('Location: pedidos.php',true,303);exit;}
$clientes=$pdo->query("SELECT id,nombre FROM clientes WHERE activo=1 ORDER BY nombre")->fetchAll();
$prods=$pdo->query("SELECT id,nombre,precio,stock FROM productos WHERE activo=1 ORDER BY nombre")->fetchAll();
$pedidos=$pdo->query("SELECT p.id,cl.nombre AS cliente,p.fecha,p.total,p.estado FROM pedidos p LEFT JOIN clientes cl ON cl.id=p.cliente_id ORDER BY p.id DESC LIMIT 20")->fetchAll();
require __DIR__.'/app/vistas/parciales/cabecera.php';require __DIR__.'/app/vistas/parciales/menu.php';
$av=$_SESSION['aviso']??null;unset($_SESSION['aviso']);
?>
<main class="panel__contenido"><h1>Pedidos</h1>
<?php if($av):?><p class="alerta-<?=$av['tipo']==='exito'?'exito':'error'?>"><?=htmlspecialchars($av['texto'],ENT_QUOTES,'UTF-8')?></p><?php endif;?>
<h2>Nuevo pedido (transaccion descuenta stock)</h2>
<form method="post"><input type="hidden" name="csrf" value="<?=htmlspecialchars(tokenCsrf(),ENT_QUOTES,'UTF-8')?>">
<label for="cliente_id">Cliente</label><select id="cliente_id" name="cliente_id" required><option value="">Seleccione</option><?php foreach($clientes as $c):?><option value="<?=$c['id']?>"><?=htmlspecialchars($c['nombre'],ENT_QUOTES,'UTF-8')?></option><?php endforeach;?></select>
<label for="producto_id">Producto</label><select id="producto_id" name="producto_id" required><option value="">Seleccione</option><?php foreach($prods as $p):?><option value="<?=$p['id']?>"><?=htmlspecialchars($p['nombre'],ENT_QUOTES,'UTF-8')?> - $<?=number_format($p['precio'],0,',','.')?> (stock <?=$p['stock']?>)</option><?php endforeach;?></select>
<label for="cantidad">Cantidad</label><input id="cantidad" name="cantidad" type="number" min="1" value="1" required>
<button class="boton" type="submit">Registrar pedido</button></form>
<h2>Historial (el borrado logico mantiene el historico)</h2>
<table><thead><tr><th>#</th><th>Cliente</th><th>Fecha</th><th>Total</th><th>Estado</th></tr></thead>
<tbody><?php foreach($pedidos as $p):?><tr><td><?=$p['id']?></td><td><?=htmlspecialchars($p['cliente']??'General',ENT_QUOTES,'UTF-8')?></td><td><?=$p['fecha']?></td><td>$ <?=number_format($p['total'],0,',','.')?></td><td><?=$p['estado']?></td></tr><?php endforeach;?></tbody></table></main>
<?php require __DIR__.'/app/vistas/parciales/pie.php'; ?>
