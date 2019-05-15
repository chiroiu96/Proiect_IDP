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
      <div class ="records">
          <form action = "<?php $_PHP_SELF ?>" method="POST">
            <div class="list">
              <div class="records">
            <input class="btn-default" type = "submit" name="add" value="Adauga"/>
            <input type = "text" name = "cant" class = "form-control input-lg" placeholder="Cantitate" autocomplete="off"/>
          </div>
            <input class="btn-default" type = "submit" name="back" value="Inapoi"/>
            </div>
      </div>
      <div class="records"> 
    <?php
    $host = 'mysql';
    $user = 'root';
    $pass = 'asd';
    $conn = new mysqli($host, $user, $pass);

    $conn = new mysqli($host, $user, $pass);
    $err = 0;
    $msg = "";
    function writeError($e) {
      echo '<div class="topcorner">
      <span class="closebtn" onclick="this.parentElement.style.display="none";">&times;</span>
      '.$e.'</div>';
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

    $locatie_client = $_SESSION['locatie_client'];
    $sql = "SELECT Id_Medicament, Cantitate FROM Depozit WHERE Id_Locatie = $locatie_client";
    $result = $conn->query($sql);
    if($result == FALSE){
      writeError($conn->error);
      exit;
    }
    echo '<div class="divTable">';
    echo '<div class="divTableHeading">';
    echo '<div class="divTableCell">Id Medicament</div>';
    echo '<div class="divTableCell">Denumire</div>';
    echo '<div class="divTableCell">Pret</div>';
    echo '<div class="divTableCell">Cantitate</div>'; 
    echo '</div>';
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          echo '<input type="radio" id="'.$row["Id_Medicament"].'" name="med" value="'.$row["Id_Medicament"].'">';
          echo '<label for="'.$row["Id_Medicament"].'" >';
          echo '<div class="divTableCell">'.$row["Id_Medicament"].'</div>';
          $id_med = $row["Id_Medicament"];
          $sql = "SELECT Denumire, Pret FROM Medicamente WHERE Id_Medicament = $id_med";
          $result_den = $conn->query($sql);
          if($result_den == FALSE){
            writeError($conn->error);
            exit;
          }
          $row_den = $result_den->fetch_assoc();
          echo '<div class="divTableCell">'.$row_den["Denumire"].'</div>';
          echo '<div class="divTableCell">'.$row_den["Pret"].'</div>';
          echo '<div class="divTableCell">'.$row["Cantitate"].'</div>';
          echo '</label>';
      }
    }
    echo '</div>';
    if(isset($_POST["add"]) && isset($_POST["med"]) && is_numeric($_POST["cant"])){
      $sql = "SELECT * FROM Medicamente_reteta";
      $result = $conn->query($sql);
      $nr_med_ret = $result->num_rows;
      $nr_ret = $_SESSION['nr_reteta'];
      $id_med = $_POST["med"];
      $cant = $_POST["cant"];
      $sql = "SELECT Cantitate FROM Depozit WHERE Id_Locatie = $locatie_client AND Id_Medicament = $id_med";
      $result = $conn->query($sql);
      if($result == TRUE){
        $row = $result->fetch_assoc();
        $cant_max = $row["Cantitate"];
        if($cant > 0 && $cant <= $cant_max){
          $sql = "INSERT INTO Medicamente_reteta (Id_Medicament_Reteta, Id_Reteta, Id_Medicament, Cantitate) ".
          "VALUES ($nr_med_ret, $nr_ret, $id_med, $cant)";
          $result = $conn->query($sql);
          if($result == FALSE){
            $err = 1;
          }
          if(!$err){
            $sql = "UPDATE Depozit SET Cantitate = Cantitate - $cant WHERE Id_Medicament = $id_med AND Id_Locatie = $locatie_client";
            $result = $conn->query($sql);
            if($result == FALSE){
              writeError($conn->error);
            }
            else{
              echo "<meta http-equiv=refresh content=\"0; URL=Reteta.php\">";
            }
          }
          else{
            writeError($conn->error);
          }
        }
        else{
          writeError("Campul cantitate are valoare negativa sau este prea mare!");
        }
      }
      else{
        writeError($conn->error);
      }      
    }
    else if(isset($_POST["add"])){
      if(!isset($_POST["med"])){
        $msg = "Medicamentul nu este selectat!";
      }
      if(!is_numeric($_POST["cant"])){
        $msg = "Campul Cantitate nu este de tipul INT!";
      }
      writeError($msg);
    }
    if(isset($_POST["back"])){
      echo "<meta http-equiv=refresh content=\"0; URL=Reteta.php\">";
    }
    $conn->close();
    ?>
  </div>
  </form>
  </div>
  </body>
</html>