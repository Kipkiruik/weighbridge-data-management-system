<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Modify these with your database credentials
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "weighbridge";

// Create connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $number = $_POST["number"];
    $date_time = $_POST["date_time"];
    $acquisition_clerk = $_POST["acquisition_clerk"];
    $gross_weight = $_POST["gross_weight"];
    $tare_weight = $_POST["tare_weight"];
    $net_weight = $_POST["net_weight"];
    $receipt_weight = $_POST["receipt_weight"];

    // Insert data into route_weights table
    $sql = "INSERT INTO route_weights (number, date_time, acquisition_clerk, gross_w, tare_w, net_w, receipt_w)
            VALUES ('$number', '$date_time', '$acquisition_clerk', '$gross_weight', '$tare_weight', '$net_weight', '$receipt_weight')";

    if ($conn->query($sql) === TRUE) {
        // Return a JSON response indicating success
        $response = array("status" => "success", "message" => "Data saved successfully");
        echo json_encode($response);
    } else {
        // Return a JSON response indicating error
        $response = array("status" => "error", "message" => "Error during insertion: " . $conn->error);
        echo json_encode($response);
    }

    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Entry</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .editable {
            cursor: pointer;
        }

        .editable:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div id="newRecordsContainer"></div>
    <h2>Route Reception</h2>

    <!-- Display existing records in an editable table -->
    <?php if (!empty($weights)) : ?>
        <table>
            <tr>
                <th>Number</th>
                <th>Date/Time</th>
                <th>Acquisition Clerk</th>
                <th>Gross Weight</th>
                <th>Tare Weight</th>
                <th>Net Weight</th>
                <th>Receipt Weight</th>
            </tr>
            <?php foreach ($weights as $weight) : ?>
                <tr class="editable" onclick="editRecord(this)">
                    <td contenteditable="true"><?php echo $weight['number']; ?></td>
                    <td contenteditable="true"><?php echo $weight['date_time']; ?></td>
                    <td contenteditable="true"><?php echo $weight['acquisition_clerk']; ?></td>
                    <td contenteditable="true"><?php echo $weight['gross_w']; ?></td>
                    <td contenteditable="true"><?php echo $weight['tare_w']; ?></td>
                    <td contenteditable="true"><?php echo $weight['net_w']; ?></td>
                    <td contenteditable="true"><?php echo $weight['receipt_w']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- Form for entering new records -->
    <form id="routeForm" action="route.php" method="post" onsubmit="return validateForm();">
        <table>
            <tr>
                <th style="width: 50px;">Number</th>
                <th style="width: 100px;">Date/Time</th>
                <th style="width: 100px;">Acquisition Clerk</th>
                <th>Gross Weight</th>
                <th>Tare Weight</th>
                <th>Net Weight</th>
                <th>Receipt Weight</th>
            </tr>
            <tr>
                <td><input type="text" name="number" style="width: 40px; height: 30px;" required></td>
                <td><input type="datetime-local" name="date_time" style="height: 30px;" required></td>
                <td><input type="text" name="acquisition_clerk" style="height: 30px;" required></td>
                <td><input type="text" name="gross_weight" style="width: 80px; height: 30px;" required></td>
                <td><input type="text" name="tare_weight" style="width: 80px; height: 30px;" required></td>
                <td><input type="text" name="net_weight" style="width: 80px; height: 30px;" required></td>
                <td><input type="text" name="receipt_weight" style="width: 80px; height: 30px;" required></td>
            </tr>
        </table>

        <button type="button" onclick="addNewRow()">Add New Record</button>
        <button type="submit">Save</button>
    </form>

    <script>
    $(document).ready(function () {
        $("#routeForm").submit(function (event) {
            event.preventDefault(); // Prevent the default form submission

            $.ajax({
                type: "POST",
                url: "route.php", // Change this to the correct PHP file name
                data: $(this).serialize(),
                success: function (response) {
                    console.log(response); // Log the response for debugging
                    // You can update the UI or perform other actions if needed

                    // After a successful submission, fetch and display records
                    fetchAndDisplayRecords();
                },
                error: function (error) {
                    console.error("Error during AJAX request:", error);
                }
            });
        });

        // Function to fetch and display records
        function fetchAndDisplayRecords() {
            $.ajax({
                type: "GET",
                url: "get_records.php", // Replace with the actual PHP file to fetch records
                dataType: "json",
                success: function (data) {
                    // Update the display with the new records
                    var recordsHtml = "<h2>Newly Entered Records</h2><table><tr><th>Number</th><th>Date/Time</th><th>Acquisition Clerk</th><th>Gross Weight</th><th>Tare Weight</th><th>Net Weight</th><th>Receipt Weight</th></tr>";

                    for (var i = 0; i < data.length; i++) {
                        recordsHtml += "<tr>";
                        recordsHtml += "<td>" + data[i].number + "</td>";
                        recordsHtml += "<td>" + data[i].date_time + "</td>";
                        recordsHtml += "<td>" + data[i].acquisition_clerk + "</td>";
                        recordsHtml += "<td>" + data[i].gross_w + "</td>";
                        recordsHtml += "<td>" + data[i].tare_w + "</td>";
                        recordsHtml += "<td>" + data[i].net_w + "</td>";
                        recordsHtml += "<td>" + data[i].receipt_w + "</td>";
                        recordsHtml += "</tr>";
                    }

                    recordsHtml += "</table>";

                    $("#newRecordsContainer").html(recordsHtml);
                },
                error: function (error) {
                    console.error("Error fetching records:", error);
                }
            });
        }
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="script.js"></script>
</body>
</html>
