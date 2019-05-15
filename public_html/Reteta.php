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
      <div class="records">
          <form action = "<?php $_PHP_SELF ?>" method = "POST">
            <div class="list">
             <div class="records">
              <input class="btn-default" type = "submit" name="add" value="Adauga"/>
            
    <?php
    $host = 'mysql';
    $user = 'root';
    $pass = 'asd';
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
    $sql = "SELECT * FROM Reteta";
    $result = $conn->query($sql);
    if($result == FALSE){
      writeError($conn->error);
      exit;
    }
    $nr_ret = $result->num_rows;
    $_SESSION['nr_reteta']= $nr_ret;
    $pret_total = 0;

    $sql = "SELECT Id_Medicament_Reteta, Id_Medicament, Cantitate FROM Medicamente_reteta WHERE Id_Reteta = $nr_ret";
    $result = $conn->query($sql);
    if($result == FALSE){
      writeError($conn->error);
      exit;
    }
    $iduri = array();
    $cantitati = array();
    if ($result->num_rows > 0) {
      echo '<input class="btn-default" type = "submit" name="del" value="Goleste"/>
          </div>
            <input class="btn-default" type = "submit" name="comanda" value="Comanda"/>
            </div></div><div class="records"> ';
      echo '<div class="divTable">';
      echo '<div class="divTableHeading">';
      echo '<div class="divTableCell">Medicament</div>';
      echo '<div class="divTableCell">Cantitate</div>';
      echo '<div class="divTableCell">Pret</div>'; 
      echo '</div>';
      while($row = $result->fetch_assoc()) {
          echo '<div class="divTableRow">';
          $id_med = $row["Id_Medicament"];
          $sql = "SELECT Denumire, Pret FROM Medicamente WHERE Id_Medicament = $id_med";
          $result_den = $conn->query($sql);
          if($result_den == FALSE){
            writeError($conn->error);
            exit;
          }
          $row_den = $result_den->fetch_assoc();
          $pret_total += $row_den["Pret"] * $row["Cantitate"];
          echo '<div class="divTableCell">'.$row_den["Denumire"].'</div>';
          echo '<div class="divTableCell">'.$row["Cantitate"].'</div>';
          echo '<div class="divTableCell">'.$row_den["Pret"].'</div>';
          echo '</div>';
          array_push($iduri, $row["Id_Medicament"]);
          array_push($cantitati, $row["Cantitate"]);
      }
      echo '<div class="divTableFoot">';
      echo '<div class="divTableCell">Pret Total</div>';
      echo '<div class="divTableCell"></div>';
      echo '<div class="divTableCell">'.$pret_total.'</div>';
      echo '</div>';
      echo '</div>';
      echo '</div></form></div>';
      if(isset($_POST["comanda"])){
        $id_cl = $_SESSION['client'];
        $sql = "INSERT INTO Reteta (Id_Reteta, Id_Client, Data, Pret) VALUES($nr_ret, $id_cl, CURDATE(),$pret_total)";
        $result=$conn->query($sql);
        if($result){
          $sql = "SELECT * FROM Reteta WHERE Id_Client = $id_cl";
          $result1 = $conn->query($sql);
          if($result1){
            $nr_ret = $result1->num_rows;
            if($nr_ret <= 10){
              $sql = "UPDATE Clienti SET Discount = $nr_ret WHERE Id_Client = $id_cl";
              $result2 = $conn->query($sql);
              if($result2 == FALSE){
                $err = 1;
                writeError($conn->error);
              }
            }
            if(!$err){
              $_SESSION["succes"] = "Comanda inregistrata cu scucces!";
              echo "<meta http-equiv=refresh content=\"0; URL=Vanzare.php\">";
            }
          }
          else{
            writeError($conn->error);
          }
        }
        else{
          writeError($conn->error);
        }
      }
      if(isset($_POST["del"])){
        for ($i = 0; $i < count($iduri); $i++) {
          $sql = "UPDATE Depozit SET Cantitate = Cantitate + $cantitati[$i] WHERE Id_Medicament = $iduri[$i] AND Id_Locatie = $locatie_client";
          $result = $conn->query($sql);
          if($result == FALSE){
            $err = 1;
            writeError($conn->error);
            break;
          }
        }
        if(!$err){
          $sql = "DELETE FROM Medicamente_reteta WHERE Id_Reteta = $nr_ret";
          $result = $conn->query($sql);
          if($result){
            echo "<meta http-equiv=refresh content=\"0; URL=Reteta.php\">";
          }
          else{
            writeError($conn->error);
          }
        }
      }
    } else {
      echo '</div></div></form></div>
      <div class="records"> ';
      echo '<div class="divTable">';
      echo '<div class="divTableHeading">';
      echo '<div class="divTableCell">Medicament</div>';
      echo '<div class="divTableCell">Cantitate</div>';
      echo '<div class="divTableCell">Pret</div>'; 
      echo '</div>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }

    if(isset($_POST["add"])){
        echo "<meta http-equiv=refresh content=\"0; URL=AdaugaMedicament.php\">";
    }
    
    $conn->close();
    ?>
  </body>
</html>