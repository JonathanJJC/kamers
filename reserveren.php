<?php  
include 'database.php';

$db = new database();

$beschikbare_kamers = $db->select("SELECT * FROM Kamers WHERE Kamerid NOT IN (SELECT Kamerid FROM reserveringoverzicht)",[]);

$row_data = array_values($beschikbare_kamers);

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
		<a href="#">Kamer reserveren</a>
		<a href="contact.php">Contact</a>
		<a href="Home"></a>
		<a href="login.php">Medewerker login</a>
	</div>

	<table>
		<h1>Beschikbare kamers</h1>
		<tr>
		    <th>Kamernummer</th>
		    <th>Reserveren</th>
		</tr>
			<?php foreach ($beschikbare_kamers as $beschikbare_kamer) { ?>
				<tr>
					<td>
						<?php echo $beschikbare_kamer["Kamernummer"]?>			
					</td>
					<td>
						<a href="accept_reservering.php?id=<?php echo $beschikbare_kamer["Kamerid"] ?>">Akkoord</a>
					</td>
				</tr>
			<?php } ?>
	</table>
</body>
</html>