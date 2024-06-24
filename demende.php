<?php
    // Start the session
    session_start();

    // Check if the user is logged in

    // Get the user's email from the session
    $id = $_SESSION['id'];

    // Connect to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cnr_retraite";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to obtain the last assigned number for a given regime
    function getLastNumber($conn, $regime) {
        $sql = "SELECT MAX(numero) AS last_number FROM demande WHERE regime = '$regime'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        return $row['last_number'];
    }

    // Function to assign the next dossier number for a given regime
    function assignNextNumber($conn, $regime) {
        $lastNumber = getLastNumber($conn, $regime);

        if ($lastNumber === null) {
            $lastNumber = 0;

            switch ($regime) {
                case "A":
                    $lastNumber = 0;
                    break;
                
                case "B":
                    $lastNumber = 100000;
                    break;

                case "C":
                    $lastNumber = 200000;
                    break;
                case "F":
                    $lastNumber = 300000;
                    break;
                
                case "P":
                    $lastNumber = 400000;
                    break;

                case "S":
                    $lastNumber = 500000;
                    break;
            }

        }

        // Increment the last number
        $nextNumber = $lastNumber + 1;

        return $nextNumber;
    }

    // Prepare the SQL query to retrieve the user with the given email
    $sql = "SELECT * FROM demande WHERE id = '$id'";

    // Execute the query
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $sql="SELECT * FROM demande WHERE id = '{$id}'";
        $res=mysqli_query($conn, $sql);
        if(mysqli_num_rows($res)>0){
            $row=mysqli_fetch_assoc($res);
            session_start();
            $_SESSION['id_file']=$row['id_file'];
            header("location:http://localhost/www/Gestion%20des%20dossiers%20de%20retraite/affichage.php");
        }
    }else{
        // Prepare the SQL query to retrieve the user with the given email
        $sql = "SELECT * FROM demandeur WHERE id = '$id'";

        // Execute the query
        $result = $conn->query($sql);

        // Get the user's information from the query result
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $id = $row["id"];
            $email = $row["email"];
            $first_name = $row["first_name"];
            $last_name = $row["last_name"];
            $birth_date = $row['birth_date'];
            $gender = $row['gender'];
            $fam_status = $row['fam_status'];
            $address = $row["address"];
            $contribution_date = $row['contribution_date'];
        }
        if (isset($_POST['save'])){
            $salaires1 = $_POST['salaires1'];
            $salaires2 = $_POST['salaires2'];
            $salaires3 = $_POST['salaires3'];
            $salaires4 = $_POST['salaires4'];
            $salaires5 = $_POST['salaires5'];
            $regime = $_POST['regime'];
            if($salaires1 < 300000 && $salaires2 < 300000 && $salaires3 < 300000 && $salaires4 < 300000 && $salaires5 < 300000){
                $wh = "Yes";
                if ($fam_status == "Married"){
                    $wh = $_POST['wh'];
                }
                $up = $_POST['up'];
                $payement = $_POST['payement'];
                $bank_name = $_POST['bank_name'];
                $numero_compte = $_POST['numero_compte'];

                // Operation
                $currentDate = date("Y-m-d");
                $yearsDifference = strtotime($currentDate) - strtotime($contribution_date);
                $yearsDifference = floor($yearsDifference / (365*24*60*60)); // Calculate the difference in years

                $taux_de_allocation = $yearsDifference * 2.5;
                $taux_de_allocation = min($taux_de_allocation , 80);
                $taux_de_allocation = $taux_de_allocation / 100;

                $spmm = $salaires1 + $salaires2 + $salaires3 + $salaires4 + $salaires5;
                $spmm = $spmm / 5;
                
                $whp = 0;
                if ($wh == "No") $whp = 2500.00;

                $avantage_principal= $spmm * $taux_de_allocation;

                $a_s = $avantage_principal * 0.02;

                if($avantage_principal<20000) $irg = 0;
                if(($avantage_principal>=20000) && ($avantage_principal<30000)) $irg = $avantage_principal*0.05;
                if($avantage_principal>=30000) $irg = $avantage_principal*0.1;
                
                $montant_mensuel = $avantage_principal - ($a_s + $irg) + $whp ;

                $allocation = 0;
                if ($yearsDifference > 5 && $yearsDifference < 15) {
                    $allocation = 1;
                }

                if ($allocation == 0){
                    $snmg = 20000;
                    $montant_mensuel = max(min($montant_mensuel, 15 * $snmg), 0.75 * $snmg);
                }

                //Stoke information
                $nextNumber = 0;
                $nextNumber = assignNextNumber($conn, $regime);
                $sql = "INSERT INTO demande (id, numero, regime, wh, payement, bank_name, numero_compte, taux_de_allocation, allocation, spmm, avantage_principal, a_s, irg, montant_mensuel) 
                VALUES ('$id', '$nextNumber', '$regime', '$wh', '$payement', '$bank_name', '$numero_compte', '$taux_de_allocation', '$allocation','$spmm', '$avantage_principal', '$a_s', '$irg', '$montant_mensuel')";

                if ($conn->query($sql) === TRUE) {
                    // No need to fetch anything for an INSERT operation
                    $_SESSION['id'] = $id;
                    header("location:http://localhost/www/Gestion%20des%20dossiers%20de%20retraite/affichage.php");
                } else {
                    echo "Error inserting record into demande: " . $conn->error;
                }
            }else{
                echo "<div class='error'>Salaires exceeds the required limit <strong> 300000 DA </strong></div>";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Retirement Request - CNR</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Retirement Request - CNR</h1>
    <h2>Welcome <?php echo "$first_name, $last_name"; ?></h2>
    <h3>You can claim retirement</h3>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

        <label for="salaires">Average monthly Salaries for each of the last 5 years :</label>

        1 : <input type="number" name="salaires1" required><br>
        2 : <input type="number" name="salaires2" required><br>
        3 : <input type="number" name="salaires3" required><br>
        4 : <input type="number" name="salaires4" required><br>
        5 : <input type="number" name="salaires5" required><br>
        <hr>
        <label for="regime">Select your Regime:</label>
        <input type="radio" name="regime" value="A" required> Agricole
        <input type="radio" name="regime" value="B" required> Base
        <input type="radio" name="regime" value="C" required> Cheminots
        <input type="radio" name="regime" value="F" required> Fonctionnaires
        <input type="radio" name="regime" value="P" required> Anticipe
        <input type="radio" name="regime" value="S" required> Sonelgaz
        <hr>
        <?php 
            if ($fam_status == "Married"){
                echo "
                <label for='wh'>Does the spouse exercise activity? :</label>
                <input type='radio' name='wh' value='Yes' required> Yes
                <input type='radio' name='wh' value='No' required> No<br>
                <hr>";
            }
        ?>
        <label for="payement">Payment method :</label>
        <input type="radio" name="payement" value="Mandat" required> Mandat
        <input type="radio" name="payement" value="CCP" required> CCP
        <input type="radio" name="payement" value="Bank" required> Bank<br>
        <hr>
        <label for="bank_name">Bank Name:</label>
        <input type="text" name="bank_name" required><br>
        <hr>
        <label for="numero_compte">Account number:</label>
        <input type="text" name="numero_compte" required><br>
        <hr>
        <button type="submit" name="save" value="Register">Request</button>
    </form>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Retirement Request - CNR <br> by HOUSSEM Works</p>
    </footer>
</body>
</html>