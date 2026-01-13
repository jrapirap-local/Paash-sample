<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
header("Content-Type: application/json");

require '../d/dbconnect.php';
include '../i/interface.php';
include '../s/services.php';

function validate($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$category = $_GET['category'] ?? null;

$client = new client();
switch ($category){
    case 'checkLoginIsValid': $client -> checkLoginIsValid($conn, $_GET['profileID'], $_GET['ip']); break;
    case 'getCurrentActiveSchoolyear': $client -> getCurrentActiveSchoolyear($conn, $_GET['schoolyearId']); break;
    case 'getCurrentSYActiveStrandAndDetails': $client -> getCurrentSYActiveStrandAndDetails($conn, $_GET['schoolyearId']); break;
    case 'getNewsForDashboard': $client -> getNewsForDashboard($conn); break;
    case 'getUnreadMailForDashboard': $client -> getUnreadMailForDashboard($conn, $_GET['profileID']); break;
    case 'getSubjectList': $client -> getSubjectList($conn, $_GET['schoolyearID']); break;
    default:
        http_response_code(400);
        echo json_encode([
            "The Requested item does not exist. <br/> Error " . $category
        ]);    
}

?>