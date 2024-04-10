<?PHP
/*   getJSONData.php - Extract data from database and present as JSON data
 *   Written by Coleman Dietsch
 *   Written:  12/10/21
 *   Revised:
*/
// The JSON standard MIME header. Output as JSON, not HTML
header('Content-type: application/json');

// Check for the limit value
if(isset($_POST['limit'])){
    $limit = preg_replace('#[^0-9]#', '', $_POST['limit']);
}
else {  // a limit variable doesn't exist.
    $limit = 5;
}

// Set up connection constants
// Using default username and password for AMPPS
define("SERVER_NAME",   "localhost");
define("DBF_USER_NAME", "crudUser");
define("DBF_PASSWORD",  "ATEXEdUSnwm8MUhZEu4b!");
define("DATABASE_NAME", "bikecourierprod");
// Global connection object
$conn = NULL;

// Connect to database
createConnection();

// This query requires multiple joins because the job table is the glue that holds the other tables together
// We order by random because it's more fun this way
$sql = "SELECT cargo.cargoName, jobDetail.pickupAddress, jobDetail.deliveryAddress, jobDetail.jobPay
        FROM cargo
        LEFT JOIN job ON cargo.cargoId = job.cargoId
        LEFT JOIN jobDetail ON job.jobId = jobDetail.jobId
        WHERE job.jobStatus LIKE 'New Posting'
        ORDER BY RAND( ) LIMIT " . $limit;

$result = $conn->query($sql);
//displayResult($result, $sql);

// Loop through the $result to create JSON formatted data
$newJobArray = array();
while($thisRow = $result->fetch_assoc( )) {
    $newJobArray[] = $thisRow;
}

// JSON encode our data for anyone to use when they access this script
echo json_encode($newJobArray);

/* -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
 * createConnection( ) - Create a database connection
 -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  - */
function createConnection( ) {
    global $conn;
    // Create connection object
    $conn = new mysqli(SERVER_NAME, DBF_USER_NAME, DBF_PASSWORD);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Select the database
    $conn->select_db(DATABASE_NAME);
} // end of createConnection( )

?>
