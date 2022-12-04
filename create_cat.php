<?php
//create_cat.php
include 'api/connection.php';
include 'common/headr.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<form method='post' action=''>
        Category name: <input type='text' name='cat_name' />
        Category description: <textarea name='cat_description' /></textarea>
        <input type='submit' value='Add category' />
     </form>";
} else {
    
    $sql = "INSERT INTO categories(cat_name, cat_description) VALUES(?,?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $param_cat_name, $param_cat_description);
        $param_cat_name = $_POST['cat_name'];
        $param_cat_description = $_POST['cat_description'];

        if ($stmt->execute()) {
            echo 'New category successfully added.';
        } else {
            echo 'Error: ' . $conn->error();
        }
        $stmt->close();
    }
    $conn->close();
}

include 'common/footer.php';