<?php
session_start();

if (!isset($_SESSION['user'])) {
 header("Location: ../index.php");
}


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <link rel="shortcut icon" href="../assets/images/icon.ico"/>
  <link rel="stylesheet" href="assets/css/style.css" type="text/css" />
  <script src="assets/js/functions.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script>

  
  window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {

      var dropdowns = document.getElementsByClassName("dropdown-content");
      var i;
      for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
          openDropdown.classList.remove('show');
        }
      }
    }
  }
  
  
  $(document).ready(function() {
    $.ajaxSetup({
      cache: false
    });

    setInterval(lertemp, 5000);
  });
  
  function lertemp() {
    $.ajax({
      type: 'post',
      url: 'checktemperatura.php',
      data: {
        get_temp: "notification"
      },
      success: function(response) {
        $('#notification').text(response);
        
        if($('#notification').text().length > 1) {
          $('#notification').css('display', 'block');
        } else {
          $('#notification').css('display', 'none');
        }
      }
    });
  }
  
  </script>
</head>
<body>
  <div id="headertop">
   <img src="assets/images/logo.png" class="logo" onclick="window.location.href = 'index.php'"/>
   <img src="assets/images/icons/logout.png" align="right" class="logouticon" onclick="performLogout();" />
   <img src="assets/images/icons/options.png" class="optionsicon" />
   <img src="assets/images/icons/graph.png" class="graphicon" />
   <div class="dropdown">
    <button onclick="dropdownMenu()" class="dropbtn">Opções</button>
    <div id="myDropdown" class="dropdown-content">
     <a onclick="displayMaximaTemperatura();">Máxima temperatura</a>
     <a onclick="displayRemoverMaximaTemperatura();">Remover máxima temperatura</a>
     <a onclick="displayNormalizarTemperatura();">Normalizar temperatura</a>
     <a onclick="displayListaDeCamioes();">Lista de camiões</a>
   </div>
 </div>
 <button onclick="displayGraphPopup();" class="btn-graph">Gráficos</button>
 <span class="who"><?php echo $_SESSION['user']; ?></span>
</div>
<div>
 <div id="graph-popup">
  <form method="post" action="grafico/index.php">
   <table align="center" width="30%" border="0">
    <tr>
     <td><input type="text" name="mostrar-grafico" placeholder="Insira aqui o número do camião" required /></td>
   </tr>
   <tr>
    <td><input type="text" name="definir-hora" placeholder="Insira aqui o número de hora" required /></td>
  </tr>
  <tr>
   <td><button type="submit" name="btn-mostrar-grafico">Mostrar Gráfico</button></td>
 </tr>
</table>
</form>
</div>
<div id="temperatura-adicionar">
  <form method="post">
   <table align="center" width="30%" border="0">
    <tr>
     <td><input type="text" name="temperatura-escolha" placeholder="Insira aqui o número da temperatura" required /></td>
   </tr>
   <tr>
     <td><button type="submit" name="btn-maxima-temperatura">Enviar</button></td>
   </tr>
 </table>
</form>
</div>
<div id="temperatura-remover">
  <form method="post">
   <table align="center" width="30%" border="0">
    <tr>
     <td><button type="submit" name="btn-remove-temperatura">Enviar</button></td>
   </tr>
 </table>
</form>
</div>
<div id="normalizar-temperatura">
  <form method="post">
   <table align="center" width="30%" border="0">
    <tr>
     <td><input type="text" name="camiao-escolha" placeholder="Insira aqui o número do camião" required /></td>
   </tr>
   <tr>
     <td><button type="submit" name="btn-normalizar-temperatura">Enviar</button></td>
   </tr>
 </table>
</form>
</div>
<div id="lista-camioes">
 
 <?php
 
 require_once '../class/database.class.php';
 $database = new Database();
 
 $database->query("SELECT utruck FROM ultimastemperaturas");
 $result = $database->resultset();
 $count = $database->rowCount();
 
 if ($count > 0) {
   
  for ($i = 0; $i < $count; $i++) {
   echo "Camião número: " . $result[$i]["utruck"];
   echo "<br />";
 }
} else {
  echo "Nenhum camião disponível.";
}
?>

</div>
<div id="notification"></div> 
</div>
</body>
</html>

<?php

if (isset($_POST['btn-maxima-temperatura'])) {
  if (is_numeric($_POST["temperatura-escolha"])) {
    require_once '../class/database.class.php';
    $database = new Database();
    $database->query("TRUNCATE TABLE maxtemp");
    $database->execute();
    $database->query("INSERT INTO maxtemp VALUES(:t)");
    $database->bind(":t", $_POST['temperatura-escolha']);
    
    if ($database->execute()) {
      echo "<script type='text/javascript'>alert('Sucesso!');</script>";
    }
  } else {
    echo "<script type='text/javascript'>alert('A temperatura introduzida não é um número numérico!');</script>";
  }
}

if (isset($_POST['btn-remove-temperatura'])) {
  require_once '../class/database.class.php';
  $database = new Database();
  $database->query("TRUNCATE TABLE maxtemp");
  
  if ($database->execute()) {
    echo "<script type='text/javascript'>alert('Sucesso!');</script>";
  }
}

if (isset($_POST['btn-normalizar-temperatura'])) {
  if (is_numeric($_POST["camiao-escolha"])) {
    $truck = $_POST["camiao-escolha"];
    require_once '../class/database.class.php';
    $database = new Database();
    $database->query("UPDATE ultimastemperaturas SET normalizada=1 WHERE utruck=:truck");
    $database->bind(':truck', $truck);
    
    if ($database->execute()) {
      echo "<script type='text/javascript'>alert('Sucesso!');</script>";
    }
  } else {
    echo "<script type='text/javascript'>alert('O número do camião introduzido não é um número numérico!');</script>";
  }
}

?>