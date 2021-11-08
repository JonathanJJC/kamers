<?php

//<-------------------------( Functie connecteren met database )------------------------->

class database{
    
    private $db_server;
    private $db_username;
    private $db_password;
    private $db_name;
    private $db;

    function __construct(){

        $this->db_server = 'localhost';
        $this->db_username = 'root';
        $this->db_password = '';
        $this->db_name = 'hotel_der_tuin';

        $dsn = "mysql:host=$this->db_server;dbname=$this->db_name;charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
             $this->db = new PDO($dsn, $this->db_username, $this->db_password, $options);
        } catch (\PDOException $e) {
             throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

//<-------------------------( Functie select data )------------------------->

    public function select($statement, $named_placeholder){

        // prepared statement (send statement to server  + checks syntax)
        $statement = $this->db->prepare($statement);

        $statement->execute($named_placeholder);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    //<-------------------------( Functie accept reservering )------------------------->

    public function accept_reservering($kamerid, $kamernummer, $naam, $adres, $plaats, $postcode, $telefoonnummer, $van, $tot){

        try{

            $sql = "INSERT INTO klant VALUES(NULL, :naam, :adres, :plaats, :postcode, :telefoonnummer)";

            $this->db->beginTransaction();
            $statement = $this->db->prepare($sql);
            $statement->execute([
            'naam' => $naam,
            'adres' => $adres,
            'plaats' => $plaats,
            'postcode' => $postcode,
            'telefoonnummer' => $telefoonnummer,
            ]);

            $klantid = $this->db->lastInsertId();

            // commit database changes
            if ($this->db->commit()) {

                $insertreserveringoverzicht = "INSERT INTO reserveringoverzicht VALUES  
                (NULL, :klantid, :kamerid, :van, :tot, :naam, :kamernummer)";
                $stmt = $this->db->prepare($insertreserveringoverzicht);
                $stmt->execute([
                'klantid' => $klantid,
                'kamerid' => $kamerid,
                'van' => $van,
                'tot' => $tot,
                'naam' => $naam,
                'kamernummer' => $kamernummer,
                ]);

                $reserveringid = $this->db->lastInsertId();

                $insertreserveringtotaal = "INSERT INTO reserveringtotaal VALUES  
                (:reserveringid, :klantid, :kamerid, :van, :tot, :naam, :adres, :plaats, :postcode, :telefoonnummer)";
                $stmt = $this->db->prepare($insertreserveringtotaal);
                $stmt->execute([
                'reserveringid' => $reserveringid,   
                'klantid' => $klantid,
                'kamerid' => $kamerid,
                'van' => $van,
                'tot' => $tot,
                'naam' => $naam,
                'adres' => $adres,
                'plaats' => $plaats,
                'postcode' => $postcode,
                'telefoonnummer' => $telefoonnummer
                ]);

                echo "<strong>Reservering geplaatst</strong>";
                header("refresh:2;uitreksel.php?id=$klantid");
            }

        }catch (Exception $e){
            // undo databasechanges in geval van error
            $this->db->rollback();
            throw $e;
        }
    }

    //<-------------------------( Functie insert first admin )------------------------->

    public function insert_admin() {

        try{
            $this->db->beginTransaction();
            $hashed_password = password_hash('admin', PASSWORD_DEFAULT);
            $sql = "INSERT IGNORE INTO Medewerker VALUES 
            (NULL, :Naam, :Gebruiksnaam, :Wachtwoord)";
            $statement = $this->db->prepare($sql);
            $statement->execute([
                'Naam' => 'admin',
                'Gebruiksnaam' => 'admin',
                'Wachtwoord' => $hashed_password
                ]);

            // commit database changes
            if ($this->db->commit()) {}

        }catch (Exception $e){
            // undo databasechanges in geval van error
            $this->db->rollback();
            throw $e;
        }
    }

    //<-------------------------( Functie inloggen )------------------------->

    public function login($gebruiksnaam, $wachtwoord) {

        $sql = "SELECT * FROM medewerker WHERE gebruiksnaam = :gebruiksnaam";

        $statement = $this->db->prepare($sql);

        $statement->execute(['gebruiksnaam' => $gebruiksnaam]); 
     
        $result = $statement->fetch();

        if (is_array($result) && count($result) > 0) {

            $hashed_password = $result['Wachtwoord'];

            
            if ($gebruiksnaam && password_verify($wachtwoord, $hashed_password)) {

                session_start();
                // save userdata in session variables
                $_SESSION['Medewerkerid'] = $result['Medewerkerid'];
                $_SESSION['Naam'] = $result['Naam'];
                $_SESSION['Gebruiksnaam'] = $result['Gebruiksnaam'];
                
                $_SESSION['loggedin'] = true;
                
                echo "<div id=succes><strong>Inloggen gelukt</strong></div>";

                header("refresh:2; beschikbarekamers.php");
                     
            }else{
                echo "wachtoord of email incorrect, probeer opnieuw";
            }

        }else{
            echo "gebruiker bestaat niet";
        }   
    }
} 