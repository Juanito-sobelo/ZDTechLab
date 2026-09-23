<?php
declare(strict_types=1);
require_once __DIR__.'/app/seguridad/sesion.php';
require_once __DIR__.'/app/seguridad/csrf.php';
require_once __DIR__.'/app/config/conexion.php';
iniciarSesionSegura();
if(!empty($_SESSION['usuario'])){header('Location: dashboard.php');exit;}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
if(!validarCsrf($_POST['csrf']??null)){http_response_code(419);exit('Solicitud no valida. Recargue.');}
$correo=trim((string)($_POST['correo']??'')); $clave=(string)($_POST['clave']??'');
if(!filter_var($correo,FILTER_VALIDATE_EMAIL)||strlen($clave)<8){$error='Correo o contraseña incorrectos.';}
else{
$pdo=Conexion::obtener();
$st=$pdo->prepare("SELECT id,nombre,clave_hash,rol,activo,bloqueado_hasta FROM usuarios WHERE correo=:c LIMIT 1");
$st->execute([':c'=>$correo]); $u=$st->fetch();
if($u&&$u['bloqueado_hasta']!==null&&strtotime($u['bloqueado_hasta'])>time()){$error='Cuenta bloqueada temporalmente. Intente mas tarde.';}
elseif($u&&(int)$u['activo']===1&&password_verify($clave,$u['clave_hash'])){
if(password_needs_rehash($u['clave_hash'],PASSWORD_DEFAULT)){
$n=password_hash($clave,PASSWORD_DEFAULT);
$pdo->prepare("UPDATE usuarios SET clave_hash=:h WHERE id=:id")->execute([':h'=>$n,':id'=>$u['id']]);}
registrarIntento($pdo,$correo,true); abrirSesion($u);
header('Location: dashboard.php');exit;}
else{registrarIntento($pdo,$correo,false); $error='Correo o contraseña incorrectos.';}
}}
?><!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Ingreso - ZD.TechLab</title><link rel="stylesheet" href="css/tokens.css"><link rel="stylesheet" href="css/estilos.css"></head>
<body><main class="pantalla-ingreso">
<img src="assets/img/logo.svg" alt="Logo de ZD.TechLab" width="120">
<h1>Ingreso al panel de gestion</h1>
<form method="post" action="login.php" autocomplete="on" novalidate>
<input type="hidden" name="csrf" value="<?=htmlspecialchars(tokenCsrf(),ENT_QUOTES,'UTF-8')?>">
<?php if($error!==''):?><p class="alerta-error" role="alert"><?=htmlspecialchars($error,ENT_QUOTES,'UTF-8')?></p><?php endif;?>
<fieldset><legend>Credenciales</legend>
<label for="correo">Correo electronico</label>
<input type="email" id="correo" name="correo" required autocomplete="username" value="<?=htmlspecialchars($_POST['correo']??'',ENT_QUOTES,'UTF-8')?>">
<label for="clave">Contraseña</label>
<input type="password" id="clave" name="clave" required autocomplete="current-password" minlength="8">
</fieldset>
<button class="boton" type="submit">Iniciar sesion</button>
<p class="t5">Pruebas: admin@zdtechlab.co / vendedor@zdtechlab.co / consultor@zdtechlab.co — clave: Clave.2026*</p>
</form></main></body></html>
