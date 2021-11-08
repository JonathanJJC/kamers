<?php  
include 'database.php';

$db = new database();

$reserveringtotaal = $db->select("SELECT * FROM reserveringtotaal WHERE klantid = :klantid",[':klantid' => $_GET['id']]);

$columns = array_keys($reserveringtotaal[0]);
$row_data = array_values($reserveringtotaal);


if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['print'])) {

    $filename = "Reservering.xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $print_header = false;

    $db = new database();

    $result = $db->select("SELECT * FROM reserveringtotaal WHERE klantid = :klantid",[':klantid' => $_GET['id']]);
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

	<table>

        <h2>Print hier je reservering details</h2>

        <tr>
            <?php foreach($columns as $column){ ?>

                <th><strong><?php echo $column ?></strong></th>

            <?php } ?>
        </tr>   
            
        <?php foreach($row_data as $rows) { ?>
            <tr>
                <?php 
                  foreach($rows as $data){
                    echo "<td>$data</td>";
                }?>
                <td>
                   <form method="post">
                   	<input type="hidden" name="klantid">
                   	<button type="submit" name="print">Print</button>
                   </form> 
                </td>
            </tr>
        <?php } ?>

    </table>
    <p>let op, dit is de enigste kans om de bevestiging uit te printen</p>
</body>
</html>