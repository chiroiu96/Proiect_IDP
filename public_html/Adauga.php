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
          <div class ="list">
            <input class="btn-default" type = "submit" name="add" value="Adauga"/>
          </div>
          <div class="input-group">
            Id Client: <input type = "text" name = "id_cl" class = "form-control input-lg" autocomplete="off"/><br>           
          </div>            
          <div class="input-group">
            Nume: <input type = "text" name = "nume" class = "form-control input-lg" autocomplete="off"/><br>          
          </div>
          <div class="input-group">
            Prenume: <input type = "text" name = "prenume" class = "form-control input-lg" autocomplete="off"/>
          </div>
      <br>
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

        $sql = "SELECT Id_Locatie, Judet, Oras, Strada, Numar FROM Locatie";
        $result = $conn->query($sql);
        if($result == FALSE){
          writeError($conn->error);
          exit;
        }
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

        if(isset($_POST["add"])){
          if(!isset($_POST["loc"])){
            $msg = "Locatia nu este selectata!";
            $err = 1;
          }
          $id_loc = $_POST["loc"];
          $id_cl = $_POST["id_cl"];
          if(!is_numeric($id_cl)){
            $msg = "Campul Id_Client nu este de tipul INT!";
            $err = 1;
          }
          if(! get_magic_quotes_gpc() ) {
              $nume_cl = addslashes ($_POST['nume']);
              $prenume_cl = addslashes ($_POST['prenume']);
          }else {
              $nume_cl = $_POST['nume'];
              $prenume_cl = $_POST['prenume'];
          }
          if(!is_string($nume_cl) || $nume_cl == ""){
            $msg = "Campul Nume nu este de tipul STRING!";
            $err = 1;
          }
          if(!is_string($prenume_cl) || $prenume_cl == ""){
            $msg = "Campul Prenume nu este de tipul STRING!";
            $err = 1;
          }
          if(!$err){
            $sql = "INSERT INTO Clienti ". "(Id_Client, Id_Locatie, Nume, Prenume, Discount) ".
            "VALUES ($id_cl, $id_loc, '$nume_cl', '$prenume_cl', 0)";

            $result = $conn->query($sql);
            if($result == FALSE){
              writeError($conn->error);
            }
            else{
              $_SESSION["succes"] = "Adaugare realizata cu scucces!";
              echo "<meta http-equiv=refresh content=\"0; URL=index.php\">";
            }
          }
          else{
            writeError($msg);
          }
        }
        $conn->close();
      ?>
    </form></div></div>
  </body>
</html>