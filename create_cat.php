<?php
//create_cat.php
include 'connect.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    //the form hasn't been posted yet, display it
    echo "<form method='post' action=''>
        Category name: <input type='text' name='cat_name' />
        Category description: <textarea name='cat_description' /></textarea>
        <input type='submit' value='Add category' />
     </form>";
} else {
    //the form has been posted, so save it
    // $sql = ìINSERT INTO categories(cat_name, cat_description)
    //    VALUES('' . mysql_real_escape_string($_POST['cat_name']) . ì',
    //          '' . mysql_real_escape_string($_POST['cat_description']) . ì')';

    $sql = "INSERT INTO categories(cat_name, cat_description) VALUES(?,?)";


    if ($stmt = mysqli_prepare($mysqli, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $param_cat_name, $param_cat_description);
        $param_cat_name = $_POST['cat_name'];
        $param_cat_description = $_POST['cat_description'];

        if (mysqli_stmt_execute($stmt)) {
            echo 'New category successfully added.';
        } else {
            echo 'Error: ' . mysqli_error($mysqli);
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($mysqli);
}

include 'footer.php';
