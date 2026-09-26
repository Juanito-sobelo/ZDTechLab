<?php
declare(strict_types=1);
final class ClienteModelo{
public function __construct(private PDO $pdo){}
public function listar(string $b='',int $pag=1,int $pp=10): array{
$off=($pag-1)*$pp;
$st=$this->pdo->prepare("SELECT * FROM clientes WHERE activo=1 AND (nombre LIKE :b OR documento LIKE :b) ORDER BY nombre LIMIT :lim OFFSET :off");
$st->bindValue(':b','%'.$b.'%');$st->bindValue(':lim',$pp,PDO::PARAM_INT);$st->bindValue(':off',$off,PDO::PARAM_INT);
$st->execute();return $st->fetchAll();}
public function contar(string $b=''): int{
$st=$this->pdo->prepare("SELECT COUNT(*) AS n FROM clientes WHERE activo=1 AND (nombre LIKE :b OR documento LIKE :b)");
$st->execute([':b'=>'%'.$b.'%']);return (int)$st->fetch()['n'];}
public function crear(array $d): int{
$st=$this->pdo->prepare("INSERT INTO clientes(documento,nombre,correo,telefono,direccion) VALUES(:doc,:nom,:cor,:tel,:dir)");
$st->execute([':doc'=>$d['documento'],':nom'=>$d['nombre'],':cor'=>$d['correo'],':tel'=>$d['telefono']??null,':dir'=>$d['direccion']??null]);
return (int)$this->pdo->lastInsertId();}
public function porId(int $id): ?array{
$st=$this->pdo->prepare("SELECT * FROM clientes WHERE id=:id AND activo=1");
$st->execute([':id'=>$id]);$r=$st->fetch();return $r?:null;}
public function actualizar(int $id,array $d): bool{
$st=$this->pdo->prepare("UPDATE clientes SET documento=:doc,nombre=:nom,correo=:cor,telefono=:tel,direccion=:dir WHERE id=:id");
return $st->execute([':doc'=>$d['documento'],':nom'=>$d['nombre'],':cor'=>$d['correo'],':tel'=>$d['telefono']??null,':dir'=>$d['direccion']??null,':id'=>$id]);}
public function desactivar(int $id): bool{
return $this->pdo->prepare("UPDATE clientes SET activo=0 WHERE id=:id")->execute([':id'=>$id]);}
}
