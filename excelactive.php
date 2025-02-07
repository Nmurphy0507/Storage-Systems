<?php
include "db_conn.php";

if (isset($_POST["export_excel"])) {

    $sql = "SELECT * FROM `checkin-out`";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error with the query: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {

        $output = '
            <table class="table" bordered="1">
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Group</th>
                <th>Building</th>
                <th>Room / Bed</th>
                <th>Key</th>
                <th>Mealcard</th>
                <th>Checked In or Out</th>
                <th>Key Returned</th>
                <th>MealCard Returned</th>
                <th>Date</th>
                <th>Notes</th>
            </tr>
        ';

        while ($row = mysqli_fetch_array($result)) {
            $output .= '
                <tr>
                <td>' . $row["first_name"] . '</td>
                <td>' . $row["last_name"] . '</td>
                <td>' . $row["group"] . '</td>
                <td>' . $row["building"] . '</td>
                <td>' . $row["room"] . '</td>
                <td>' . $row["key_number"] . '</td>
                <td>' . $row["mealcard"] . '</td>
                <td>' . $row["Checked_in_out"] . '</td>
                <td>' . $row["key_returned"] . '</td>
                <td>' . $row["mealcard_returned"] . '</td>
                <td>' . $row["Date"] . '</td>
                <td>' . $row["notes"] . '</td>
                </tr>
            ';
        }

        $output .= '</table>';

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=ActiveKeys.xls");

        echo $output;
        exit();
    } else {
        echo "No data found!";
    }
}
?>
