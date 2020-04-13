<?php
require_once("config.php");//loads config.php to ensure the database has already been created and if not then creates it.

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";//This whole IF statement checks if the user has entered a username and assigns it to a variable.
    }
    else {
        $sql = "SELECT id FROM users WHERE username = ?";//checks the database for ID's for usernames.
        if($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = trim($_POST["username"]);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $username_err = "This username is already taken.";//returns error message if the username has already been used. 
                }
                else {
                    $username = trim($_POST["username"]);//if username isn't already being used then assigns it to variable.
                }
            }
            else {
                echo "Oops!! Something went wrong. Please try again later.";//if the database cannot be accessed then it returns this message. 
            }

            $stmt->close();//This closes access to the database.
        }
        if(empty(trim($_POST["password"]))) {
            $password_err = "Please enter password.";//checks the user has entered password.
        }
        elseif (strlen(trim($_POST["password"])) < 6) {
            $passwrid_err = "Password must have atleast 6 characters.";//uses the function 'strlen' to check the length of the password and ensure it meets the specified length.
        }
        else {
            $password = trim($_POST["password"]);//if the password meets all requirements then it sets it to the variable.
        }

        if (empty(trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Please confirm the password.";//checks if the confirm password field is empty and returns message if it is.
        }
        else {
            $confirm_password = trim($_POST["confirm_password"]);
            if (empty($password_err) && ($password != $confirm_password)) {
                $confirm_password_err = "Passwords did not match!";//checks if the password and confirm password fields match and returns an error message.
            }
        }

        if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";//checks if all fields arent empty and then inserts the data into the users table.

            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("ss", $param_username, $param_password);
                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT);//hashes the users password before it is entered into the table, this means you need a key to unhash the password to use it.

                if ($stmt->execute()) {
                    header("location: login.php");//if the statement is successful, it sedns the user to the login page.
                }
                else {
                    echo "Something went wrong. PLease try again later.";//if statement isn't successful then returns error message. 
                }
            }
            $stmt->close();//closes statement
        }
        $mysqli->close();//closes SQL connection
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <head>
        <title>Sign Up</title>
        <link rel="stylesheet" href="stylesheet.css">
    </head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill in this form to create an account.</p>
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
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" placeholder="Confirm Password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class-="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-primary" value="Reset">
            </div>
            <p>Already have an account?<a href="login.php">Login Here</a></p>
        </form>
    </div>
</body>
</html>