<?php

session_start();
if (isset($_SESSION['user']) != "") {
  header("Location: admin/index.php");
} 

include_once 'class/database.class.php';

$database = new Database();

if (isset($_POST["btn-login"])) {
  
  $user = $_POST['user'];
  $pass = hash('sha256', $_POST['pass']);
  $database->query('SELECT * FROM users WHERE username = :username');
  
  $database->bind(':username', $user);
  $row = $database->single();
  
  if ($row['password'] == $pass) {
    $_SESSION['user'] = $row['username'];
    header("Location: admin/index.php");
  } else {
    echo '<script language="javascript">';
    echo 'alert("Dados inválidos!")';
    echo '</script>';
  }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="shortcut icon" href="assets/images/icon.ico"/>
  <title>Servidor IOT</title>
  <link rel="stylesheet" href="assets/css/style.css" type="text/css" />
</head>
<body>
  <center>
   <div id="logotipo"><img src="assets/images/logo/logo.png"/></div>
   <div id="login-form">
    <form method="post">
     <table align="center" width="30%" border="0">
      <tr>
       <td><input type="text" name="user" placeholder="Nome" required /></td>
     </tr>
     <tr>
       <td><input type="password" name="pass" placeholder="Senha" required /></td>
     </tr>
     <tr>
       <td><button type="submit" name="btn-login">Entrar</button></td>
     </tr>
   </table>
 </form>
</div>
</center>
</body>
</html>