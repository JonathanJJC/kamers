<?php 
include 'database.php';
include 'validator/admin.php';

$db =  new database();

$beschikbaar = $db->select("SELECT * FROM Kamers WHERE Kamerid NOT IN (SELECT Kamerid FROM reserveringoverzicht)",[]);

$beschikbaar_columns = array_keys($beschikbaar[0]);
$beschikbaar_row_data = array_values($beschikbaar);

if (count($beschikbaar_row_data) < 2 || 2  ) {
	echo '<script language="javascript">';
	echo 'alert("NOG MAAR 2 KAMERS OVER")';
	echo '</script>';
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_kamer'])){

   
  $kamerid = htmlspecialchars(trim($_POST['kamer_id']));
  $kamernummer = htmlspecialchars(trim($_POST['kamer_nummer']));  

  $db = new database();

  $db->select("UPDATE kamers SET Kamernummer = :kamer_nummer WHERE Kamerid = :kamer_id",['kamer_nummer' => $kamernummer, 'kamer_id' => $kamerid]);

  // header("refresh:2;");
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin interface</title>
</head>
<body>
	<div>
		<a href="beschikbarekamers.php">Beschikbare kamers</a>
		<a href="gereserveerdekamers.php">Gereserveerde kamers</a>
		<a href="klanten.php">Klanten</a>
		<a href="logout.php">Logout</a>
	</div>

	<h1>Welkom <?php echo $_SESSION['Gebruiksnaam']; ?></h1>

	<!-- SQL data omzetten naar tabel met behulp van foreach -->

	<table>
		<tr>
			<th>Kamer id</th>
			<th>Kamernummer</th>
			<th>Edit</th>
		</tr>
		<?php foreach ($beschikbaar AS $schikbaar) { ?>
			<tr>
			<form method="post">
				<td><input type="hidden" name="kamer_id" value="<?php echo $schikbaar["Kamerid"] ?>"><?php echo $schikbaar["Kamerid"] ?></td>
				<td><input type="text" name="kamer_nummer" value="<?php echo $schikbaar["Kamernummer"] ?>"></td>
				<td><button type="submit" name="edit_kamer">EDIT</button></td>	
			</form></tr>
		<?php } ?>
	</table>
</body>
</html>