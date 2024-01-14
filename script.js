document.addEventListener('DOMContentLoaded', function () {
    toggleHorizontalMenu('route-menu');

    var verticalMenuLinks = document.querySelectorAll('.vertical-menu li a');

    verticalMenuLinks.forEach(function (menuItem) {
        menuItem.addEventListener('click', function (event) {
            event.preventDefault();
            var menuId = this.getAttribute('data-menu-id');
            console.log('Clicked vertical menu with data-menu-id:', menuId);
            toggleHorizontalMenu(menuId);

            // Check if the clicked menu is "Complete" under "Route Reception"
            if (menuId === 'complete-menu') {
                console.log('Attempting to load routeweights.php');
                loadReports('routeweights.php');
            }

            // Check if the clicked menu is "Manifest" under "Route Reception"
            if (menuId === 'manifest-menu') {
                console.log('Attempting to load route.php');
                loadContent('route.php');
                displaySavedData(); // Call a function to display saved data
            }
        });
    });

    document.querySelector('#route-menu li a[href="?menu=manifest"]').addEventListener('click', function (event) {
        event.preventDefault();
        loadContent('route.php');
        displaySavedData(); // Call a function to display saved data
    });

    document.getElementById('routeForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission

        // AJAX request to save data
        $.ajax({
            type: "POST",
            url: "route.php",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    var confirmation = prompt("Data saved successfully!\n\nClick OK to enter more data.");

                    if (confirmation !== null) {
                        // Clear the form or perform other actions if needed
                        document.getElementById("routeForm").reset();
                        // Focus on the first input field for more data entry
                        document.querySelector('input[name="number"]').focus();
                    }
                } else {
                    alert("Error: " + response.message);
                }
            },
            // ... (other AJAX options)
        });
    });

    function toggleHorizontalMenu(menuId) {
        console.log('Toggling horizontal menu with ID:', menuId);
        var horizontalMenus = document.querySelectorAll('.horizontal-menu');

        horizontalMenus.forEach(function (menu) {
            menu.style.display = 'none';
        });

        var selectedMenu = document.getElementById(menuId);

        if (selectedMenu) {
            selectedMenu.style.display = 'block';
        } else {
            console.error(menuId + ' menu not found!');
            console.log('Available menu IDs:', Array.from(horizontalMenus).map(menu => menu.id).join(', '));
        }
    }

    function loadContent(contentUrl) {
        var mainContent = document.querySelector('main');
        var xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                mainContent.innerHTML = xhr.responseText;
            }
        };

        xhr.open('GET', contentUrl, true);
        xhr.send();
    }

    function loadReports(reportUrl) {
        var mainContent = document.querySelector('main');
        var xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                mainContent.innerHTML = xhr.responseText;
            }
        };

        xhr.open('GET', reportUrl, true);
        xhr.send();
    }

    function displaySavedData() {
        // Fetch and display saved data from the backend
        // Replace the URL with the actual URL for fetching saved data
        $.ajax({
            type: "GET",
            url: "fetch_saved_data.php",
            dataType: "json",
            success: function (response) {
                if (response.status === "success" && response.data.length > 0) {
                    var savedDataContainer = document.getElementById('savedData');
                    savedDataContainer.innerHTML = ''; // Clear previous content

                    response.data.forEach(function (dataItem) {
                        var dataItemDiv = document.createElement('div');
                        dataItemDiv.textContent = 'Number: ' + dataItem.number + ', Date/Time: ' + dataItem.date_time;
                        savedDataContainer.appendChild(dataItemDiv);
                    });
                }
            },
            // ... (other AJAX options)
        });
    }
});
