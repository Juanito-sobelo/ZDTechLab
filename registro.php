<?php
require_once __DIR__.'/app/seguridad/sesion.php';
require_once __DIR__.'/app/seguridad/csrf.php';
require_once __DIR__.'/app/config/conexion.php';
iniciarSesionSegura();
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
if(!validarCsrf($_POST['csrf']??null)){http_response_code(419);exit;}
$n=trim($_POST['nombre']??'');$c=trim($_POST['correo']??'');$cl=(string)($_POST['clave']??'');$rol=$_POST['rol']??'vendedor';
if(mb_strlen($n)<3||!filter_var($c,FILTER_VALIDATE_EMAIL)||strlen($cl)<8||!in_array($rol,['administrador','vendedor','consultor'],true)){$msg='Datos invalidos.';}
else{try{$pdo=Conexion::obtener();
$pdo->prepare("INSERT INTO usuarios(nombre,correo,clave_hash,rol) VALUES(:n,:c,:h,:r)")
->execute([':n'=>$n,':c'=>$c,':h'=>password_hash($cl,PASSWORD_DEFAULT),':r'=>$rol]);
$msg='Usuario creado. <a href="login.php">Ingresar</a>';}catch(Throwable $e){$msg='Correo ya existe.';}}
}
?><!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Registro - ZD.TechLab</title><link rel="stylesheet" href="css/tokens.css"><link rel="stylesheet" href="css/estilos.css"></head>
<body><main class="pantalla-ingreso"><h1>Registro</h1>
<?php if($msg):?><p class="alerta-exito"><?=$msg?></p><?php endif;?>
<form method="post"><input type="hidden" name="csrf" value="<?=htmlspecialchars(tokenCsrf(),ENT_QUOTES,'UTF-8')?>">
<label for="nombre">Nombre</label><input id="nombre" name="nombre" required minlength="3">
<label for="correo">Correo</label><input id="correo" type="email" name="correo" required>
<label for="clave">Clave (min 8)</label><input id="clave" type="password" name="clave" required minlength="8">
<label for="rol">Rol</label><select id="rol" name="rol"><option>vendedor</option><option>consultor</option><option>administrador</option></select>
<button class="boton" type="submit">Crear</button></form></main></body></html>
