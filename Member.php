<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Member</title>
    </head>
    <body>
    <?php
            echo "<h3>This is members only</h3>"
        ?>
    <?php
    session_start();
    if(isset($_SESSION['signup'])){
        // Database connection details
    $host = "localhost"; // Change to your MySQL server hostname
    $username = "root";  // Change to your MySQL username
    $password = "root";  // Change to your MySQL password
    $database = "mockup"; // Change to your database name

    // Create a database connection
    $mysqli = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Create a prepared statement for inserting user data into the user table
$userStmt = $mysqli->prepare("INSERT INTO user (User_FName, User_LName, Username, User_DOB, User_Blacklist, Member_Flag, Member_Type, Member_Faculty, Member_Year, General_Flag, Admin_Flag) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

// Create a prepared statement for inserting login data into the login table
$loginStmt = $mysqli->prepare("INSERT INTO login (User_ID, username, password) VALUES (?, ?, ?)");

if ($userStmt && $loginStmt) {
    // Bind values to the placeholders for the user table
    $userFName = $_SESSION['fname'];
    $userLName = $_SESSION['lname'];
    $username = $_SESSION['username'];
    $userDOB = $_SESSION['dob'];
    $userBlacklist = 0; // Assuming default is 0
    $memberFlag = 1; // Assuming this is for members
    $memberType = $_SESSION['memberType'];
    $memberFaculty = $_SESSION['faculty'];
    $memberYear = $_SESSION['doe'];
    $generalFlag = 0; // Assuming this is not a general user
    $adminFlag = 0; // Assuming this is not an admin user

    $userStmt->bind_param("ssssiiisiii", $userFName, $userLName, $username, $userDOB, $userBlacklist, $memberFlag, $memberType, $memberFaculty, $memberYear, $generalFlag, $adminFlag);

    // Execute the statement for inserting user data
    if ($userStmt->execute()) {
        // Get the User_ID of the inserted user
        $user_id = $userStmt->insert_id;

        // Now, bind values to the placeholders for the login table
        $loginUsername = $_SESSION['username'];
        $loginPassword = $_SESSION['passwd']; // Assuming password is "root"

        $loginStmt->bind_param("iss", $user_id, $loginUsername, $loginPassword);

        // Execute the statement for inserting login data
        if ($loginStmt->execute()) {
            // Both user and login data have been successfully inserted
        } else {
            echo "Error inserting login data: " . $loginStmt->error;
        }
    } else {
        echo "Error inserting user data: " . $userStmt->error;
    }

    // Close both statements
    $userStmt->close();
    $loginStmt->close();
} else {
    echo "Error preparing statements: " . $mysqli->error;
}

// Close the database connection
$mysqli->close();
    }
    // Check if the user is logged in
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $userType = $_SESSION['userType'];
        $memberType = $_SESSION['memberType'];
        $faculty = $_SESSION['faculty'];
        $doe = $_SESSION['doe'];

        echo "Welcome, $username!<br>";
        echo "User Type: $userType<br>";

        if ($userType == "member") {
            echo "Member Type: $memberType<br>";
            echo "Faculty: $faculty<br>";
            echo "Date of Enrollment: $doe<br>";
        }
    } else {
        // Handle the case when the user is not logged in or session data is not set
        echo "You are not logged in.";
    }
    ?>
    </body>
</html>
