<?php
declare(strict_types=1);
require_once __DIR__.'/app/seguridad/guardia.php';
require_once __DIR__.'/app/config/conexion.php';
header('Content-Type: application/json; charset=utf-8');
$pdo=Conexion::obtener();
$meses=array_reverse($pdo->query("SELECT periodo,total_vendido FROM v_ventas_mes ORDER BY periodo DESC LIMIT 12")->fetchAll());
$cats=$pdo->query("SELECT categoria,total_vendido FROM v_ventas_categoria WHERE total_vendido>0 ORDER BY total_vendido DESC")->fetchAll();
$stk=$pdo->query("SELECT nombre,stock FROM v_stock_critico ORDER BY stock ASC LIMIT 10")->fetchAll();
echo json_encode(['ventasMes'=>['etiquetas'=>array_column($meses,'periodo'),'valores'=>array_map('floatval',array_column($meses,'total_vendido'))],
'categorias'=>['etiquetas'=>array_column($cats,'categoria'),'valores'=>array_map('floatval',array_column($cats,'total_vendido'))],
'stock'=>['etiquetas'=>array_column($stk,'nombre'),'valores'=>array_map('intval',array_column($stk,'stock'))]],
JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR);
