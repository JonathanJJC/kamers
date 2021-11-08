<?php
//include de database connectie en alle functies
require "database.php";

$db = new database();

$db->insert_admin();

//login functie
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])){

   // als velden niet zijn ingevuld gaat de code niet door
   if (!empty($_POST["gebruiksnaam"] && !empty($_POST["wachtwoord"]))) {

      $gebruiksnaam = htmlspecialchars(trim($_POST['gebruiksnaam']));
      $wachtwoord = htmlspecialchars(trim($_POST['wachtwoord']));  

      $db = new database();

      $db->login($gebruiksnaam, $wachtwoord);

   }else{
      echo "Bijde velden moeten ingevuld zijn, probeer nogmaals";
   }
}

?>
<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title></title>
</head>
<body>
   <form method="post">
      <h1>Medewerker login</h1>
      <h3>Email</h3>
      <input required type="text" name="gebruiksnaam" placeholder="johndoe@gmail.com">

      <h3>Password</h3>
      <input required type="password" name="wachtwoord" placeholder="**********">

      <br><br>
      <button id="green" type="submit" name="login"><strong>Login</strong></button>
   </form>
</body>
</html>