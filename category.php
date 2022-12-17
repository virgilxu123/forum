<?php
//create_cat.php
include 'connect.php';
include 'header.php';

//first select the category based on $_GET['cat_id']
$sql = "SELECT cat_id, cat_name, cat_description FROM categories WHERE cat_id = ?";


if ($stmt = mysqli_prepare($mysqli, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $param_cat_id);

    $param_cat_id = $_GET['id'];

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 0) {
            echo 'This category does not exist.';
        } else {
            mysqli_stmt_bind_result($stmt, $cat_id, $cat_name, $cat_description);

            //do a query for the topics
            while ($stmt->fetch()) {
                echo '<h2> Topics in ' . $cat_name . ' category </h2>';

                $sql = "SELECT
                            topic_id,
                            topic_subject,
                            topic_date,
                            topic_cat
                        FROM
                            topics
                        WHERE
                            topic_cat = ?";

                if ($stmt = mysqli_prepare($mysqli, $sql)) {
                    mysqli_stmt_bind_param($stmt, "i", $param_topic_cat);
                    $param_topic_cat = $cat_id;

                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_store_result($stmt);

                        if (mysqli_stmt_num_rows($stmt) == 0) {
                            echo 'There are no topics in this category yet.';
                        } else {
                            echo '<table border="1">
                                    <tr>
                                        <th>Topic</th>
                                        <th>Created at</th>
                                    </tr>';

                            mysqli_stmt_bind_result($stmt, $topic_id, $topic_subject, $topic_date, $topic_cat);
                            while (mysqli_stmt_fetch($stmt)) {
                                echo '<tr>';
                                echo '<td class="leftpart">';
                                echo '<h3><a href="topic.php?id=' . $topic_id . '">' . $topic_subject . '</a><h3>';
                                echo '</td>';
                                echo '<td class="rightpart">';
                                echo date('d-m-Y', strtotime($topic_date));
                                echo '</td>';
                                echo '</tr>';
                            }
                        }
                    }
                }
            }
        }
    }
}

include 'footer.php';
