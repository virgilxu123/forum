<?php
//signup.php
include 'connect.php';
include 'header.php';

echo '<h3>Sign up</h3>';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    /*the form hasn't been posted yet, display it
      note that the action="" will cause the form to post to the same page it is on */
    echo '<form method="post" action="">
        Username: <input type="text" name="user_name" />
        Password: <input type="password" name="user_pass">
        Password again: <input type="password" name="user_pass_check">
        E-mail: <input type="email" name="user_email">
        <input type="submit" value="Add category" />
     </form>';
} else {
    $errors = array(); /* declare the array for later use */

    if (isset($_POST['user_name'])) {
        //the user name exists
        if (!ctype_alnum($_POST['user_name'])) {
            $errors[] = 'The username can only contain letters and digits.';
        }
        if (strlen($_POST['user_name']) > 30) {
            $errors[] = 'The username cannot be longer than 30 characters.';
        }
    } else {
        $errors[] = 'The username field must not be empty.';
    }


    if (isset($_POST['user_pass'])) {
        if ($_POST['user_pass'] != $_POST['user_pass_check']) {
            $errors[] = 'The two passwords did not match.';
        }
    } else {
        $errors[] = 'The password field cannot be empty.';
    }

    if (!empty($errors)) /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/ {
        echo 'Uh-oh.. a couple of fields are not filled in correctly..';
        echo '<ul>';
        foreach ($errors as $key => $value) /* walk through the array so all the errors get displayed */ {
            echo '<li>' . $value . '</li>'; /* this generates a nice error list */
        }
        echo '</ul>';
    } else {
        //the form has been posted without, so save it
        //notice the use of mysql_real_escape_string, keep everything safe!
        //also notice the sha1 function which hashes the password
        // $sql = "INSERT INTO
        //          users(user_name, user_pass, user_email ,user_date, user_level)
        //          VALUES('" . mysql_real_escape_string($_POST['user_name']) . "',
        //                 '" . sha1($_POST['user_pass']) . "',
        //                 '" . mysql_real_escape_string($_POST['user_email']) . "',
        //                  NOW(),
        //                  0)";

        $sql = "INSERT INTO users(user_name, user_pass, user_email ,user_date, user_level) VALUES(?,?,?,?, ?)";

        if ($stmt = mysqli_prepare($mysqli, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssi", $param_username, $param_password, $param_email, $param_date, $param_level);

            // Set parameters
            $param_username = $_POST['user_name'];
            // $param_password = sha1($_POST['user_pass']);
            $param_password = password_hash($_POST['user_pass'], PASSWORD_DEFAULT);
            $param_email = $_POST['user_email'];
            $param_date = date("Y-m-d H:i:s");
            $param_level = 0;

            if (mysqli_stmt_execute($stmt)) {
                echo 'Successfully registered. You can now <a href="signin.php">sign in</a> and start posting! :-)';
            } else {
                echo 'Something went wrong while registering. Please try again later.';
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($mysqli);

        // $result = mysql_query($sql);


        // if (!$result) {
        //something went wrong, display the error
        //echo mysql_error(); //debugging purposes, uncomment when needed
        // } else {
        // }
    }
}

include 'footer.php';
