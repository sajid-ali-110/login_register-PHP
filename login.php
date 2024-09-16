<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Login</title>
</head>
<body>

    <?php 
    $errors = array();

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email)) {
            $errors[] = "Email is required.";
        }
        if (empty($password)) {
            $errors[] = "Password is required.";
        }

        if (empty($errors)) {
            require_once("config.php");

            $check = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $check->bindParam(":email", $email, PDO::PARAM_STR);
            $check->execute();

            if ($check->rowCount() > 0) {
                $user = $check->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $user["password"])) {
                    echo "<div class='alert alert-success' role='alert'>Login successful! Redirecting...</div>";
                    
                    header("Location: index.php");
                    exit();
                } else {
                    // Password doesn't match
                    $errors[] = "Incorrect password. Please try again.";
                }
            } else {
                $errors[] = "No account found with this email. Please register first.";
            }
        }
    }
    ?>

    <div class="container">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" role="alert">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="reg_form">
            <h2 align="center">Login Form</h2>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Enter email">
            </div> <br>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
            </div> <br>
            <button type="submit" class="btn btn-primary" name="login">Login</button>
        </form>
    </div>
</body>
</html>
