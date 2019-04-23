<div style="display:table; margin: 0 auto;">
	<button type="submit" onclick="location.href='Aprovizionare.php'"><H3> Aprovizionare</H3></button>
	<button type="submit" onclick="location.href='Clienti.php'"><H3> Clienti</H3></button>
	<button type="submit" onclick="location.href='Vanzare.php'"><H3> Vanzare</H3></button>
	<button type="submit" onclick="location.href='index.php'"><H3> Angajati</H3></button>
</div>
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

$sql = "CREATE DATABASE IF NOT EXISTS myDB";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully <br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

$db_selected = mysqli_select_db($conn, 'myDB');
if (!$db_selected) {
    die ('Can\'t use myDB : ' . mysqli_error()) . "<br>";
}

$sql = "CREATE TABLE IF NOT EXISTS Angajati (
  Id_Angajat int(11) NOT NULL,
  Id_Locatie int(11) NOT NULL,
  Nume varchar(20) NOT NULL,
  Prenume varchar(20) NOT NULL,
  Salariu int(11) NOT NULL,
  Prima int(11) DEFAULT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Angajati table created successfully <br>";
} else {
    echo "Error creating Angajati table: " . $conn->error . "<br>";
}

$sql = "CREATE TABLE IF NOT EXISTS Clienti (
  Id_Client int(11) NOT NULL,
  Nume varchar(20) NOT NULL,
  Prenume varchar(20) NOT NULL,
  Discount varchar(20) NOT NULL,
  Id_Angajat int(11) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Clienti table created successfully <br>";
} else {
    echo "Error creating Clienti table: " . $conn->error . "<br>";
}

$sql = "CREATE TABLE IF NOT EXISTS Depozit (
  Id_Depozit int(11) NOT NULL,
  Id_Pastila int(11) NOT NULL,
  Id_Locatie int(11) NOT NULL,
  Cantitate int(11) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Deopzit table created successfully <br>";
} else {
    echo "Error creating Depozit table: " . $conn->error . "<br>";
}

$sql = "CREATE TABLE IF NOT EXISTS Locatie (
  Id_Locatie int(11) NOT NULL,
  Judet varchar(20) NOT NULL,
  Oras varchar(20) NOT NULL,
  Strada varchar(20) NOT NULL,
  Numar int(11) NOT NULL,
  Altele varchar(20) DEFAULT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Locatie table created successfully <br>";
} else {
    echo "Error creating Locatie table: " . $conn->error . "<br>";
}

$sql = "CREATE TABLE IF NOT EXISTS Pastile (
  `Id_Pastila` int(11) NOT NULL,
  `Denumire` varchar(30) NOT NULL,
  `Compensare` varchar(20) NOT NULL,
  `Pret` int(11) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Pastile table created successfully <br>";
} else {
    echo "Error creating Pastile table: " . $conn->error . "<br>";
}

$sql = "CREATE TABLE IF NOT EXISTS Reteta (
  id_Pastila int(11) NOT NULL,
  Id_Angajat int(11) NOT NULL,
  Denumire varchar(30) NOT NULL,
  Cantitate int(11) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Reteta table created successfully <br>";
} else {
    echo "Error creating Reteta table: " . $conn->error . "<br>";
}
$conn->close();
?>