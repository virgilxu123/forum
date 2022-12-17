<?php
//create_cat.php
include 'connect.php';
include 'header.php';

$sql = "SELECT `cat_id`, `cat_name`, `cat_description` FROM `categories`";

if ($result = $mysqli->query($sql)) {
    if ($result->num_rows == 0) {
        echo 'No categories defined yet.';
    } else {
        echo '<table border="1">
              <tr>
                <th>Category</th>
                <th>Last topic</th>
              </tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td class="leftpart">';
            echo '<h3><a href="category.php?id=' . $row['cat_id'] . '">' . $row['cat_name'] . '</a></h3>' . $row['cat_description'];
            echo '</td>';
            echo '<td class="rightpart">';
            echo '<a href="topic.php?id=">Topic subject</a> at 10-10';
            echo '</td>';
            echo '</tr>';
        }
    }
    $result->free_result();
}
$mysqli->close();


include 'footer.php';
