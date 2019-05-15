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
            <input class="btn-default" type = "submit" name="add" value="Adauga"/>
            <input type = "text" name = "cant" class = "form-control input-lg" placeholder="Cantitate" autocomplete="off"/>
      </div>
      <div class="records">
          <?php
    $host = 'mysql';
    $user = 'root';
    $pass = 'asd';
    $conn = new mysqli($host, $user, $pass);
    $msg = "";
    function writeError($e) {
      echo '<div class="topcorner">
      <span class="closebtn" onclick="this.parentElement.style.display="none";">&times;</span>
      '.$e.'</div>';
    }
    function writeSucces($s){
      echo '<div class="topcornerS" id="showMe">'.$s.'</div>';
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
    $sql = "SELECT Id_Locatie, Judet, Oras, Strada, Numar FROM Locatie";
    $result = $conn->query($sql);
    if($result == FALSE){
      writeError($conn->error);
      exit;
    }
    echo '<div class="left">';
    echo '<div class="divTable">';
    echo '<div class="divTableHeading">';
    echo '<div class="divTableCell">Judet</div>';
    echo '<div class="divTableCell">Oras</div>';
    echo '<div class="divTableCell">Strada</div>'; 
    echo '<div class="divTableCell">Numar</div>';
    echo '</div>';
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          echo '<input type="radio" id="'.$row["Id_Locatie"].'" name="loc" value="'.$row["Id_Locatie"].'">';
          echo '<label for="'.$row["Id_Locatie"].'" >';
          echo '<div class="divTableCell">'.$row["Judet"].'</div>';
          echo '<div class="divTableCell">'.$row["Oras"].'</div>';
          echo '<div class="divTableCell">'.$row["Strada"].'</div>';
          echo '<div class="divTableCell">'.$row["Numar"].'</div>';
          echo '</label>';
      }
    }
    echo '</div>';
    echo '</div>';
    $sql = "SELECT Id_Medicament, Denumire FROM Medicamente";
    $result = $conn->query($sql);
    if($result == FALSE){
      writeError($conn->error);
      exit;
    }
    echo '<div class="right">';
    echo '<div class="divTable">';
    echo '<div class="divTableHeading">';
    echo '<div class="divTableCell">Denumire</div>';
    echo '</div>';
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          echo '<input type="radio" id="'.$row["Id_Medicament"].'m" name="med" value="'.$row["Id_Medicament"].'">';
          echo '<label for="'.$row["Id_Medicament"].'m" >';
          echo '<div class="divTableCell">'.$row["Denumire"].'</div>';
          echo '</label>';
      }
    }
    echo '</div>';
    echo '</div>';

    if(isset($_POST["add"]) && isset($_POST["loc"]) && isset($_POST["med"]) && is_numeric($_POST["cant"])){
		$id_loc = $_POST["loc"];
	    $id_med = $_POST["med"];
	    $cant = $_POST["cant"];
	    if($cant > 0){
	      $sql = "UPDATE Depozit SET Cantitate = Cantitate + $cant WHERE Id_Medicament = $id_med AND Id_Locatie = $id_loc"; 

	      $result = $conn->query($sql);
	      if($result == FALSE){
	      	writeError($conn->error);
	      }
	      else{
	      	writeSucces("Aprovizionare realizata cu succes!");
	      }
	    }
	    else{
	    	writeError("Campul cantitate are valoare negativa sau 0!");
	    }
    }
    else if(isset($_POST["add"])){
    	if(!is_numeric($_POST["cant"])){
    		$msg = "Campul cantitate nu este de tipul INT!";
    	}
    	else if(!isset($_POST["loc"])){
    		$msg = "Locatia nu este selectata!";
    	}
    	else if(!isset($_POST["med"])){
    		$msg = "Medicamentul nu este selectat!";
    	}
    	writeError($msg);
    }

    $conn->close();
    ?>
  </div>
  </form>
  </div>
  </body>
</html>