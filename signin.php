<?php
    //signin.php
    include 'api/connection.php';

    $message = "";
    //first, check if the user is already signed in. If that is the case, there is no need to display this page
    if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true){
        header("location: create_cat.php");
        exit;
    }else {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $errors = array();
            if(empty($_POST['user_name'])){
                $errors[] = "The username field must not be empty.";
            }
            if(empty($_POST['user_pass'])){
                $errors[] = "The password field must not be empty.";
            }

            if(!empty($errors)) {
                $message = "<br>Uh-oh.. a couple of fields are not filled in correctly..<br>";

                foreach($errors as $key => $value) {
                    $message .=  "$value .<br>"; 
                }
            }else {

                $sql = "SELECT user_id, user_name, user_pass, user_level FROM users WHERE user_name = ?";
                if($stmt = $conn->prepare($sql)){

                    $stmt->bind_param("s", $param_username);
                    $param_username = trim($_POST["user_name"]);
 
                    if($stmt->execute()){

                        $stmt->store_result();

                        if($stmt->num_rows() == 1){
                            $stmt->bind_result($user_id, $user_name, $hashed_password, $user_level);
                            if($stmt->fetch()){
                                if(password_verify($_POST['user_pass'], $hashed_password)){
                                    $_SESSION["loggedin"] = true;
                                    $_SESSION["id"] = $user_id;
                                    $_SESSION["user_name"] = $user_name;
                                    $_SESSION['user_level'] = $user_level;
                                    session_start();
                                    $message = '<br>Welcome, ' . $_SESSION['user_name'] . '.<a href="index.php">Proceed to the forum overview</a>.';
                                } else{
                                    $message = "<br>Invalid username or password.";
                                }
                            }
                        } else{
                            $message = "<br>Student ID not yet register.";
                        }
                    } else{
                    echo "Oops! Something went wrong. Please try again later.";
                    }
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
        <span class="prompt"><?php echo $message; ?></span>
    </form>
    

    <?php include 'common\footer.php' ?>
    
</body>
</html>
