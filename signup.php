<?php
    //signup.php
    include 'api\connection.php';

    $message = "";
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        /* so, the form has been posted, we'll process the data in three steps:
        1. Check the data
        2. Let the user refill the wrong fields (if necessary)
        3. Save the data
        */
        $errors = array(); /* declare the array for later use */
        if(isset($_POST['user_name'])) {
            //the user name exists
            if(!ctype_alnum($_POST['user_name'])){
                $errors[] = 'The username can only contain letters and digits.';
            }
            if(strlen($_POST['user_name']) > 30){
                $errors[] = 'The username cannot be longer than 30 characters.';
            }
        }else {
            $errors[] = 'The username field must not be empty.';
        }

        if(isset($_POST['user_pass'])){
            if($_POST['user_pass'] != $_POST['user_pass_check']) {
                $errors[] = 'The two passwords did not match.';
            }
        }else {
            $errors[] = 'The password field cannot be empty.';
        }
        /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/
        if(!empty($errors)) { 
            $message = "Uh-oh.. a couple of fields are not filled in correctly..\n";

            /* walk through the array so all the errors get displayed */
            foreach($errors as $key => $value){
                $message .= $value."\n"; /* this generates a nice error list */
            }

        }else {
            //the form has been posted without, so save it
            //notice the use of mysql_real_escape_string, keep everything safe!
            //also notice the sha1 function which hashes the password
            $password = password_hash($_POST['user_pass'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users(user_name, user_pass, user_email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $_POST['user_name'], $password, $_POST['user_email']);
            $stmt->execute();
            
            $message = 'Successfully registered. You can now <a href="signin.php"> sign in</a> and start posting! :-)';

        }
    }

?>

    <?php include 'common\headr.php' ?>

    <h3>Sign up</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div  class="form-container">
            <div>Username: <input type="text" name="user_name" /></div>
            <div>Password: <input type="password" name="user_pass"></div>
            <div>Password again: <input type="password" name="user_pass_check"></div>
            <div>E-mail: <input type="email" name="user_email"></div>
            <div><input type="submit" value="Sign up" class="signup"/></div>     
        </div>
        <span><?php echo $message; ?></span>  
    </form>

    <?php include 'common\footer.php' ?>