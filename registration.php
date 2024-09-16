
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Registration</title>
</head>
<body>
    <div class="container">
     <?php 
if (isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    $errors = array();

    if (empty($fullname) || empty($email) || empty($password) || empty($confirmpassword)) {
        array_push($errors, "All fields are required to fill.");
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid.");
    }

    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters.");
    }
    
    if ($password !== $confirmpassword) {
        array_push($errors, "Passwords don't match.");
    }

    require_once "config.php";
    $check = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $check->bindParam("email", $email, PDO::PARAM_STR);
    $check->execute();
    if ($check->rowCount()>0) {
        array_push($errors, "email has bee already used.");
    }
    
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger' role='alert'> $error </div>";
        }
    } else {
        require_once "config.php"; // Include your database connection file

        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (:fullname, :email, :password)");

        // Bind the parameters
        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'> Registration successful! </div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'> There was an error in registration. Please try again later. </div>";
        }
    }
}
?>

        <form method="post" action="registration.php" class="reg_form">
            <h2 align="center">Registration Form</h2>
            <div class="form-group">
                <label for="Username">Username</label>
                <input type="text" class="form-control" id="Username" name="fullname" placeholder="Enter Username">
            </div> <br>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Enter email">
            </div> <br>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
            </div> <br>
            <div class="form-group">
                <label for="ConfirmPassword">Confirm Password</label>
                <input type="password" class="form-control" id="ConfirmPassword" name="confirmpassword" placeholder="Confirm Password">
            </div> <br>
            <button type="submit" class="btn btn-primary" name="submit">Register</button> <br>
            <a href="login.php">already have an account</a>
        </form>
    </div>
</body>
</html>
