<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Retirement eligibility decision - CNR</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Retirement eligibility decision - CNR</h1>
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
        $contribution_date = $row['contribution_date'];

        $currentDate = date("Y-m-d");
        $yearsDifference = strtotime($currentDate) - strtotime($contribution_date);
        $yearsDifference = floor($yearsDifference / (365*24*60*60)); // Calculate the difference in years
        
        $age = strtotime($currentDate) - strtotime($birth_date);
        $age = floor($age / (365*24*60*60)); // Calculate the age in years

        if ($yearsDifference > 50){
            echo "<div class='error'>Rejected because you have <strong> $yearsDifference </strong> years of contribution</div>";
        }else {
            if ($age >= 60 && $gender == "Male"){
                if ($yearsDifference >= 15) { // 15 years
                    session_start();
                    $_SESSION['id'] = $row['id'];
                    header("Location: http://localhost/www/Gestion%20des%20dossiers%20de%20retraite/demende.php");
                } elseif ($yearsDifference > 5) { // 5 years
                    session_start();
                    $_SESSION['id'] = $row['id'];
                    header("Location: http://localhost/www/Gestion%20des%20dossiers%20de%20retraite/demende.php");
                } else {
                    echo "<div class='error'>Rejected because you have <strong> $yearsDifference </strong> years of contribution</div>";
                }
            }elseif ($age >= 55 && $gender == "Female"){
                if ($yearsDifference >= 15) { // 15 years
                    session_start();
                    $_SESSION['id'] = $row['id'];
                    header("Location: http://localhost/www/Gestion%20des%20dossiers%20de%20retraite/demende.php");
                } elseif ($yearsDifference > 5) { // 5 years
                    session_start();
                    $_SESSION['id'] = $row['id'];
                    header("Location: http://localhost/www/Gestion%20des%20dossiers%20de%20retraite/demende.php");
                } else {
                    echo "<div class='error'>Rejected because you have <strong> $yearsDifference </strong> years of contribution</div>";
                }
            }else {
                echo "<div class='error'>Rejected because you are <strong> $gender </strong> and <strong>$age</strong> years old</div>";
            }
        } 
    }else {
        echo "<div class='error'>Email is not exists</div>";
    }
?>  
    </form>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Retirement Request - CNR <br> by HOUSSEM Works</p>
    </footer>
</body>
</html>