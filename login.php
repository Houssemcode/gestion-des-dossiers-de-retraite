<?php
    $conn=mysqli_connect("localhost", "root", "", "cnr_retraite");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }else{
        if (isset($_POST['save'])){
            $email = $_POST['email'];

            $sql = "SELECT * FROM demandeur WHERE email = '$email'";
            $res=mysqli_query($conn, $sql);
            if(mysqli_num_rows($res)>0){
                $row=mysqli_fetch_assoc($res);
                session_start();
                $_SESSION['id']=$row['id'];
                header("location:http://localhost/www/Gestion%20des%20dossiers%20de%20retraite/update.php");            
            }else{
                echo "<div class= 'error'>Email is not exists</div>";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Retirement eligibility test - CNR</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Retirement eligibility test - CNR</h1>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

        <label for="email">Email :</label>
        <input type="text" name="email" required><br>
        <hr>
        
        <button type="submit" name="save" value="Register">Update</button>
    </form>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Retirement Request - CNR <br> by HOUSSEM Works</p>
    </footer>
</body>
</html>