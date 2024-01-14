<?php
// Assume user authentication logic here
$userName = "John Doe";
$userRole = "Admin";

// ... (your existing code)

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="d.css"> <!-- Include your external CSS file -->
    <script defer src="script.js"></script>
    <title>Factory System Dashboard</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <header>
        <h1>Rorok Tea Factory</h1>
    </header>

    <nav class="vertical-menu">
    <ul>
        <li><a href="#" data-menu-id="route-menu" data-submenu-id="route-menu">Route Reception</a></li>
        <li><a href="#" data-menu-id="factory-menu" data-submenu-id="factory-menu">Factory Reception</a></li>
        <li><a href="#" data-menu-id="firewood-menu" data-submenu-id="firewood-menu">Firewood</a></li>
        <li><a href="#" data-menu-id="turnabout-menu" data-submenu-id="turnabout-menu">Turnabout</a></li>
        <li><a href="#" data-menu-id="misposts-menu" data-submenu-id="misposts-menu">Misposts</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>


    <!-- Horizontal Menus -->
    <div class="horizontal-menu" id="route-menu">
    <ul>
        <li><a href="?menu=manifest">Manifest</a></li>
        <li><a href="?menu=complete" data-menu-id="complete-menu">Complete</a></li>
    </ul>
</div>


    <div class="horizontal-menu" id="factory-menu">
        <ul>
        <li><a href="#" data-menu-id="route-menu">Manifest</a></li>

            <li><a href="#">Complete</a></li>
        </ul>
    </div>

    <div class="horizontal-menu" id="firewood-menu">
        <ul>
            <li><a href="#">In Progress</a></li>
            <li><a href="#">Complete</a></li>
        </ul>
    </div>

    <div class="horizontal-menu" id="turnabout-menu">
        <ul>
            <li><a href="#">Turnabout</a></li>
            
        </ul>
    </div>

    <div class="horizontal-menu" id="misposts-menu">
        <ul>
            <li><a href="#">Complete</a></li>
            
        </ul>
    </div>
    

    <main>
        <!-- Content area will be dynamically updated based on the selected menu -->
        <?php
        if (!empty($_GET['menu']) && $_GET['menu'] == 'manifest') {
            include('route.php'); // Include the route entry form
        } else {
            // Display other content based on the selected menu
            // For example, you can add logic to display different pages or content
        }
        ?>
    </main>

    <footer>
        
    </footer>
</body>
</html>
