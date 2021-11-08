<?php 
include 'database.php';
include 'validator/admin.php';

$db =  new database();

$reserveringen = $db->select("SELECT * FROM reserveringtotaal",[]);

$reserveringen_columns = array_keys($reserveringen[0]);
$reserveringen_row_data = array_values($reserveringen);

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['print'])) {

    $filename = "Reservering.xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $print_header = false;

    $db = new database();

    $result = $db->select("SELECT * FROM reserveringtotaal",[]);
    if(!empty($result)){
        foreach($result as $row){
            if(!$print_header){
                echo implode("\t", array_keys($row)) ."\n";
                $print_header=true;
            }
            echo implode("\t", array_values($row)) ."\n";
        }
    }
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {

    $klantid = $_POST['klantid'];
    $db->select("DELETE FROM reserveringtotaal WHERE Klantid = :Klantid",[':Klantid' => $klantid]);
    $db->select("DELETE FROM reserveringoverzicht WHERE Klantid = :Klantid",[':Klantid' => $klantid]);
    header("refresh:1;");
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
	<h1>Welkom <?php echo $_SESSION['Gebruiksnaam']; ?></h1>
	<div>
		<a href="logout.php">Logout</a>
	</div>

	<!-- Table Beschikbare kamers met behulp van foreach -->

	<table>
        <h1>Gereserveerde kamers</h1>

        <tr>
            <?php foreach($reserveringen_columns as $reserveringen_column){ ?>

                <th><strong><?php echo $reserveringen_column ?></strong></th>

            <?php } ?>
        </tr>   
            
        <?php foreach($reserveringen_row_data as $reserveringen_row) { ?>
            <tr>
                <?php 
                  foreach($reserveringen_row as $reserveringen_data){
                    echo "<td>$reserveringen_data</td>";
                }?>
                <td>
                   <form method="post">
                   	<input type="hidden" name="klantid" value="<?php echo $reserveringen_row["Klantid"] ?>">
                    <button type="submit" name="delete">Delete</button>
                   	<button type="submit" name="wijzigen">Wijzig</button>
                   </form> 
                </td>
            </tr>
        <?php } ?>

	</table>
	<form method="post">
        <input type="hidden" name="klantid">
        <button type="submit" name="print">Print</button>
    </form> 

</body>
</html>