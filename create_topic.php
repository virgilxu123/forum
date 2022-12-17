<?php
//create_cat.php
include 'connect.php';
include 'header.php';

echo '<h2>Create a topic</h2>';
if (!isset($_SESSION['signed_in'])) {
    //the user is not signed in
    echo 'Sorry, you have to be <a href="/forum/signin.php">signed in</a> to create a topic.';
} else {
    //the user is signed in
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        //the form hasn't been posted yet, display it
        //retrieve the categories from the database for use in the dropdown
        $sql = "SELECT `cat_id`, `cat_name`, `cat_description` FROM `categories`";

        if ($result = $mysqli->query($sql)) {
            if ($result->num_rows == 0) {
                if ($_SESSION['user_level'] == 1) {
                    echo 'You have not created categories yet.';
                } else {
                    echo 'Before you can post a topic, you must wait for an admin to create some categories.';
                }
            } else {
                echo '<form method="post" action="">
                    Subject: <input type="text" name="topic_subject" />
                    Category:';

                echo '<select name="topic_cat">';
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['cat_id'] . '">' . $row['cat_name'] . '</option>';
                }
                echo '</select>';

                echo 'Message: <textarea name="post_content" /></textarea>
                    <input type="submit" value="Create topic" />
                 </form>';
            }
        } else {
            echo 'Error while selecting from database. Please try again later.';
        }
    } else {

        //start the transaction
        // $query  = "BEGIN";
        // $result = mysql_query($query);

        mysqli_begin_transaction($mysqli);

        try {

            $sql = "INSERT INTO topics (topic_subject, topic_date,topic_cat,topic_by) values(?,?,?,?)";

            $stmt = mysqli_prepare($mysqli, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $param_topic_subject, $param_topic_date, $param_topic_cat, $param_topic_by);

            $param_topic_subject = $_POST['topic_subject'];
            $param_topic_date = date("Y-m-d H:i:s");
            $param_topic_cat = $_POST['topic_cat'];
            $param_topic_by = $_SESSION['user_id'];

            if (mysqli_stmt_execute($stmt)) {
                $topicID = mysqli_stmt_insert_id($stmt);

                $sql = "INSERT INTO posts(post_content,post_date,post_topic,post_by) VALUES (?,?,?,?)";

                $param_postContent = $_POST["post_content"];
                $param_postDate = date("Y-m-d H:i:s");
                $param_postTopic = $topicID;
                $param_postBy = $_SESSION['user_id'];

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_commit($mysqli);
                    echo 'You have successfully created <a href="topic.php?id=' . $topicID . '">your new topic</a>.';
                } else {
                    throw new Exception("Error while inserting into posts table");
                }
            } else {
                throw new Exception("Error while inserting into topics table");
            }

            mysqli_commit($mysqli);
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($mysqli);
            throw $exception;
        }
    }
}

include 'footer.php';
