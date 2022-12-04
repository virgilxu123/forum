<?php
//create_cat.php
include 'api/connection.php';
include 'common/headr.php';
$form = "<form method='post' action=''>
            Category name: <input type='text' name='cat_name' />
            Category description: <textarea name='cat_description' /></textarea>
            <input type='submit' value='Add category' />
        </form>";
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo $form;
} else {
    if(empty($_POST['cat_name']) || empty($_POST['cat_description'])) {
        echo $form;
        echo "<span style='color:red;'><em>Category name and category description cannot be empty!!</em></span>";
    }else {
        $sql = "INSERT INTO categories(cat_name, cat_description) VALUES(?,?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $param_cat_name, $param_cat_description);
            $param_cat_name = $_POST['cat_name'];
            $param_cat_description = $_POST['cat_description'];

            if ($stmt->execute()) {
                echo 'New category successfully added.';
            } else {
                echo '<span style="color:red;"><em>Category already exists!!</em></span>';
            }
            $stmt->close();
        }
        $conn->close();
    }
    
}

include 'common/footer.php';
?>