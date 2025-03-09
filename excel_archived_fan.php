<?php
include "db_conn.php";

if (isset($_POST["export_excel"])) {

    $sql = "SELECT * FROM `fan_archive`";
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
                <th>Room</th>
                <th>Fan Quantity</th>
                <th>Fan Cost</th>
                <th>Date Rented</th>
                <th>Date Returned</th>
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
                <td>' . $row["fan_quantity"] . '</td>
                <td>' . $row["fan_cost"] . '</td>
                <td>' . $row["fan_date_rented"] . '</td>
                <td>' . $row["fan_date_returned"] . '</td>
                </tr>
            ';
        }

        $output .= '</table>';

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Archived_Fans.xls");

        echo $output;
        exit();
    } else {
        echo "No data found!";
    }
}
?>
