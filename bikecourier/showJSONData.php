<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>

    <!-- showJSONData.php - Show JSON data that we are getting from getJSONData.php
         Student: Coleman Dietsch
         Written:   12/10/21
         Revised:   12/17/21
     -->
    <title>New Jobs Live Feed</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" type="text/css" href="sharedStyle.css">
    <script src="menu.js"></script>

    <script>
        function getData(){
            let counter = 0;
            let result = document.getElementById("result");
            let httpReq = new XMLHttpRequest();

            // Add AJAX call
            // Request the API script using POST, calling the PHP script
            httpReq.open("POST", "getJSONData.php", true);
            httpReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            httpReq.onreadystatechange = function() {
                if(httpReq.readyState == 4 && httpReq.status == 200) {
                    let data = JSON.parse(httpReq.responseText);
                    // Clear the data each time around
                    result.innerHTML = "";
                    for(let index in data){
                        result.innerHTML += "<section class=\"json\"><h3>"
                            + data[index].cargoName + "</h3>"
                            + " Pickup:  " + data[index].pickupAddress + "<br />"
                            + " Delivery: " + data[index].deliveryAddress + "<br />"
                            + " Job Pay: $" + data[index].jobPay + "<br />"
                            + "</section>";
                    }// end of for( )
                }// end of if readyState
            }// end of onreadystatechange

            // Send the request with a POST variable of 4 for the limit
            httpReq.send("limit=4");
            result.innerHTML = "requesting...  Counter is: " + counter++;
            // Twiddle the CPU's thumbs for 10 seconds
            // Then, call the function.
            setTimeout('getData()',10000);
        } // end of getData( )
    </script>
</head>
<body>

<header>
    <nav>
        <div class="flex-top">
            <a href="createJob.php" class="special-char">🚲</a>
            <a href="https://colemand.dev/bikecourier/">
                <h1>Twin Cities Bicycle Courier Services</h1>
            </a>
            <div class="dropdown">
                <button onclick="showMenu()" class="menuButton">☰</button>
                <div id="myDropdown" class="dropdown-content">
                    <a href="index.php">Home</a>
                    <a href="createJob.php">Create New Job</a>
                    <a href="presentation.html">Presentation</a>
                    <a href="readMe.html">Read Me</a>
                    <a href="reflection.html">Reflection</a>
                    <a href="getJSONData.php">Get JSON Data</a>
                    <a href="showJSONData.php">Show JSON Data</a>
                </div>
            </div>
        </div>
        <div class="flex-center">
            <ul>
                <li><a href="index.php">New Jobs</a></li>
                <li><a href="inProgress.php">Jobs In Progress</a></li>
                <li><a href="expired.php">Expired Jobs</a></li>
            </ul>
        </div>
    </nav>
</header>

<main class="flex-column" id="result">
    <script>
        getData();
    </script>
</main>

<footer>
    <div class="flex-center">
        <ul>
            <li><a href="readMe.html">Help</a></li>
            <li><a href="tel:+6121112223333">Contact Us</a></li>
            <li><a href="mailto:webmaster@example.com">Report Issue</a></li>
        </ul>
    </div>
</footer>

</body>
</html>
