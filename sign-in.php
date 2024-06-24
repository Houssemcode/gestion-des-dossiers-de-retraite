<?php
    $conn=mysqli_connect("localhost", "root", "", "cnr_retraite");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }else{
        if (isset($_POST['save'])){
            $email = $_POST['email'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $birth_date = $_POST['birth_date'];
            $gender = $_POST['gender'];
            $fam_status = $_POST['fam_status'];
            $address = $_POST['address'];
            $contribution_date = $_POST['contribution_date'];

            $sql = "SELECT * FROM demandeur WHERE email = '$email'";
            $res=mysqli_query($conn, $sql);
            if(mysqli_num_rows($res)>0){
                echo "<div class= 'error'>Email already exists</div>";
            }
            else{
                $sql = "SELECT * FROM demandeur WHERE first_name = '$first_name' and last_name = '$last_name' ";
                $res=mysqli_query($conn, $sql);
                if(mysqli_num_rows($res)>0){
                    echo "<div class= 'error'>This user ($first_name, $last_name) already exists</div>";
                }else{
                    $sql="INSERT INTO demandeur (email, first_name, last_name, birth_date, gender, fam_status, address, contribution_date) VALUES('{$email}', '{$first_name}', '{$last_name}', '{$birth_date}', '{$gender}', '{$fam_status}', '{$address}', '{$contribution_date}')";
                    if(mysqli_query($conn,$sql)){
                        $sql="SELECT * FROM demandeur WHERE email = '{$email}'";
                        $res=mysqli_query($conn, $sql);
                        if(mysqli_num_rows($res)>0){
                            $row=mysqli_fetch_assoc($res);
                            session_start();
                            $_SESSION['id']=$row['id'];
                            header("location:http://localhost/www/Gestion%20des%20dossiers%20de%20retraite/desion.php");
                        }
                    }else{
                        echo "error";
                    }
                }
            }
        }
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert a demandeur - CNR</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Insert a demandeur - CNR</h1>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

        <label for="email">Email :</label>
        <input type="text" name="email" required><br>
        <hr>
        <label for="first_name">First Name :</label>
        <input type="text" name="first_name" required><br>
        <hr>
        <label for="last_name">Last Name :</label>
        <input type="text" name="last_name" required><br>
        <hr>
        <label for="birth_date">Birth Date (mm-dd-yyyy) :</label>
        <input type="date" name="birth_date" required><br>
        <hr>
        <label for="gender">Gender :</label>
        <input type="radio" name="gender" value="Male" required> Male
        <input type="radio" name="gender" value="Female" required> Female<br>
        <hr>
        <label for="fam_status">Family Situation :</label>
        <input type="radio" name="fam_status" value="Married" required> Married 
        <input type="radio" name="fam_status" value="Single" required> Single 
        <input type="radio" name="fam_status" value="Divorced" required> Divorced / Divorcé
        <input type="radio" name="fam_status" value="Widowed" required> Widowed / Veuf
        <hr>
        <label for="address">Address :</label>
        <input type="text" name="address" required><br>
        <hr>
        <label for="contribution_date">Contribution Date :</label>
        <input type="date" name="contribution_date" required><br>
        <hr>
        
        <button type="submit" name="save" value="Register">Insert</button>
    </form>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Retirement Request - CNR <br> by HOUSSEM Works</p>
    </footer>
</body>
</html>
