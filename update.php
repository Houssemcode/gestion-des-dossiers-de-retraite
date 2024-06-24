<?php
$conn = mysqli_connect("localhost", "root", "", "cnr_retraite");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['id'];

// Fetch user information
$sql = "SELECT * FROM demandeur WHERE id = $user_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching user information: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) == 0) {
    die("User not found");
}

$user = mysqli_fetch_assoc($result);

// Fetch additional information
$demande = []; // Initialize $demande as an empty array

$demande_sql = "SELECT * FROM demande WHERE id = $user_id";
$demande_result = mysqli_query($conn, $demande_sql);

if (!$demande_result) {
    die("Error fetching additional information: " . mysqli_error($conn));
}

if (mysqli_num_rows($demande_result) > 0) {
    $demande = mysqli_fetch_assoc($demande_result);
}

if (isset($_POST['update'])) {
    // Update information if the form is submitted

    $address = $_POST['address'];
    $fam_status = $_POST['fam_status'];
    $wh = $_POST['wh'];
    $payement = $_POST['payement'];
    $bank_name = $_POST['bank_name'];
    $numero_compte = $_POST['numero_compte'];

    // Update the user information in the database
    $update_sql = "UPDATE demandeur SET address = '$address', fam_status = '$fam_status' WHERE id = $user_id";
    $update_result = mysqli_query($conn, $update_sql);

    if ($update_result) {
        // Update additional information in another table (adjust the query based on your database structure)
        $update_info_sql = "UPDATE demande SET wh = '$wh', payement = '$payement', bank_name = '$bank_name', numero_compte = '$numero_compte' WHERE id = $user_id";
        mysqli_query($conn, $update_info_sql);

        echo "<div class='success'>Information updated successfully</div>";
    } else {
        echo "<div class='error'>Error updating information: " . mysqli_error($conn) . "</div>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Information - CNR</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Update Information - CNR</h1>
    <h2>Welcome <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h2>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="address">Address :</label>
        <input type="text" name="address" value="<?php echo $user['address']; ?>" required><br>

        <label for="fam_status">Family Situation :</label>
        <input type="radio" name="fam_status" value="Married" <?php echo ($user['fam_status'] == 'Married') ? 'checked' : ''; ?> required> Married
        <input type="radio" name="fam_status" value="Single" <?php echo ($user['fam_status'] == 'Single') ? 'checked' : ''; ?> required> Single
        <input type="radio" name="fam_status" value="Divorced" <?php echo ($user['fam_status'] == 'Divorced') ? 'checked' : ''; ?> required> Divorced / Divorcé
        <input type="radio" name="fam_status" value="Widowed" <?php echo ($user['fam_status'] == 'Widowed') ? 'checked' : ''; ?> required> Widowed / Veuf<br>

        <!-- Assuming you have another table 'demande' for additional information -->
        <label for="wh">Does the spouse exercise activity? :</label>
        <input type="radio" name="wh" value="Yes" <?php echo ($demande['wh'] == 'Yes') ? 'checked' : ''; ?> required> Yes
        <input type="radio" name="wh" value="No" <?php echo ($demande['wh'] == 'No') ? 'checked' : ''; ?> required> No<br>

        <label for="payement">Payment method :</label>
        <input type="radio" name="payement" value="Mandat" <?php echo ($demande['payement'] == 'Mandat') ? 'checked' : ''; ?> required> Mandat
        <input type="radio" name="payement" value="CCP" <?php echo ($demande['payement'] == 'CCP') ? 'checked' : ''; ?> required> CCP
        <input type="radio" name="payement" value="Bank" <?php echo ($demande['payement'] == 'Bank') ? 'checked' : ''; ?> required> Bank<br>

        <label for="bank_name">Bank Name:</label>
        <input type="text" name="bank_name" value="<?php echo $demande['bank_name']; ?>" required><br>

        <label for="numero_compte">Account number:</label>
        <input type="text" name="numero_compte" value="<?php echo $demande['numero_compte']; ?>" required><br>

        <button type="submit" name="update" value="Update">Update Information</button>
    </form>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Retirement Request - CNR <br> by HOUSSEM Works</p>
    </footer>
</body>
</html>
