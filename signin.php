<?php
    //signin.php
    include 'api/connection.php';

   
    session_start();
    $message = "";
    //first, check if the user is already signed in. If that is the case, there is no need to display this page
    if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true){
        header("location: create_cat.php");
        exit;
    }else {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            if(!isset($_POST['user_name'])){
                $message .= "The username field must not be empty. \n";
            }
            if(!isset($_POST['user_pass'])){
                $errors[] = 'The password field must not be empty.';
            }

            if(!empty($errors)) {
                $message = "Uh-oh.. a couple of fields are not filled in correctly..\n";

                foreach($errors as $key => $value) {
                    $message .=  $value ."\n"; /* this generates a nice
                    error list */
                }
            }else {
                //the form has been posted without errors, so save it
                //notice the use of mysql_real_escape_string, keep everything safe!
                //also notice the sha1 function which hashes the password

                $sql = "SELECT user_id, user_name, user_pass FROM users WHERE user_name = ?";
                if($stmt = $conn->prepare($sql)){
                    // Bind variables to the prepared statement as parameters
                    $stmt->bind_param("s", $param_username);
                    // Set parameters
                    $param_username = trim($_POST["user_name"]);
                    // Attempt to execute the prepared statement
                    if($stmt->execute()){
                        // Store result
                        $stmt->store_result();
                        // Check if username exists, if yes then verify password
                        if($stmt->num_rows() == 1){
                            // Bind result variables
                            $stmt->bind_result($user_id, $user_name, $hashed_password);
                            if($stmt->fetch()){
                                if(password_verify($_POST['user_pass'], $hashed_password)){
                                    // Password is correct, so start a new session
                                    session_start();
                                    // Store data in session variables

                                    $_SESSION["loggedin"] = true;
                                    $_SESSION["id"] = $user_id;
                                    $_SESSION["name"] = $user_name;
                                    // Redirect user to welcome page
                                    header("location: create_cat.php");

                                } else{
                                    // Password is not valid, display a generic error message
                                    $message = "Invalid username or password.";
                                }
                            }
                        } else{
                            // Username doesn't exist, display a generic error message
                            $message = "Student ID not yet register.";
                        }
                    } else{
                    echo "Oops! Something went wrong. Please try again later.";
                    }
                    // Close statement
                    $stmt->close();
                }
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <style>
    </style>
</head>
<body>
    <?php include 'common\headr.php' ?>

    <h3>Sign in</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Username: <input type="text" name="user_name" />
        Password: <input type="password" name="user_pass">
        <input type="submit" value="Sign in" />
        <span><?php echo $message; ?></span>
    </form>
    

    <?php include 'common\footer.php' ?>
    
</body>
</html>