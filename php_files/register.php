<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "attendance_system");

// Stop if connection fails
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $programme = $_POST['programme'];
    $year_of_study = $_POST['year_of_study'];
    $reg_no = $_POST['reg_no'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $residence = $_POST['residence'];

    // Save to database
    $sql = "INSERT INTO member_details (name, programme, year_of_study, reg_no, dob, phone, residence)
            VALUES ('$name', '$programme', '$year_of_study', '$reg_no', '$dob', '$phone', '$residence')";

    if ($conn->query($sql) === TRUE) {
        // Get user ID
        $last_id = $conn->insert_id;
        $user_id = "SCOM" . str_pad($last_id, 3, "0", STR_PAD_LEFT);
        $conn->query("UPDATE member_details SET user_id='$user_id' WHERE id=$last_id");
        
        // Redirect to a separate result page with the user ID
        header("Location: registration_success.php?user_id=$user_id&name=" . urlencode($name));
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}


$conn->close();
?>
