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
          <form action = "<?php $_PHP_SELF ?>" method = "POST">
            <div class="records">
            <input class="btn-default" type = "submit" name="add" value="Adauga"/>
            <input class="btn-default" type = "submit" name="del" value="Sterge"/>
          </div>
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

    $sql = "CREATE TABLE IF NOT EXISTS Clienti (
      Id_Client int(11) NOT NULL,
      Id_Locatie int(11) NOT NULL,
      Nume varchar(20) NOT NULL,
      Prenume varchar(20) NOT NULL,
      Discount int(2) NOT NULL
    )";
    $result = $conn->query($sql);
    if($result == FALSE){
      writeError($conn->error);
      exit;
    }

    $sql = "CREATE TABLE IF NOT EXISTS Depozit (
      Id_Depozit int(11) NOT NULL,
      Id_Medicament int(11) NOT NULL,
      Id_Locatie int(11) NOT NULL,
      Cantitate int(11) NOT NULL
    )";
    $result = $conn->query($sql);
    if($result == FALSE){
      writeError($conn->error);
      exit;
    }
    $sql = "CREATE TABLE IF NOT EXISTS Locatie (
      Id_Locatie int(11) NOT NULL,
      Judet varchar(20) NOT NULL,
      Oras varchar(20) NOT NULL,
      Strada varchar(20) NOT NULL,
      Numar int(11) NOT NULL
    )";
    $result=$conn->query($sql);
    if($result == FALSE){
      writeError($conn->error);
      exit;
    }
    $sql = "CREATE TABLE IF NOT EXISTS Medicamente (
      `Id_Medicament` int(11) NOT NULL,
      `Denumire` varchar(30) NOT NULL,
      `Pret` int(11) NOT NULL
    )";
    $result = $conn->query($sql);
    if($result == FALSE){
      writeError($conn->error);
      exit;
    }
    $sql = "CREATE TABLE IF NOT EXISTS Reteta (
      Id_Reteta int(11) NOT NULL,
      Id_Client int(11) NOT NULL,
      Data date NOT NULL,
      Pret int(11) NOT NULL
    )";
    $result=$conn->query($sql);
    if($result == FALSE){
      writeError($conn->error);
      exit;
    }
    $sql = "CREATE TABLE IF NOT EXISTS Medicamente_reteta (
      Id_Medicament_Reteta int(11) NOT NULL,
      Id_Reteta int(11) NOT NULL,
      Id_Medicament int(11) NOT NULL,
      Cantitate int(11) NOT NULL
    )";
    $result=$conn->query($sql);
    if($result == FALSE){
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
    $iduri = array();
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          echo '<input type="checkbox" id="'.$row["Id_Client"].'" name="'.$row["Id_Client"].'">';
          echo '<label for="'.$row["Id_Client"].'" >';
          echo '<div class="divTableCell">'.$row["Id_Client"].'</div>';
          echo '<div class="divTableCell">'.$row["Id_Locatie"].'</div>';
          echo '<div class="divTableCell">'.$row["Nume"].'</div>';
          echo '<div class="divTableCell">'.$row["Prenume"].'</div>';
          echo '<div class="divTableCell">'.$row["Discount"].'</div>';
          echo '</label>';
          array_push($iduri, $row["Id_Client"]);
      }
    } //else {
      //echo "0 results";
    //}
    echo '</div>';
    if(isset($_POST["del"])){
      $bec = 0;
      foreach ($iduri as $i) {
        if(isset($_POST[$i])){
          $bec = 1;
          $sql = "DELETE FROM Clienti WHERE Id_Client = $i";
          if ($conn->query($sql) === TRUE) {
            echo "New record deleted successfully";
          } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
          }
        } 
      }
      if($bec){
        $_SESSION["succes"] = "Stergere realizata cu scucces!";
        echo "<meta http-equiv=refresh content=\"0; URL=index.php\">";
      }
      else{
        writeError("Niciun client nu este selectat!");
      }
    }
    if(isset($_POST["add"])){
      echo "<meta http-equiv=refresh content=\"0; URL=Adauga.php\">";
    }
    $conn->close();
    ?>
  </div>
  </form>
  </div>
  </body>
</html>