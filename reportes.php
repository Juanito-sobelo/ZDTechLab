<?php
declare(strict_types=1);
require_once __DIR__.'/app/seguridad/guardia.php';
require_once __DIR__.'/app/config/conexion.php';
exigirRol('administrador','consultor');
$pdo=Conexion::obtener();
$desde=$_GET['desde']??'2026-01-01'; $hasta=$_GET['hasta']??'2026-09-28';
$tipo=$_GET['tipo']??'categoria';
// CSV export
if(isset($_GET['csv'])){
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="reporte-'.$tipo.'-'.date('Ymd').'.csv"');
$s=fopen('php://output','w'); fwrite($s,"\xEF\xBB\xBF");
if($tipo==='categoria'){$f=$pdo->query("SELECT categoria,unidades,total_vendido FROM v_ventas_categoria WHERE total_vendido>0 ORDER BY total_vendido DESC")->fetchAll();
fputcsv($s,['Categoria','Unidades','Total'],';');foreach($f as $x)fputcsv($s,[$x['categoria'],$x['unidades'],$x['total_vendido']],';');}
elseif($tipo==='stock'){$f=$pdo->query("SELECT nombre,categoria,stock,stock_minimo FROM v_stock_critico")->fetchAll();
fputcsv($s,['Producto','Categoria','Stock','Minimo'],';');foreach($f as $x)fputcsv($s,[$x['nombre'],$x['categoria'],$x['stock'],$x['stock_minimo']],';');}
else{$f=$pdo->query("SELECT nombre,correo,pedidos,total_comprado FROM v_top_clientes LIMIT 20")->fetchAll();
fputcsv($s,['Cliente','Correo','Pedidos','Total'],';');foreach($f as $x)fputcsv($s,[$x['nombre'],$x['correo'],$x['pedidos'],$x['total_comprado']],';');}
fclose($s);exit;}
$cat=$pdo->query("SELECT categoria,unidades,total_vendido FROM v_ventas_categoria WHERE total_vendido>0 ORDER BY total_vendido DESC")->fetchAll();
$totCat=array_sum(array_column($cat,'total_vendido'));
$stk=$pdo->query("SELECT * FROM v_stock_critico")->fetchAll();
$cli=$pdo->query("SELECT * FROM v_top_clientes LIMIT 20")->fetchAll();
$numeroReporte='R-2026-0148';
require __DIR__.'/app/vistas/parciales/cabecera.php';
require __DIR__.'/app/vistas/parciales/menu.php';
?>
<link rel="stylesheet" href="css/reporte.css">
<main class="panel__contenido">
<h1>Reportes</h1>
<form method="get" class="no-imprimir">
<label for="tipo">Reporte</label><select id="tipo" name="tipo">
<option value="categoria" <?=$tipo==='categoria'?'selected':''?>>Ventas por categoria</option>
<option value="stock" <?=$tipo==='stock'?'selected':''?>>Inventario stock critico</option>
<option value="clientes" <?=$tipo==='clientes'?'selected':''?>>Pedidos por cliente</option></select>
<label for="desde">Desde</label><input id="desde" type="date" name="desde" value="<?=$desde?>">
<label for="hasta">Hasta</label><input id="hasta" type="date" name="hasta" value="<?=$hasta?>">
<button class="boton" type="submit">Filtrar</button>
<a class="boton boton--sec" href="reportes.php?tipo=<?=$tipo?>&csv=1">CSV</a>
<button class="boton no-imprimir" type="button" onclick="window.print()">Imprimir / PDF</button>
</form>
<?php require __DIR__.'/app/vistas/reportes/encabezado.php'; ?>
<p class="t5">Filtros: <?=$desde?> al <?=$hasta?> · Estado: confirmado</p>
<?php if($tipo==='categoria'):?>
<div class="graficos no-imprimir"><div class="grafico"><canvas id="g-ventas"></canvas></div><div class="grafico"><canvas id="g-categorias"></canvas></div></div>
<table><thead><tr><th>Categoria</th><th>Unidades</th><th>Total vendido</th><th>%</th></tr></thead><tbody>
<?php foreach($cat as $f):?><tr><td><?=htmlspecialchars($f['categoria'],ENT_QUOTES,'UTF-8')?></td><td><?=$f['unidades']?></td><td>$ <?=number_format((float)$f['total_vendido'],0,',','.')?></td><td><?=round($totCat?(float)$f['total_vendido']*100/$totCat:0,1)?></td></tr><?php endforeach;?>
<tr><td><strong>Total general</strong></td><td><strong><?=array_sum(array_column($cat,'unidades'))?></strong></td><td><strong>$ <?=number_format((float)$totCat,0,',','.')?></strong></td><td><strong>100</strong></td></tr>
</tbody></table>
<?php elseif($tipo==='stock'):?>
<table><thead><tr><th>Producto</th><th>Categoria</th><th>Stock</th><th>Minimo</th></tr></thead><tbody>
<?php foreach($stk as $f):?><tr><td><?=htmlspecialchars($f['nombre'],ENT_QUOTES,'UTF-8')?></td><td><?=htmlspecialchars($f['categoria'],ENT_QUOTES,'UTF-8')?></td><td><?=$f['stock']?></td><td><?=$f['stock_minimo']?></td></tr><?php endforeach;?>
</tbody></table>
<?php else:?>
<table><thead><tr><th>Cliente</th><th>Pedidos</th><th>Total comprado</th></tr></thead><tbody>
<?php foreach($cli as $f):?><tr><td><?=htmlspecialchars($f['nombre'],ENT_QUOTES,'UTF-8')?></td><td><?=$f['pedidos']?></td><td>$ <?=number_format((float)$f['total_comprado'],0,',','.')?></td></tr><?php endforeach;?>
</tbody></table>
<?php endif;?>
<p class="t5 reporte__pie">Documento generado automaticamente por ZD.TechLab. Informacion de uso interno. Pagina 1 de 1</p>
</main>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="js/graficos.js"></script>
<?php require __DIR__.'/app/vistas/parciales/pie.php'; ?>
