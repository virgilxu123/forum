<?php
//signin.php
include 'connect.php';
include 'header.php';

echo '<h3>Sign in</h3>';

//first, check if the user is already signed in. If that is the case, there is no need to display this page
if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true) {
    echo 'You are already signed in, you can <a href="signout.php">sign out</a> if you want.';
} else {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        /*the form hasn't been posted yet, display it
          note that the action="" will cause the form to post to the same page it is on */
        echo '<form method="post" action="">
            Username: <input type="text" name="user_name" />
            Password: <input type="password" name="user_pass">
            <input type="submit" value="Sign in" />
         </form>';
    } else {
        /* so, the form has been posted, we'll process the data in three steps:
            1.  Check the data
            2.  Let the user refill the wrong fields (if necessary)
            3.  Varify if the data is correct and return the correct response
        */
        $errors = array(); /* declare the array for later use */

        if (!isset($_POST['user_name'])) {
            $errors[] = 'The username field must not be empty.';
        }

        if (!isset($_POST['user_pass'])) {
            $errors[] = 'The password field must not be empty.';
        }

        if (!empty($errors)) /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/ {
            echo 'Uh-oh.. a couple of fields are not filled in correctly..';
            echo '<ul>';
            foreach ($errors as $key => $value) /* walk through the array so all the errors get displayed */ {
                echo '<li>' . $value . '</li>'; /* this generates a nice error list */
            }
            echo '</ul>';
        } else {
            $sql = "SELECT user_id, user_name,user_pass, user_level FROM users WHERE user_name = ?";

            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param('s', $param_username);
                $param_username = $_POST['user_name'];

                if ($stmt->execute()) {
                    $stmt->store_result();

                    if ($stmt->num_rows == 1) {
                        $stmt->bind_result($id, $username, $user_pass, $user_level);
                        $stmt->fetch();

                        if (password_verify($_POST['user_pass'], $user_pass)) {
                            $_SESSION['signed_in'] = true;
                            $_SESSION['user_id'] = $id;
                            $_SESSION['user_name'] = $username;
                            $_SESSION['user_level'] = $user_level;

                            echo 'Welcome, ' . $_SESSION['user_name'] . '. <a href="index.php">Proceed to the forum overview</a>.';
                        } else {
                            echo 'You have supplied a wrong user/password combination. Please try again.';
                        }
                    } else {
                        echo 'You have supplied a wrong user/password combination. Please try again.';
                    }
                } else {
                    echo 'Something went wrong while signing in. Please try again later.';
                }
            }
        }
    }
}


include 'footer.php';
