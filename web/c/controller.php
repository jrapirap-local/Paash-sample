<?php
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
    case 'connectToDB': $client -> connectToDB(); break;
    case 'getLoginInformation': $client -> getLoginInformation($conn, $_GET['profileID'], validate($_GET['uname']), validate($_GET['pass']), $_GET['ip']); break;
    case 'checkLoginIsValid': $client -> checkLoginIsValid($conn, $_GET['profileID'], $_GET['ip']); break;
    default:
        http_response_code(400);
        echo json_encode([
            "The Requested item does not exist. <br/> Error " . $category
        ]);    
}

?>