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

// Fetch route weights data from the database grouped by day
$sql = "SELECT DISTINCT DATE(date_time) AS day FROM route_weights";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $days = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $days = array();
}

// Fetch all route weights data for the first day (you can modify this based on user selection)
$initialDay = (!empty($days)) ? $days[0]['day'] : null;
$weights = getWeightsByDay($conn, $initialDay);

function getWeightsByDay($connection, $day) {
    $sql = "SELECT * FROM route_weights WHERE DATE(date_time) = '$day'";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return array();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Route Weights Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            flex-direction: column;
        }

        .container {
            width: 80%;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 12px; /* Adjusted padding value */
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        .day-heading {
            background-color: #e0e0e0;
            cursor: pointer;
            padding: 12px; /* Adjusted padding value */
            margin-bottom: 2px;
            display: flex;
            justify-content: space-between; /* Adjusted alignment */
            align-items: center;
        }

        @media print {
            body {
                font-family: Arial, sans-serif;
                background-color: #fff;
                margin: 0;
                padding: 0;
                display: block;
                min-height: 100vh;
            }

            .container {
                width: 100%;
            }

            .day-heading {
                display: none;
            }

            .action-heading {
                display: none;
            }
        }

        .print-button {
            padding: 8px; /* Adjusted padding value */
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .day-weights {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Your existing HTML content here -->
    <!-- ... -->
    <script>
        // Your existing JavaScript code here
        // ...
    </script>
</body>
</html>

</head>
<body>
    <div class="container">
        <h2>Route Weights Data</h2>
        <!-- Display route weights data grouped by day -->
        <?php 
            $previouslyClickedDay = null;
            foreach ($days as $day) : 
        ?>
            <div class="day-heading" onclick="toggleDayWeights('<?php echo $day['day']; ?>')">
                <h3><?php echo $day['day']; ?></h3>
                <button class="print-button" onclick="printReport('<?php echo $day['day']; ?>')">Print</button>
            </div>
            <table class="day-weights" id="<?php echo 'dayWeights_' . $day['day']; ?>">
                <!-- Include your PHP code to fetch and display data for each day -->
                <?php $weightsForDay = getWeightsByDay($conn, $day['day']); ?>
                <tr>
                    <th>Number</th>
                    <th>Date/Time</th>
                    <th>Acquisition Clerk</th>
                    <th>Gross Weight</th>
                    <th>Tare Weight</th>
                    <th>Net Weight</th>
                    <th>Receipt Weight</th>
                </tr>
                <?php foreach ($weightsForDay as $weight) : ?>
                    <tr>
                        <td><?php echo $weight['number']; ?></td>
                        <td><?php echo $weight['date_time']; ?></td>
                        <td><?php echo $weight['acquisition_clerk']; ?></td>
                        <td><?php echo $weight['gross_w']; ?></td>
                        <td><?php echo $weight['tare_w']; ?></td>
                        <td><?php echo $weight['net_w']; ?></td>
                        <td><?php echo $weight['receipt_w']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php 
            $previouslyClickedDay = $day['day'];
            endforeach; 
        ?>
    </div>

    <script>
    var openDay = null;

    function toggleDayWeights(day) {
        var dayWeightsTable = document.getElementById('dayWeights_' + day);
        if (openDay !== null) {
            document.getElementById('dayWeights_' + openDay).style.display = 'none';
        }
        dayWeightsTable.style.display = (dayWeightsTable.style.display === 'table') ? 'none' : 'table';
        openDay = (dayWeightsTable.style.display === 'table') ? day : null;
    }

    function printReport(day) {
        var dayWeightsTable = document.getElementById('dayWeights_' + day);
        dayWeightsTable.style.display = 'table';

        var printContents = dayWeightsTable.outerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
        dayWeightsTable.style.display = (openDay === day) ? 'table' : 'none';
    }
</script>

</body>
</html>


