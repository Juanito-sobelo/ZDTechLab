<?php
declare(strict_types=1);
define('BASE_URL','/');
final class ProductoModelo{
public function __construct(private PDO $pdo){}
public function listar(string $b='',int $pag=1,int $pp=10): array{
$off=($pag-1)*$pp;
$st=$this->pdo->prepare("SELECT p.id,p.nombre,p.precio,p.stock,c.nombre AS categoria FROM productos p INNER JOIN categorias c ON c.id=p.categoria_id WHERE p.activo=1 AND p.nombre LIKE :b ORDER BY p.nombre LIMIT :lim OFFSET :off");
$st->bindValue(':b','%'.$b.'%');$st->bindValue(':lim',$pp,PDO::PARAM_INT);$st->bindValue(':off',$off,PDO::PARAM_INT);
$st->execute();return $st->fetchAll();}
public function contar(string $b=''): int{
$st=$this->pdo->prepare("SELECT COUNT(*) AS n FROM productos WHERE activo=1 AND nombre LIKE :b");
$st->execute([':b'=>'%'.$b.'%']);return (int)$st->fetch()['n'];}
public function crear(array $d): int{
$st=$this->pdo->prepare("INSERT INTO productos(nombre,categoria_id,precio,stock,stock_minimo,activo,creado_en) VALUES(:n,:c,:p,:s,:m,1,NOW())");
$st->execute([':n'=>$d['nombre'],':c'=>$d['categoria_id'],':p'=>$d['precio'],':s'=>$d['stock'],':m'=>$d['stock_minimo']??5]);
return (int)$this->pdo->lastInsertId();}
public function porId(int $id): ?array{
$st=$this->pdo->prepare("SELECT * FROM productos WHERE id=:id AND activo=1");
$st->execute([':id'=>$id]);$r=$st->fetch();return $r?:null;}
public function actualizar(int $id,array $d): bool{
$st=$this->pdo->prepare("UPDATE productos SET nombre=:n,categoria_id=:c,precio=:p,stock=:s,stock_minimo=:m WHERE id=:id");
return $st->execute([':n'=>$d['nombre'],':c'=>$d['categoria_id'],':p'=>$d['precio'],':s'=>$d['stock'],':m'=>$d['stock_minimo']??5,':id'=>$id]);}
public function desactivar(int $id): bool{
return $this->pdo->prepare("UPDATE productos SET activo=0 WHERE id=:id")->execute([':id'=>$id]);}
}
