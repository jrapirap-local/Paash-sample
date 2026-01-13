<?php

header("Content-Type: application/json");

require '../d/dbconnect.php';
include '../i/interface.php';
include '../s/services.php';

function validate($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$admin = new admin();

// READ JSON BODY
// $input = json_decode(file_get_contents("php://input"), true);
$category = $_GET['category'] ?? null;
// $category = isset($_GET['category']) ? validate($_GET['category']) : null;

switch ($category){
    case 'checkLoginIsValid': $admin -> checkLoginIsValid($conn, $_GET['profileID'], $_GET['ip']); break;
    case 'getNews': $admin -> getLatestNews($conn, $conn2); break;

    case 'getNavProfile': $admin -> getNavProfile($conn, $_GET['profileID'], $_GET['accntLevel']); break;

    case 'getCurrentActiveSchoolyear': $admin -> getCurrentActiveSchoolyear($conn, $_GET['schoolyearId']); break;
    
    case 'logout': $admin -> logout($conn, $_GET['profileID']); break;
    default:
        http_response_code(400);
        echo json_encode([
            "The Requested item does not exist. <br/> Error " . $category
        ]);
}

?>