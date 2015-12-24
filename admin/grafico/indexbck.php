<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
}

?>
<?php
if (isset($_POST['btn-mostrar-grafico'])) {
    $truck = $_POST['mostrar-grafico'];
    $hora = $_POST['definir-hora'];
    require_once '../../class/database.class.php';
    $database = new Database();
    
    $database->query("select * from data where datetime >= SUBDATE(NOW(), INTERVAL :hora HOUR) AND truck=:truck");
    $database->bind(':truck', $truck);
	$database->bind(':hora', $hora);
	
    $emparray = $database->resultset();
   
    $fp = fopen('dados/cam' . $truck . '.json', 'w');
    fwrite($fp, json_encode($emparray));
    fclose($fp);
    
    $database->close();    
}
?>

<!DOCTYPE html>
<html>
  <head>
    <!-- EXTERNAL LIBS-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://www.google.com/jsapi"></script>

    <!-- EXAMPLE SCRIPT -->
    <script>
	var cam = "<?php
echo $_POST['mostrar-grafico'];
?>";
      // onload callback
      function drawChart() {

        // JSONP request
        var jsonData = $.ajax({
          url: 'dados/cam'+cam+'.json',
          data: {page: 1},
          dataType: 'json',
        }).done(function (results) {

          var data = new google.visualization.DataTable();

          data.addColumn('datetime', 'Time');
          data.addColumn('number', 'Temp');

          $.each(results, function (i, row) {
            data.addRow([
              (new Date(row.datetime)),
              parseFloat(row.temp)
            ]);
          });

          var chart = new google.visualization.LineChart($('#chart').get(0));

          chart.draw(data, {
            title: 'Temperatura'
          });

        });

      }

      // load chart lib
      google.load('visualization', '1', {
        packages: ['corechart']
      });

      // call drawChart once google charts is loaded
	google.setOnLoadCallback(drawChart); 
    </script>

  </head>
  <body>
    <div id="chart" style="width: 100%;"></div>
  </body>
</html>