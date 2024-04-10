<?PHP

/* jobLib.php - PHP library for bikecourierdatabase
   Written by Coleman Dietsch
   Written:   12/4/2021
   Revised:   12/17/2021
*/

function clearThisJob() {
    global $thisJob;
    $thisJob['customerName'] = "";
    $thisJob['customerBusiness'] = "";
    $thisJob['customerEmail'] = "";
    $thisJob['customerPhone'] = "";
    $thisJob['cargoName'] = "";
    $thisJob['cargoDimensions'] = "";
    $thisJob['cargoWeight'] = "";
    $thisJob['cargoValue'] = "";
    $thisJob['pickupAddress'] = "";
    $thisJob['deliveryAddress'] = "";
    $thisJob['jobPay'] = "";
}

/********************************************
 * displayResult( ) - Execute a query and display the result
 *    Parameters:  $rs - result set to display as 2D array
 *                 $sql - SQL string used to display an error msg
 ********************************************/
function displayResult($result, $sql)
{
    if ($result->num_rows > 0) {
        echo "<table>\n";
        // print headings (field names)
        $heading = $result->fetch_assoc();
        echo "<tr>\n";
        // print field names
        foreach ($heading as $key => $value) {
            echo "<th>" . $key . "</th>\n";
        }
        echo "</tr>\n";

        // Print values for the first row
        echo "<tr>\n";
        foreach ($heading as $key => $value) {
            echo "<td>" . $value . "</td>\n";
        }

        // output rest of the records
        while ($row = $result->fetch_assoc()) {
            //print_r($row);
            //echo "<br />";
            echo "<tr>\n";
            // print data
            foreach ($row as $key => $value) {
                echo "<td>" . $value . "</td>\n";
            }
            echo "</tr>\n";
        }
        echo "</table>\n";
    } else {
        echo "<strong>zero results using SQL: </strong>" . $sql;
    }
} // end of displayResult( )

function runQuery($sql, $msg, $echoSuccess)
{
    global $conn;

    // run the query
    if ($conn->query($sql) === TRUE) {
        if ($echoSuccess) {
            echo $msg . " successful.<br />";
        }
    } else {
        echo "<strong>Error when: " . $msg . "</strong> using SQL: " . $sql . "<br />" . $conn->error;
    }
} // end of runQuery( )

function displayMessage($msg, $color) {
    echo "<hr /><strong style='color:" . $color . ";'>" . $msg . "</strong><hr />";
}

function displayTable() {
    global $conn;

    echo "<br />";

    // Table:customer
    $sql = "SELECT * FROM customer";
    echo "<strong>Table: customer</strong><br />";
    $result = $conn->query($sql);
    displayResult($result, $sql);
    echo "<br />";

    // Table:cargo
    $sql = "SELECT * FROM cargo";
    echo "<strong>Table: cargo</strong><br />";
    $result = $conn->query($sql);
    displayResult($result, $sql);
    echo "<br />";

    // Table:employee
    $sql = "SELECT * FROM employee";
    echo "<strong>Table: employee</strong><br />";
    $result = $conn->query($sql);
    displayResult($result, $sql);
    echo "<br />";

    // Table:job
    $sql = "SELECT * FROM job";
    echo "<strong>Table: job</strong><br />";
    $result = $conn->query($sql);
    displayResult($result, $sql);
    echo "<br />";

    // Table:jobDetail
    $sql = "SELECT * FROM jobDetail";
    echo "<strong>Table: jobDetail</strong><br />";
    $result = $conn->query($sql);
    displayResult($result, $sql);
    echo "<br />";
}

function displayNewJobs() {
    global $conn;

    // Call stored proc
    $sql = "CALL getNewJobs()";
    echo "<strong>New Jobs</strong><br /><br />";
    $result = $conn->query($sql);
    displayResult($result, $sql);
    echo "<br />";
}

function displayInProgressJobs() {
    global $conn;

    // Call stored proc
    $sql = "CALL getInProgressJobs()";
    echo "<strong>In Progress Jobs</strong><br /><br />";
    $result = $conn->query($sql);
    displayResult($result, $sql);
    echo "<br />";
}

function displayExpiredJobs() {
    global $conn;

    // Call stored proc
    $sql = "CALL getExpiredJobs()";
    echo "<strong>Expired Jobs</strong><br /><br />";
    $result = $conn->query($sql);
    displayResult($result, $sql);
    echo "<br />";
}
?>