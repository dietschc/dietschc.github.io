<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset="utf-8"/>
    <title>Expired Jobs</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" type="text/css" href="sharedStyle.css">
    <script src="menu.js"></script>

    <?PHP
    /* expired.php - Updated expired page for bikecourierdatabase
       Written by Coleman Dietsch
       Written:   12/4/2021
       Revised:   12/17/2021
    */

    // Set up connection constants
    // Using default username and password for AMPPS
    const SERVER_NAME = "localhost";
    const DBF_USER_NAME = "crudUser";
    const DBF_PASSWORD = "ATEXEdUSnwm8MUhZEu4b!";
    const DATABASE_NAME = "bikecourierprod";

    // Use our library file
    require_once(getcwd() . "/jobLib.php");

    // Create connection object
    $conn = new mysqli(SERVER_NAME, DBF_USER_NAME, DBF_PASSWORD);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Select the database
    $conn->select_db(DATABASE_NAME);
    ?>
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
                <li><a href="expired.php" class="active">Expired Jobs</a></li>
            </ul>
        </div>
    </nav>
</header>

<main class="flex-column">
    <section>
        <?PHP
        displayExpiredJobs();

        // Close the database
        $conn->close();
        ?>
    </section>
    <section>
        <h1>Stored Procedure</h1>
        <img src="graphic/expiredJobsScreenshot.PNG" class="site-thumbnails" alt="screenshot of expired stored proc">
    </section>
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

<a href="createJob.php" class="fab">+</a>

</body>
</html>
