<?php
@ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>PharmaDB</title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/> 
</head>
  <body>
    <div class="menu">
      <nav  role="navigation" class="navbar navbar-inverse" id="nav_show">
          <div id="nav">
              <ul class="nav navbar-nav site_nav_menu1"  >
                  <li id="logo">PharmaDB</li>
                  <li><a href="index.php">Clienti</a></li>
                  <li><a href="Aprovizionare.php">Aprovizionare</a></li>
                  <li><a href="Vanzare.php">Vanzare</a></li>
              </ul>
          </div>
      </nav>
    </div>
    <div class="form">
      <div class ="list">
          <form action = "<?php $_PHP_SELF ?>" method="POST">
            <input class="btn-default" type = "submit" name="alege" value="Inainte"/>
      </div>
      <div class="records"> 
    <?php
    $host = 'mysql';
    $user = 'root';
    $pass = 'asd';
    $conn = new mysqli($host, $user, $pass);
    function writeError($e) {
      echo '<div class="topcorner">
      <span class="closebtn" onclick="this.parentElement.style.display="none";">&times;</span>
      '.$e.'</div>';
    }
    function writeSucces($s){
      echo '<div class="topcornerS" id="showMe">'.$s.'</div>';
    }
    if($_SESSION["succes"] != ""){
      writeSucces($_SESSION["succes"]);
      $_SESSION["succes"] = "";
    }
    if ($conn->connect_error) {
        writeError($conn->error);
        exit;
    }
    $db_selected = mysqli_select_db($conn, 'teodb');
    if (!$db_selected) {
        writeError($conn->error);
        exit;
    }

    $sql = "SELECT Id_Client, Id_Locatie, Nume, Prenume, Discount FROM Clienti";
    $result = $conn->query($sql);
    if($result == FALSE){
      writeError($conn->error);
      exit;
    }
    echo '<div class="divTable">';
    echo '<div class="divTableHeading">';
    echo '<div class="divTableCell">Id Client</div>';
    echo '<div class="divTableCell">Id Locatie</div>';
    echo '<div class="divTableCell">Nume</div>'; 
    echo '<div class="divTableCell">Prenume</div>';
    echo '<div class="divTableCell">Discount</div>';
    echo '</div>';
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          echo '<input type="radio" id="'.$row["Id_Client"].'" name="loc" value="'.$row["Id_Client"].'">';
          echo '<label for="'.$row["Id_Client"].'" >';
          echo '<div class="divTableCell">'.$row["Id_Client"].'</div>';
          echo '<div class="divTableCell">'.$row["Id_Locatie"].'</div>';
          echo '<div class="divTableCell">'.$row["Nume"].'</div>';
          echo '<div class="divTableCell">'.$row["Prenume"].'</div>';
          echo '<div class="divTableCell">'.$row["Discount"].'</div>';
          echo '</label>';
      }
    }
    echo '</div>';
    if(isset($_POST["alege"]) && isset($_POST["loc"])){
      $id_cl = $_POST["loc"];
      $_SESSION['client'] = $id_cl;
      $sql = "SELECT Id_Locatie FROM Clienti WHERE Id_Client = $id_cl";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $_SESSION['locatie_client']= $row["Id_Locatie"];
      echo "<meta http-equiv=refresh content=\"0; URL=Reteta.php\">";
    }
    else if(isset($_POST["alege"])){
      writeError("Clientul nu este selectat!");
    }
    
    $conn->close();
    ?>
  </div>
  </form>
  </div>
  </body>
</html>