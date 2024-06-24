<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notification de Retraite - CNR</title>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
    <div class="notification">
        <center><h1>Notification de Retraite - CNR</h1></center>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
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

            // Prepare the SQL query to retrieve the user details
            $demandeurQuery = "SELECT * FROM demandeur WHERE id = '$id'";

            // Execute the query for user details
            $demandeurResult = $conn->query($demandeurQuery);

            // Get the user's information from the query result
            if ($demandeurResult && $demandeurResult->num_rows > 0) {
                $demandeurRow = $demandeurResult->fetch_assoc();

                $email = $demandeurRow["email"];
                $first_name = $demandeurRow["first_name"];
                $last_name = $demandeurRow["last_name"];
                $birth_date = $demandeurRow['birth_date'];
                $gender = $demandeurRow['gender'];
                $fam_status = $demandeurRow['fam_status'];
                $address = $demandeurRow["address"];
                $contribution_date = $demandeurRow['contribution_date'];

                // Prepare the SQL query to retrieve retirement details
                $demandeQuery = "SELECT * FROM demande WHERE id = '$id'";

                // Execute the query for retirement details
                $demandeResult = $conn->query($demandeQuery);

                // Get the retirement information from the query result
                if ($demandeResult && $demandeResult->num_rows > 0) {
                    $demandeRow = $demandeResult->fetch_assoc();

                    $numero = $demandeRow['numero'];
                    $regime = $demandeRow['regime'];
                    $spmm = $demandeRow['spmm'];
                    $wh = $demandeRow['wh'];
                    $payement = $demandeRow['payement'];
                    $bank_name = $demandeRow['bank_name'];
                    $numero_compte = $demandeRow['numero_compte'];
                    $taux_de_allocation = $demandeRow['taux_de_allocation'];
                    $avantage_principal = $demandeRow['avantage_principal'];
                    $a_s = $demandeRow['a_s'];
                    $irg = $demandeRow['irg'];
                    $montant_mensuel = $demandeRow['montant_mensuel'];
                    $allocation = $demandeRow['allocation'];
                    if ($allocation = 0) $m = "Pension rate (Taux de la pension)";
                    else $m = "Allowance rate (Taux de l’allocation)";

                    // Display the retrieved information
                 // Output formatted details in an official manner
                 echo "<center><p>Document: $regime$numero</p></center>";
                 echo "<h2><strong>Retirement Notification</strong></h2>";
                 echo "<p><strong>Issued to:</strong> $first_name, $last_name</p>";
                 echo "<p><strong>Email:</strong> $email</p>";
                 echo "<hr>";
                 echo "<h2><strong>Personal Details</strong></h2>";
                 echo "<p><strong>Birth Date:</strong> $birth_date</p>";
                 echo "<p><strong>Gender:</strong> $gender</p>";
                 echo "<p><strong>Family Situation:</strong> $fam_status</p>";
                 echo "<p><strong>Address:</strong> $address</p>";
                 echo "<p><strong>Contribution Date:</strong> $contribution_date</p>";
                 echo "<hr>";
                 echo "<h2><strong>Retirement Information</strong></h2>";

                 echo "<table style='border-collapse: collapse; margin-top: 10px; margin-left: auto; margin-right: auto;'>";
                 echo "<tr style='background-color: #f2f2f2;'><td><strong>Does the spouse exercise activity?</strong> (Majoration pour conjoint à charge O||N) </td><td>$wh</td></tr>";
                 echo "<tr style='background-color: #e6e6e6;'><td><strong>Average monthly post salary</strong> (SPMM)</td><td>$spmm DA</td></tr>";
                 echo "<tr style='background-color: #f2f2f2;'><td><strong>Payment method</strong></td><td>$payement</td></tr>";
                 echo "<tr style='background-color: #e6e6e6;'><td><strong>Bank Name</strong></td><td>$bank_name</td></tr>";
                 
                 $t = $taux_de_allocation * 100;
                 
                 echo "<tr style='background-color: #f2f2f2;'><td><strong>$m</strong></td><td>$t%</td></tr>";
                 echo "<tr style='background-color: #e6e6e6;'><td><strong>Main advantage</strong> (Avantage principal)</td><td>$avantage_principal DA</td></tr>";
                 echo "<tr style='background-color: #f2f2f2;'><td><strong>Social insurances</strong> (Assurances sociales)</td><td>$a_s DA</td></tr>";
                 echo "<tr style='background-color: #e6e6e6;'><td><strong>IRG</strong></td><td>$irg DA</td></tr>";
                 echo "<tr style='background-color: #f2f2f2;'><td><strong>Net monthly amount of pension</strong> (Montant net mensuel de la pension)</td><td>$montant_mensuel DA</td></tr>";
                 $currentDate = date("Y-m-d");
                 echo "</table>";
                 echo "<br>";
                 echo "<p>$currentDate</p>";

             } else {
                 echo "No data available.";
             }
            } else {
                echo "No user details found.";
            }
            $conn->close(); // Close the database connection
        ?>
        </form>

    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Retirement Request - CNR <br> by HOUSSEM Works</p>
    </footer>
</body>
</html>