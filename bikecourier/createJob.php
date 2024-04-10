<?php session_start(); ?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset="utf-8"/>
    <title>Add New Job</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" type="text/css" href="sharedStyle.css">
    <script src="menu.js"></script>

    <?PHP
    /* createJob.php - Add new jobs to bikecourierdatabase
       Written by Coleman Dietsch
       Written:   11/27/2021
       Revised:   11/28/2021
                  12/17/2021
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

    if (array_key_exists('hidIsReturning', $_POST)) {
        // Get the array that was stored as a session variable
        // Used to populate the HTML textboxes using JavaScript DOM
        $thisJob = unserialize(urldecode($_SESSION['sessionThisJob']));

        // Collect input values from POST method
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Debug info:
//            echo "<hr /><strong>\$_POST: </strong>";
//            print_r($_POST);

            // id values obtained after inserts
            $customerId = '';
            $cargoId = '';
            $jobId = '';

            // Collect customer input values
            $customerName = $_POST['txtCustomerName'];
            $customerBusiness = $_POST['txtCustomerBusiness'];
            $customerEmail = $_POST['txtCustomerEmail'];
            $customerPhone = $_POST['txtCustomerPhone'];

            // Collect cargo input values
            $cargoName = $_POST['txtCargoName'];
            $cargoDimensions = $_POST['txtCargoDimensions'];
            $cargoWeight = $_POST['txtCargoWeight'];
            $cargoValue = $_POST['txtCargoValue'];

            // The following values are radio and checkbox inputs
            $cargoIsFoodItem = isset($_POST['optIsFoodItem']) != NULL ? $_POST['optIsFoodItem'] : 0;
            $cargoIsFragile = isset($_POST['chkCargoIsFragile']) != NULL ? $_POST['chkCargoIsFragile'] : 0;
            $cargoHasLiquid = isset($_POST['chkCargoIsLiquid']) != NULL ? $_POST['chkCargoIsLiquid'] : 0;
            $cargoIsMedicalPrescription = isset($_POST['chkCargoIsMedicalPrescription']) != NULL ? $_POST['chkCargoIsMedicalPrescription'] : 0;

            // Collect delivery input values
            $pickupAddress = $_POST['txtPickupAddress'];
            $deliveryAddress = $_POST['txtDeliveryAddress'];
            $pickupDate = isset($_POST['dateForDeliveryPickup']);
            $pickupTime = isset($_POST['optDeliveryPickupTime']);
            // Probably going to need a method to format date and time
//        $pickupDateTime = $pickupDate + ' ' + $pickupTime;
            $jobPay = $_POST['txtDeliveryPay'];

            // Create an associative array mirroring the record in the HTML table
            $thisJob = [
                "customerName" => $customerName,
                "customerBusiness" => $customerBusiness,
                "customerEmail" => $customerEmail,
                "customerPhone"=> $customerPhone,
                "cargoName" => $cargoName,
                "cargoDimensions" => $cargoDimensions,
                "cargoWeight" => $cargoWeight,
                "cargoValue" => $cargoValue,
                "pickupAddress" => $pickupAddress,
                "deliveryAddress" => $deliveryAddress,
                "jobPay" => $jobPay
            ];

            // Save array as a serialized session variable
            $_SESSION['sessionThisJob'] = urlencode(serialize($thisJob));

            $arrayContainsNull = false;
            foreach ($thisJob as $value) {
                // Debug line
//                echo "$value <br>";
                if ($value == NULL) {
                    $arrayContainsNull = true;
                }
            }

            // If there are no null values found, try to insert into the database
            if ($arrayContainsNull == false) {

                // This variable is used to indicate success into a table
                $success = false;

                // Write inputs to database
                // Populate customer table first
                if ($customerName != NULL && $customerEmail != NULL && $customerPhone != NULL) {
                    /*
                    $sql = "INSERT INTO customer (customerName,customerEmail,customerPhone,customerBusiness)
                        VALUES ('$customerName', '$customerEmail', '$customerPhone', '$customerBusiness')";
                    runQuery($sql, "Insert:customer", false);
                    */

                    // Prepared statement
                    $sql = "INSERT INTO customer (customerName,customerEmail,customerPhone,customerBusiness)
                    VALUES (?, ?, ?, ?)";
                    // Set up a prepared statement
                    if ($stmt = $conn->prepare($sql)) {
                        // Pass the parameters
//                        echo "\$customerName is: $customerName<br />";
//                        echo "\$customerEmail is: $customerEmail<br />";
//                        echo "\$customerPhone is: $customerPhone<br />";
//                        echo "\$customerBusinessis: $customerBusiness<br />";
                        $stmt->bind_param("ssss", $customerName, $customerEmail, $customerPhone, $customerBusiness);
                        if ($stmt->errno) {
                            displayMessage("stmt prepare( ) had error.", "red");
                        }

                        // Execute the query
                        $stmt->execute();
                        if ($stmt->errno) {
                            displayMessage("Could not execute Customer prepared statement", "red");
                        }

                        // Store the result
                        $stmt->store_result();
                        $totalCount = $stmt->num_rows;

                        // Free results
                        $stmt->free_result();

                        // Close the statement
                        $stmt->close();

                    } // end of prepared statement

                    // Collect the most recently insert customerId
                    $sql = "SELECT MAX(customerId) FROM customer";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $customerId = $row['MAX(customerId)'];

                    // Set success to true if we get to this point
                    $success = true;
                } else {
                    displayMessage("Please fill out customer details.", "red");
                }

                // If previous insert was successful
                if ($success) {
                    // Populate cargo table
                    if ($cargoName != NULL) {
                        /*
                        $sql = "INSERT INTO cargo (cargoName,cargoDimensions,cargoWeight,cargoValue,cargoIsFoodItem,
                       cargoIsFragile,cargoHasLiquid,cargoHasPrescription)
                        VALUES ('$cargoName', '$cargoDimensions', '$cargoWeight', '$cargoValue', '$cargoIsFoodItem',
                                '$cargoIsFragile', '$cargoHasLiquid', '$cargoIsMedicalPrescription')";
                        runQuery($sql, "Insert:cargo", false);
                        */

                        // Prepared statement
                        $sql = "INSERT INTO cargo (cargoName,cargoDimensions,cargoWeight,cargoValue,cargoIsFoodItem,
                       cargoIsFragile,cargoHasLiquid,cargoHasPrescription) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                        // Set up a prepared statement
//                        echo "\$customerName is: $customerName<br />";
//                        echo "\$cargoDimensions is: $cargoDimensions<br />";
//                        echo "\$cargoWeight is: $cargoWeight<br />";
//                        echo "\$cargoValue is: $cargoValue<br />";
//                        echo "\$cargoIsFoodItem is: $cargoIsFoodItem<br />";
//                        echo "\$cargoIsFragile is: $cargoIsFragile<br />";
//                        echo "\$cargoHasLiquid is: $cargoHasLiquid<br />";
//                        echo "\$cargoHasPrescription is: $cargoIsMedicalPrescription<br />";
                        if ($stmt = $conn->prepare($sql)) {
                            // Pass the parameters
                            $stmt->bind_param("sssissss", $cargoName,$cargoDimensions,$cargoWeight,
                                $cargoValue,$cargoIsFoodItem,$cargoIsFragile,$cargoHasLiquid,$cargoIsMedicalPrescription);
                            if ($stmt->errno) {
                                displayMessage("stmt prepare( ) had error.", "red");
                            }

                            // Execute the query
                            $stmt->execute();
                            if ($stmt->errno) {
                                displayMessage("Could not execute Cargo prepared statement", "red");
                            }

                            // Store the result
                            $stmt->store_result();
                            $totalCount = $stmt->num_rows;

                            // Free results
                            $stmt->free_result();

                            // Close the statement
                            $stmt->close();
                        } // end of prepared statement

                        // collect cargoId
                        $sql = "SELECT MAX(cargoId) FROM cargo";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $cargoId = $row['MAX(cargoId)'];
                    } else {
                        displayMessage("Please fill out cargo details.", "red");

                        // Set success to false if there is an issue
                        $success = false;
                    }
                } // end success

                // If previous insert was successful
                if ($success) {
                    // Populate job table
                    if ($pickupAddress != NULL && $deliveryAddress != NULL && $jobPay != NULL) {
                        /*
                        $sql = "INSERT INTO job (customerId, cargoId, jobStatus)
                        VALUES ('$customerId', '$cargoId', 'New Posting')";
                        runQuery($sql, "Insert:job", false);
                        */

                        // Prepared statement
                        $sql = "INSERT INTO job (customerId, cargoId, jobStatus) VALUES (?, ?, 'New Posting')";
                        // Set up a prepared statement
                        if ($stmt = $conn->prepare($sql)) {
                            // Pass the parameters
                            $stmt->bind_param("ii", $customerId, $cargoId);
                            if ($stmt->errno) {
                                displayMessage("stmt prepare( ) had error.", "red");
                            }

                            // Execute the query
                            $stmt->execute();
                            if ($stmt->errno) {
                                displayMessage("Could not execute Job prepared statement", "red");
                            }

                            // Store the result
                            $stmt->store_result();
                            $totalCount = $stmt->num_rows;

                            // Free results
                            $stmt->free_result();

                            // Close the statement
                            $stmt->close();
                        } // end of prepared statement

                        // Collect most recently inserted jobId
                        $sql = "SELECT MAX(jobId) FROM job";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $jobId = $row['MAX(jobId)'];
                    } else {
                        displayMessage("Please fill out delivery details.", "red");
                        // Set success to false if there is an issue
                        $success = false;
                    }
                }

                // If previous insert was successful
                if ($success) {
                    // Populate jobDetails as long as we have a job id
                    if ($jobId != NULL) {
                        /*
                        $sql = "INSERT INTO jobdetail (jobId,pickupAddress,deliveryAddress,jobPay,pickupDateTime)
                        VALUES ('$jobId', '$pickupAddress', '$deliveryAddress', '$jobPay', NOW())";
                        runQuery($sql, "Insert:job", false);
                        */

                        // Prepared statement
                        $sql = "INSERT INTO jobDetail (jobId,pickupAddress,deliveryAddress,jobPay,pickupDateTime)
                        VALUES (?, ?, ?, ?, NOW())";
                        // Set up a prepared statement
                        if ($stmt = $conn->prepare($sql)) {
                            // Pass the parameters
                            $stmt->bind_param("issi", $jobId,$pickupAddress,$deliveryAddress,$jobPay);
                            if ($stmt->errno) {
                                displayMessage("stmt prepare( ) had error.", "red");
                            }

                            // Execute the query
                            $stmt->execute();
                            if ($stmt->errno) {
                                displayMessage("Could not execute Job prepared statement", "red");
                            }

                            // Store the result
                            $stmt->store_result();
                            $totalCount = $stmt->num_rows;

                            // Free results
                            $stmt->free_result();

                            // Close the statement
                            $stmt->close();
                        } // end of prepared statement

                        // If we get to the end, we were successful at something!
                        echo "New job posting created successful!";

                        // Clear the textfields so we can create further jobs
                        clearThisJob();
                    } else {
                        displayMessage("Something has gone wrong. Please fill out he form completely and try again", "red");
                    }
                }

            } // end if array contains null
            else {
                // form contains empty required text field
                displayMessage("Please fill out the form completely and try again", "red");
            }
        } // end if server POST block
    } // end if session block

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
                <li><a href="expired.php">Expired Jobs</a></li>
            </ul>
        </div>
    </nav>
</header>

<main class="flex-column">
    <section class="skinny">
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>"
              method="POST"
              name="frmRegistration"
              id="frmRegistration">

            <fieldset>
                <legend><strong>Customer Details</strong></legend>
                <label for="txtCustomerName">Customer Name</label>
                <input type="text" name="txtCustomerName" id="txtCustomerName" value="<?php echo isset($thisJob['customerName']) ? $thisJob['customerName'] : '' ?>"/>

                <label for="txtCustomerBusiness">Customer Business (optional)</label>
                <input type="text" name="txtCustomerBusiness" id="txtCustomerBusiness" value="<?php echo isset($thisJob['customerBusiness']) ? $thisJob['customerBusiness'] : '' ?>"/>

                <label for="txtCustomerEmail">Customer Email Address</label>
                <input type="text" name="txtCustomerEmail" id="txtCustomerEmail" value="<?php echo isset($thisJob['customerEmail']) ? $thisJob['customerEmail'] : '' ?>"/>

                <label for="txtCustomerPhone">Customer Phone Number</label>
                <input type="text" name="txtCustomerPhone" id="txtCustomerPhone" value="<?php echo isset($thisJob['customerPhone']) ? $thisJob['customerPhone'] : '' ?>"/>
            </fieldset>
            <br/>
            <fieldset>
                <legend><strong>Cargo Details</strong></legend>
                <label for="txtCargoName">Cargo Name or Description</label>
                <input type="text" name="txtCargoName" id="txtCargoName" value="<?php echo isset($thisJob['cargoName']) ? $thisJob['cargoName'] : '' ?>"/>

                <label for="txtCargoDimensions">Cargo Dimensions (LxWxH)</label>
                <input type="text" name="txtCargoDimensions" id="txtCargoDimensions" value="<?php echo isset($thisJob['cargoDimensions']) ? $thisJob['cargoDimensions'] : '' ?>"/>

                <label for="txtCargoWeight">Cargo Weight</label>
                <input type="text" name="txtCargoWeight" id="txtCargoWeight" value="<?php echo isset($thisJob['cargoWeight']) ? $thisJob['cargoWeight'] : '' ?>"/>

                <label for="txtCargoValue">Cargo Declared Value</label>
                <input type="text" name="txtCargoValue" id="txtCargoValue" value="<?php echo isset($thisJob['cargoValue']) ? $thisJob['cargoValue'] : '' ?>"/>

                <p>Cargo contains food items?</p>
                <div class="horizontal">
                    <label for="optIsFoodItem">Yes</label>
                    <input type="radio" name="optIsFoodItem" id="optIsFoodItem" value="1">
                    <label for="optIsFoodItem">No</label>
                    <input type="radio" name="optIsFoodItem" id="optIsFoodItem" value="0" checked>

                    <p>Additional Cargo Details</p>
                    <input type="checkbox" id="chkCargoIsFragile" name="chkCargoIsFragile" value="1">
                    <label for="chkCargoIsFragile">Fragile</label><br>
                    <input type="checkbox" id="chkCargoIsLiquid" name="chkCargoIsLiquid" value="1">
                    <label for="chkCargoIsLiquid">Contains Liquids</label><br>
                    <input type="checkbox" id="chkCargoIsMedicalPrescription" name="chkCargoIsMedicalPrescription"
                           value="1">
                    <label for="chkCargoIsMedicalPrescription">Contains Medical Prescriptions</label><br>
                </div>
            </fieldset>
            <br/>
            <fieldset>
                <legend><strong>Delivery Details</strong></legend>
                <label for="txtPickupAddress">Pickup Address</label>
                <input type="text" name="txtPickupAddress" id="txtPickupAddress" value="<?php echo isset($thisJob['pickupAddress']) ? $thisJob['pickupAddress'] : '' ?>"/>

                <label for="txtDeliveryAddress">Delivery Address</label>
                <input type="text" name="txtDeliveryAddress" id="txtDeliveryAddress" value="<?php echo isset($thisJob['deliveryAddress']) ? $thisJob['deliveryAddress'] : '' ?>"/>

                <label for="dateForDeliveryPickup">Delivery Pickup Date</label>
                <input type="date" name="dateForDeliveryPickup" id="dateForDeliveryPickup" min="2021-01-01">

                <label for="optDeliveryPickupTime">Delivery Pickup Time</label>
                <select name="optDeliveryPickupTime" id="optDeliveryPickupTime">
                    <option value="asap">ASAP</option>
                    <option value="9am">9 AM</option>
                    <option value="10am">10 AM</option>
                    <option value="11am">11 AM</option>
                    <option value="1pm">1 PM</option>
                    <option value="2pm">2 PM</option>
                    <option value="3pm">3 PM</option>
                    <option value="4pm">4 PM</option>
                </select>

                <label for="txtDeliveryPay">Delivery Reward Amount</label>
                <input type="text" name="txtDeliveryPay" id="txtDeliveryPay" value="<?php echo isset($thisJob['jobPay']) ? $thisJob['jobPay'] : '' ?>"/>
            </fieldset>

            <br/>
            <div class="buttonBar">
                <button name="btnSubmit"
                        value="save"
                        class="crudButton"
                        onclick="this.form.submit();">
                    Save
                </button>
            </div>
            <!-- Use a hidden field to tell server if return visitor -->
            <input type="hidden" name="hidIsReturning" value="true"/>
        </form>
    </section>

    <section class="wide">
        <?PHP
        displayTable();
        // Close the database
        $conn->close();
        ?>
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

</body>
</html>
