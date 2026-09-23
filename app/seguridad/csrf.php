<?php
declare(strict_types=1);
function tokenCsrf(): string{
if(empty($_SESSION['csrf'])){$_SESSION['csrf']=bin2hex(random_bytes(32));}
return $_SESSION['csrf'];}
function validarCsrf(?string $enviado): bool{
return is_string($enviado)&&!empty($_SESSION['csrf'])&&hash_equals($_SESSION['csrf'],$enviado);}
function registrarIntento(PDO $pdo,string $correo,bool $exito): void{
$pdo->prepare("INSERT INTO intentos_acceso(correo,exito,ip) VALUES(:c,:e,:ip)")
->execute([':c'=>$correo,':e'=>$exito?1:0,':ip'=>$_SERVER['REMOTE_ADDR']??null]);
$pdo->prepare("DELETE FROM intentos_acceso WHERE fecha < (NOW() - INTERVAL 1 DAY)")->execute();
if(!$exito){
$st=$pdo->prepare("SELECT COUNT(*) AS n FROM intentos_acceso WHERE correo=:c AND exito=0 AND fecha>(NOW()-INTERVAL 15 MINUTE)");
$st->execute([':c'=>$correo]); $n=(int)($st->fetch()['n']??0);
if($n>=5){$pdo->prepare("UPDATE usuarios SET bloqueado_hasta=(NOW()+INTERVAL 15 MINUTE) WHERE correo=:c")->execute([':c'=>$correo]);}
}}
