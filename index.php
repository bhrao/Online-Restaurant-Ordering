<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: home.php");
    exit;
}

// Include config file
require_once "db.php";

// Define variables and initialize with empty values
$email = $password = "";
$err = false;

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["email"]))) {
        $err = true;
    } else {
        $email = mysqli_real_escape_string($db, $_POST['email']);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $err = true;
    } else {
        $password = mysqli_real_escape_string($db, $_POST['password']);
    }

    // Validate credentials
    if (!$err) {
        // Prepare a select statement
        $sql = "SELECT id, email_id, password, is_admin FROM tbl_login WHERE email_id = ?";
        $stmt = mysqli_stmt_init($db);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password, $is_admin);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;
                            $_SESSION["admin"] = $is_admin;

                            // Redirect user to welcome page
                            header("location: home.php");
                        } else {
                            // Display an error message if password is not valid
                            $err = true;
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $err = true;
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($db);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css"/>
    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
</head>
<body>
<div class="wrapper">
    <div class="_3pYe-" style="height: 130px;">
        <div class="_1Tg1D">Login</div>
        <div class="HXZeD"></div>
        <div class="_2r91t">or <a class="_3p4qh" href="register.html">create an account</a></div>

    </div>
    <?php if($err): ?>
        <div class="_2tL9P A7Y41">
            Invalid Email / Password
        </div>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <div class="_3Um38 _3lG1r" data-children-count="1">
                <input type="text" id="email" name="email" class="_381fS" value="<?php echo $email; ?>">
                <label class="_1Cvlf <?php echo strlen($email) != 0  ? "_2tL9P" : ""  ?>" for="email">Email</label>
            </div>
        </div>
        <div>
            <div class="_3Um38 _3lG1r" data-children-count="1">
                <input type="password" id="password" name="password" class="_381fS" value="<?php echo $password; ?>">
                <label class="_1Cvlf <?php echo strlen($password) != 0  ? "_2tL9P" : ""  ?>" for="password">Password</label>
            </div>
        </div>
        <div class="_25qBi _2-hTu"><a class="a-ayg"><input type="submit" style="display: none;">Login</a></div>
    </form>
</div>
</body>

<script>
    $(document).ready(function () {
        $('._381fS').on("focus", function () {
            $('label[for=' + this.id + ']').addClass('_2tL9P');
        });
        $('._381fS').on("blur", function () {
            if (this.value == "") {
                $('label[for=' + this.id + ']').removeClass('_2tL9P');
            }
        });
        $('.a-ayg').on("click", function(){
            $(this).parent().parent().submit();
        })
    });
</script>
</html>