<?php  
include 'database.php';

$db = new database();

$beschikbare_kamers = $db->select("SELECT * FROM Kamers WHERE Kamerid = :Kamerid AND Kamerid NOT IN (SELECT Kamerid FROM reserveringoverzicht)",[':Kamerid' => $_GET['id']]);

foreach ($beschikbare_kamers as $beschikbare_kamer) {}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accept_reservering'])){

  $kamerid = htmlspecialchars(trim($_POST['kamerid']));
  $kamernummer = $beschikbare_kamer["Kamernummer"];

  $van = htmlspecialchars(trim($_POST['van']));
  $tot = htmlspecialchars(trim($_POST['tot']));

  $naam = htmlspecialchars(trim($_POST['naam']));
  $adres = htmlspecialchars(trim($_POST['adres']));
  $plaats = htmlspecialchars(trim($_POST['plaats']));
  $postcode = htmlspecialchars(trim($_POST['postcode']));  
  $telefoonnummer = htmlspecialchars(trim($_POST['telefoonnummer']));    

  $db = new database();

  $db->accept_reservering($kamerid, $kamernummer, $naam, $adres, $plaats, $postcode, $telefoonnummer, $van, $tot);
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Reserveren Hoterl der duin</title>
</head>
<body>
	<div>
		<a href="index.php">Over ons</a>
		<a href="reserveren.php">Kamer reserveren</a>
		<a href="contact.php">Contact</a>
		<a href="Home"></a>
		<a href="login.php">Medewerker login</a>
	</div>

	<form method="post">
		<h1>Kamer <?php echo $beschikbare_kamer['Kamernummer'] ?> reserveren</h1>
		<input type="hidden" name="kamerid" value="<?php echo $beschikbare_kamer["Kamerid"] ?>">

		<h4>Van</h4>
		<input required type="date" name="van">

		<h4>Tot</h4>
		<input required type="date" name="tot">

		<h4>Naam</h4>
		<input required type="text" name="naam">

		<h4>Adres</h4>
		<input required type="text" name="adres">

		<h4>Plaats</h4>
		<input required type="text" name="plaats">

		<h4>Postcode</h4>
		<input required type="text" name="postcode">

		<h4>Telefoonnummer</h4>
		<input required type="number" name="telefoonnummer">

		<button type="submit" name="accept_reservering">Accept reservering</button>
	</form>
</body>
</html>