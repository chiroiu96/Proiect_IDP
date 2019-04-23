<html>
<head>
<div style="display:table; margin: 0 auto;">
	<button type="submit" onclick="location.href='Aprovizionare.php'"><H3> Aprovizionare</H3></button>
	<button type="submit" onclick="location.href='Clienti.php'"><H3> Clienti</H3></button>
	<button type="submit" onclick="location.href='Vanzare.php'"><H3> Vanzare</H3></button>
	<button type="submit" onclick="location.href='index.php'"><H3> Angajati</H3></button>
</div>
</head>
<body>
<br>
<div style="display:table; margin: 0 auto;">
<H2 align='middle'>Introduce client</H2>
<form action = "<?php $_PHP_SELF ?>" method = "POST">
		 Id Client: <input type = "text" name = "id_cl" >
         Nume: <input type = "text" name = "nume" >
         Prenume: <input type = "text" name = "prenume" />
		 Discount: <input type = "text" name = "disc" />
		 Id Angajat: <input type = "text" name = "id_ang" />
         <input type = "submit" name="add"/>
</form>
</div>
<div style="display:table; margin: 0 auto;">
<H2 align='middle'>Sterge client</H2>
<form action = "<?php $_PHP_SELF ?>" method = "POST">
		 Id Client: <input type = "text" name = "id_del" >
         <input type = "submit" name="delete"/>
</form>
</div>
<H2 align='middle'>Lista Clienti</H2>
<?php
	$host = 'mysql';
	$user = 'root';
	$pass = 'rootpassword';
	$conn = new mysqli($host, $user, $pass);

	if ($conn->connect_error) {
	    die("Connection failed: <br>" . $conn->connect_error);
	} else {
	    echo "Connected to MySQL successfully! <br>";
	}
	$db_selected = mysqli_select_db($conn, 'myDB');
	if (!$db_selected) {
    	die ('Can\'t use myDB : ' . mysqli_error()) . "<br>";
	}
	if(isset($_POST["add"]) && $_POST["id_cl"] != "" && $_POST["nume"] != "" && $_POST["prenume"] != "" && $_POST["disc"] != "" && $_POST["id_ang"] != ""){
		$id_cl = $_POST["id_cl"];
		$id_ang = $_POST["id_ang"];
		if(! get_magic_quotes_gpc() ) {
               $nume_cl = addslashes ($_POST['nume']);
               $prenume_cl = addslashes ($_POST['prenume']);
               $discount_cl = addslashes($_POST["disc"]);
            }else {
               $nume_cl = $_POST['nume'];
               $prenume_cl = $_POST['prenume'];
               $discount_cl = $_POST["disc"];
            }
		$sql = "INSERT INTO Clienti ". "(Id_Client, Nume, Prenume, Discount, Id_Angajat) ".
		"VALUES ($id_cl, '$nume_cl', '$prenume_cl', '$discount_cl', $id_ang)";

		if ($conn->query($sql) === TRUE) {
		    echo "New record created successfully";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
	if(isset($_POST["delete"]) && $_POST["id_del"] != ""){
		$id_cl = $_POST["id_del"];
		$sql = "DELETE FROM Clienti WHERE Id_Client = $id_cl";

		if ($conn->query($sql) === TRUE) {
		    echo "New record deleted successfully";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
	$sql = "SELECT Id_Client, Nume, Prenume, Discount, Id_Angajat FROM Clienti";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    // output data of each row
		echo '<table style="width:100%" border = "1">';
	  	echo '<tr>';
	    echo '<th>Id Client</th>';
	    echo '<th>Nume</th>'; 
	    echo '<th>Prenume</th>';
		echo '<th>Discount</th>';
		echo '<th>Id Angajat</th>';
	  	echo '</tr>';
	    while($row = $result->fetch_assoc()) {
	        echo '<tr>';
			echo '<td align="center">'.$row["Id_Client"].'</td>';
			echo '<td align="center">'.$row["Nume"].'</td>';
			echo '<td align="center">'.$row["Prenume"].'</td>';
			echo '<td align="center">'.$row["Discount"].'</td>';
			echo '<td align="center">'.$row["Id_Angajat"].'</td>';
			echo '</tr>';
	    }
	    echo '</table>';
	} else {
    	echo "0 results";
	}

	$conn->close();
?>
</body>
</html>