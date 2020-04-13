<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");//if the session is set to logged in and logged in is false the redirects to login.php
    exit();
}

require_once "config.php";//loads config.php to ensure the database has already been created and if not then creates it.

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {//places POST server request
    if (empty(trim($_POST["confirm_password"]))) {//checks if field is empty
        $confirm_password_err = "Please enter new password.";//asks for new password
    }
    else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Passwords didn't match.";//returns error message if passwrods dont match
        }
    }
    
    if (empty($new_password_err) && empty($confrim_passwrod_err)) {
        $sql = "UPDATE users SET password = ? WHERE id = ?";//inserts new passwrod into database
        
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("id", $param_password,  $param_id);
            
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);//hashes new password within database
            $param_id = $_SESSION["id"];
            if ($stmt->execute()) {
                session_destroy();//destroys session
                header("location: login.php");//sends user to login page.
                exit();
            }
            else {
                echo "Oops!! Something wnet wrong. Please try again later.";
            }
        }
        $stmt->close();
    }
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Reset Password</title>
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body>
        <div class="wrapper">
            <h2>Reset Password</h2>
            <p>Please fill out this form to reset password.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                    <label>New Password</label>
                    <input type="password" placeholder="Password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                    <span class="help-block"><?php echo $new_password_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : '' ?>">
                    <label>Confirm Password</label>
                    <input type="password" placeholder="Confirm Password" name="confirm_password" class="form-control">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                        <a class="btn btn-link" href="welcome.php">Cancel</a>
                </div>
            </form>
        </div>
    </body>
</html>