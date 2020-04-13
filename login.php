<?php
$page_ID="Login";
include ('Website Stats/Website_stats.php');
session_start(); //checks to see if session is already active. 
include_once('error_log.php');

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php"); //if the session is set to logged in and logged in is true the redirects to welcome.php
    exit;
}

require_once("config.php");//loads config.php to ensure the database has already been created and if not then creates it.

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) { //checks if username field is empty.
        $username_err = "Please enter username";
    }
    else {
        $username = trim($_POST["username"]);//sets username variable to username entered.
    }
    if (empty(trim($_POST["password"]))) { //checks if password field is empty.
        $password_err = "Please enter password";
    }
    else {
        $password = trim($_POST["password"]); //sets password to password entered.
    }
    if (empty($username_err) && empty($password_err)) { //this whole if else statement is used to ensure all fields are correctly filled.
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = $username;
            if($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $username, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();
                            $_SESSION["loggedin"] = true;//sets the loggedin variable to true so the session can be used on other pages.
                            $_SESSION["id"] = $id; //sets session ID so session can be identified.
                            $_SESSION["username"] = $username; //sets username for session.
                            if ($_SESSION['editmode']==-1) 
                            {
                                $_SESSION['editmode']=1;
                            }
                            else
                            {
                                $_SESSION['editmode']=0;
                            }
                            header("location: index.php"); //sends user to welcome.php once session has be created.
                        }
                        else {
                            $password_err = "The password you entered was not valid.";//error message if password wasn't correct
                        }
                    }
                }
                else {
                    $username_err = "No account found with that username";//error message for if there isn't an account under that username. 
                }
            }
            else {
                echo "Oops!! something went wrong. Please try again later.";//error message for if session cannot be created. 
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
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body>
        <div class="wrapper">
            <h2>Login</h2>
            <p>Please fill in your credentials to log in.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" placeholder="Username" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" placeholder="Password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <div class="links">
                <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
            </div>
            </form>
        </div>
    </body>
</html>