<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PHP-MySQL forum</title>
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <h1>My forum</h1>
    <div id="wrapper">
        <div id="menu">
            <a class="item" href="/forum/index.php">Home</a> -
            <a class="item" href="/forum/create_topic.php">Create a topic</a> -

            <a class="item" href="/forum/create_cat.php">Create a category</a>
            <div class="userbar">
                <div class="userbar">
                    <?php
                    if (isset($_SESSION['signed_in'])) {
                        echo 'Hello ' . $_SESSION['user_name'] . '. Not you? <a href="signout.php">Sign out</a>';
                    } else {
                        echo '<a href="signin.php">Sign in</a> or <a href="signup.php">create an account</a>.';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div id="content">