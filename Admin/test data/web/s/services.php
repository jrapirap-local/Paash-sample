<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    class admin implements i {
        public function errorMessage($conn, $err) {
            //error message
            $errorMsg = str_replace("'","","Error on line ".$err->getLine()." in ".$err->getFile()
            .": ".$err->getMessage().".");
            $trace = str_replace("'","",$err->getTraceAsString());
            $logs = "INSERT INTO tblsystemlogs (logTitle, log, trace, dateLog, loggedBy)
            VALUES ('".$err->getCode()."', '".$errorMsg."', '".$trace."', (SELECT NOW()), '".$err->profileID."')";
            if($conn->query($logs)===TRUE){
                return "A <b class='text-danger'>System Error</b> has occurred. Please try again later. If error still persist, please report to Web Developers. ERROR CODE : " . $err->getMessage();
            }
            
        }
        #region Global
        public function validate($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        public function generateRandomString($length = 20) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[random_int(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        public function randomPassword($length = 10) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[random_int(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        public function getNotification($conn, $conn2, $profileID){
            try {
                $output = array();
                $sql = "SELECT * FROM tblMail WHERE mailRecieverID LIKE ('%$profileID%') AND isSent = 1 AND isActive = 1";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){ 
                    while($row = $result->fetch_assoc()){
                        $output[] = $row;
                     }
                     
                }
                echo json_encode($output);  
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }    
        }  

        public function checkLoginIsValid($conn, $profileID, $ip){
            try {
                
                $output = array();
                $login = "SELECT * FROM tblusers WHERE profileID='$profileID' AND isLoggedIn = 1 AND isActive = 1";
                $result = $conn->query($login);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] = $row;
                    }
                    echo json_encode([
                        "status" => "success",
                        "message" => "Valid login"
                    ]);
                } else {
                    // header ('HTTP/1.1 401 Unauthorized');    
                    echo json_encode([
                        "status" => "VERIFICATION_FAILED",
                        "message" => "Unable to verify login, please login again."
                    ]); 
                }
            }
            catch (Exception $err) {
                header ('HTTP/1.1 500 Internal Server Error');
                throw new Exception($err->getMessage());
            }            
        } 

        public function logout($conn, $profileID) {
            try {
                $sql = "SELECT * FROM tblusers WHERE profileID = '$profileID'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){ 
                        $update = "UPDATE tblusers SET isLoggedIn = 0 WHERE profileID = '".$row["profileID"]."'"; 
                        if($conn->query($update) === TRUE) { 
                            echo json_encode([
                                "status" => "success",
                                "message" => "Session closed"
                            ]);
                        }
                     }
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        } 
        
        public function checkAccountStat($conn, $profileID) {
            try {
                $sql = "SELECT * FROM tblusers WHERE profileID = '$profileID'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){ 
                    echo true;
                } else {
                    echo false;
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }
        
        public function getLatestNews($conn, $conn2){
            try {
                $output = array();
                $sql = "SELECT * FROM tblMail WHERE isAnnouncement = 1 ORDER BY dateCreated desc LIMIT 1";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){ 
                    while($row = $result->fetch_assoc()){
                        $output[] = $row;
                     }
                     
                }
                echo json_encode($output);  
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }               
        }

        public function getRequestList($conn, $profileID){
            try {
                $output = array();
                $sqlselect = "SELECT * FROM tblrequestform WHERE requestedBy = '$profileID'";
                $result = $conn->query($sqlselect);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] = $row;
                    }
                }
                echo json_encode($output);  
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        function createUserName($conn, $firstName, $middleName, $lastName) {
            // Split the names by space
            $fnArr = explode(" ", $firstName);
            $mnArr = explode(" ", $middleName);
            $lnArr = explode(" ", $lastName);
            $int = 1;
            $uname = "";

            // Get first character of each part of the first and middle names
            $fn = '';
            foreach ($fnArr as $part) {
                $fn .= substr($part, 0, 1);
            }

            $mn = '';
            foreach ($mnArr as $part) {
                $mn .= substr($part, 0, 1);
            }

            // Remove periods and commas from last name parts
            $ln = '';
            foreach ($lnArr as $part) {
                $ln .= str_replace([",", "."], "", $part);
            }

            // Combine and return the username
            
            $sql = "SELECT * FROM tblusers WHERE userName = '$uname'";
            $res = $conn->query($sql);
            if ($res->num_rows > 0) { 
                $uname = strtolower(str_replace(" ", "", $fn . $mn . $ln . $int."@paash.com"));
            } else {
                $uname = strtolower(str_replace(" ", "", $fn . $mn . $ln . "@paash.com"));
            }
            return $uname;
        }  


        public function approveRequest($conn, $requestID, $profileID){
            try {
                $output = array();
                $output2 = array();
                $sqlselect = "SELECT * FROM tblrequestform WHERE requestID = '$requestID'";
                $res = $conn->query($sqlselect);
                if ($res->num_rows > 0){
                    while($row = $res->fetch_assoc()){
                        $arr = json_decode($row["request"]);
                        $sqlselect2 = "SELECT * FROM tblusers WHERE profileID = '".$row["requestedBy"]."'";
                        $res2 = $conn->query($sqlselect2);
                         if ($res2->num_rows > 0){
                            while($user = $res2->fetch_assoc()){
                                $output2 = $user;
                                $accnt = $user["accntLevel"];
                                switch($accnt){
                                    case "1"; //faculty
                                        foreach ($arr as $key => $value) {
                                            if($key != "note"){
                                                if(empty($value) || is_null($value)){
                                                    $value = null;
                                                }
                                                $sqlfac = "UPDATE tblfacultyinfo SET ".$key." = '".$value."' WHERE profileID = '".$user["profileID"]."'";
                                                if($conn->query($sqlfac) === TRUE){
                                                    if(empty($value) || is_null($value)){
                                                        array_push($output, $key . " value has been deleted! ");
                                                    } else {
                                                        array_push($output, $key . " has been updated to " . $value);
                                                    }
                                                }
                                            }
                                        }
                                        $sqlupdaterequest = "UPDATE tblrequestform SET requestState = 'Approved', approvedBy = '$profileID', dateApproved = (SELECT NOW()) WHERE requestID = '$requestID'";
                                        $conn->query($sqlupdaterequest);
                                    break;
                                    case "2";//student
                                        foreach ($arr as $key => $value) {
                                            if($key != "note"){
                                                if(empty($value) || is_null($value)){
                                                    $value = null;
                                                }                                                
                                                $sqlstud = "UPDATE tblstudentlist SET ".$key." = '".$value."' WHERE profileID ='".$user["profileID"]."'";
                                                if($conn->query($sqlstud) === TRUE){
                                                    if(empty($value) || is_null($value)){
                                                        array_push($output, $key . " value has been deleted! ");
                                                    } else {
                                                        array_push($output, $key . " has been updated to " . $value);
                                                    }
                                                }
                                            } 
                                        }
                                        $sqlupdaterequest = "UPDATE tblrequestform SET requestState = 'Approved', approvedBy = '$profileID', dateApproved = (SELECT NOW()) WHERE requestID = '$requestID'";
                                        $conn->query($sqlupdaterequest);
                                    break;
                                    case "3";//parent
                                        foreach ($arr as $key => $value) {
                                            if($key != "note"){
                                                if(empty($value) || is_null($value)){
                                                    $value = null;
                                                } 
                                                $sqlparent = "UPDATE tblguardianprofile SET ".$key." = '".$value."' WHERE guardianProfileID = '".$user["profileID"]."'";
                                                if($conn->query($sqlparent) === TRUE){
                                                    if(empty($value) || is_null($value)){
                                                        array_push($output, $key . " value has been deleted! ");
                                                    } else {
                                                        array_push($output, $key . " has been updated to " . $value);
                                                    }
                                                }
                                            } 
                                        }
                                        $sqlupdaterequest = "UPDATE tblrequestform SET requestState = 'Approved', approvedBy = '$profileID', dateApproved = (SELECT NOW()) WHERE requestID = '$requestID'";
                                        $conn->query($sqlupdaterequest);                                        
                                    break;
                                }                            
                            }
                         }

                    }
                }
                echo json_encode($output);  
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }    
        
        public function denyRequest($conn, $requestID, $profileID, $reason){
            try {
                $output = array();
                $output2 = array();
                $sqlselect = "SELECT * FROM tblrequestform WHERE requestID = '$requestID'";
                $res = $conn->query($sqlselect);
                if ($res->num_rows > 0){
                    while($row = $res->fetch_assoc()){
                        $arr = json_decode($row["request"]);
                        $sqlselect2 = "SELECT * FROM tblusers WHERE profileID = '".$row["requestedBy"]."'";
                        $res2 = $conn->query($sqlselect2);
                         if ($res2->num_rows > 0){
                            while($user = $res2->fetch_assoc()){
                                $output2 = $user;
                                $accnt = $user["accntLevel"];
                                switch($accnt){
                                    case "1"; //faculty
                                        foreach ($arr as $key => $value) {
                                            if($key != "note"){
                                                if(empty($value) || is_null($value)){
                                                    array_push($output, $key . " value has been deleted! ");
                                                } else {
                                                    array_push($output, $key . " has been updated to " . $value);
                                                }
                                            }
                                        }
                                        $sqlupdaterequest = "UPDATE tblrequestform SET requestState = 'Denied', reason = '$reason' WHERE requestID = '$requestID'";
                                        $conn->query($sqlupdaterequest);
                                    break;
                                    case "2";//student
                                        foreach ($arr as $key => $value) {
                                            if($key != "note"){
                                                if(empty($value) || is_null($value)){
                                                    array_push($output, $key . " value has been deleted! ");
                                                } else {
                                                    array_push($output, $key . " has been updated to " . $value);
                                                }
                                            }
                                        }
                                        $sqlupdaterequest = "UPDATE tblrequestform SET requestState = 'Denied', reason = '$reason' WHERE requestID = '$requestID'";
                                        $conn->query($sqlupdaterequest);
                                    break;
                                    case "3";//parent
                                        foreach ($arr as $key => $value) {
                                            if($key != "note"){
                                                if(empty($value) || is_null($value)){
                                                    array_push($output, $key . " value has been deleted! ");
                                                } else {
                                                    array_push($output, $key . " has been updated to " . $value);
                                                }
                                            }
                                        }
                                        $sqlupdaterequest = "UPDATE tblrequestform SET requestState = 'Denied', reason = '$reason' WHERE requestID = '$requestID'";
                                        $conn->query($sqlupdaterequest);                                    
                                    break;
                                }                            
                            }
                         }

                    }
                }
                echo json_encode($output);  
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }         
        
        public function getNavProfile($conn, $profileID, $accntLevel){
            try {
                $output = [];
                $stmt = null;
        
                switch ($accntLevel) {
                    case "0": // Admin
                        $stmt = $conn->prepare("SELECT firstName, middleName, lastName, img, isActive FROM tbladminprofile WHERE adminProfile = ?");
                        break;
        
                    case "1": // Faculty
                        $stmt = $conn->prepare("SELECT firstName, middleName, lastName, profileImg AS img, isActive FROM tblfacultyinfo WHERE profileID = ?");
                        break;
        
                    case "2": // Student
                        $stmt = $conn->prepare("
                            SELECT sprofile.classID, studentlist.studentType, sProfile.schoolyearID, sProfile.semesterID, 
                                   sProfile.gradelevelID, sProfile.strandID, track.trackID, studentlist.firstName, 
                                   studentlist.middleName, studentlist.lastName, studentlist.gender, studentlist.eMail, 
                                   guardian.prefix, guardian.guardianFName, guardian.guardianMName, guardian.guardianLName, 
                                   sProfile.guardianRelation, guardian.guardianGender, guardian.guardianEmail, 
                                   guardian.guardianContactNo, users.userName, strand.strandID, studentlist.img AS img, 
                                   users.isActive, class.className
                            FROM tblstudentlist AS studentlist 
                            INNER JOIN tblstudentprofile AS sProfile ON studentlist.profileID = sProfile.profileID 
                            INNER JOIN tblguardianprofile AS guardian ON sProfile.guardianID = guardian.guardianProfileID
                            INNER JOIN tblstrandlist AS strand ON strand.strandID = sProfile.strandID 
                                AND strand.schoolyearID = sProfile.schoolyearID 
                                AND strand.gradelevelID = sProfile.gradelevelID
                            INNER JOIN tbltracks AS track ON track.trackID = strand.trackID
                            INNER JOIN tblusers AS users ON users.profileID = studentlist.profileID
                            INNER JOIN tblclass as class ON class.classProfile = sProfile.classID
                            WHERE studentlist.profileID = ?
                        ");
                        break;
        
                    case "3": // Parent
                        $stmt = $conn->prepare("SELECT guardianFName, guardianMName, guardianLName, profileImg AS img, isActive FROM tblguardianprofile WHERE guardianProfileID = ?");
                        break;
        
                    default:
                        throw new Exception("Invalid account level.");
                }
        
                if ($stmt) {
                    $stmt->bind_param("s", $profileID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $output = $result->fetch_all(MYSQLI_ASSOC);
                }
        
                // Output JSON (optional: return instead of echo)
                echo json_encode($output);
            } catch (Exception $err) {
                $err->profileID = $profileID ?? "paash.system.admin.function:" . __FUNCTION__;
                header('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }
        public function getContactList($conn, $conn2, $profileID) {
            try {
                $output = array();
                $arr = array();
                $index = 0;
                $sql = "SELECT * FROM tblcontacts WHERE ownerProfile = '$profileID'";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){ 
                        if($row["contactList"]!=""){    
                            $output = json_decode($row["contactList"]);
                        }
                        
                     }
                }
                echo json_encode($output);
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function getGroupList($conn, $conn2, $profileID) {
            try {
                $output = array();
                $sql = "SELECT * FROM tblgroups WHERE groupOwner = '$profileID'";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){ 
                        $output[] =  $row;                  
                     }
                }
                echo json_encode($output);
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function addNewContacts($conn, $conn2, $name, $mail, $profileID) {
            try {
                $arr = array();
                $object = array();
                $currobject = array();
                $select = "SELECT * FROM tblusers WHERE userName = '$mail'";
                $resUser = $conn->query($select);
                if ($resUser->num_rows > 0) {
                    while($users = $resUser->fetch_assoc()){ 
                        $sql = "SELECT * FROM tblcontacts WHERE ownerProfile = '$profileID'";
                        $result = $conn2->query($sql);
                        if ($result->num_rows > 0){ //edit
                            while($row = $result->fetch_assoc()){
                                $arr = json_decode($row["contactList"],true);  
                                $object = array("name"=>$name, "eMail"=>$mail, "linkID"=>$users["profileID"]); 
                                $currobject = $object;
                                array_push($arr, $currobject);
                            }
        
                            $sqlinsert = "UPDATE tblcontacts SET contactList = '".json_encode($arr)."' WHERE ownerProfile='".$profileID."'";
                            if ($conn2->query($sqlinsert) === TRUE){
                                echo true;
                            }else {
                                echo false;
                            }
                        } else { //new  
                            $object = array("name"=>$name,"eMail"=>$mail,"linkID"=>$users["profileID"]);
                            $currobject[] = $object;
                        
                            $sqlinsert = "INSERT INTO tblcontacts (ownerProfile, contactList) VALUES('".$profileID."','".json_encode($currobject)."')";
                            if ($conn2->query($sqlinsert) === TRUE){
                                echo true;
                            }else {
                                echo false;
                            }
                        }
                    }
                } else {
                    echo "ERR_USERNOTFOUND";
                }


            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }             
        }

        public function addNewGroupAsContacts($conn, $conn2, $name, $mail, $profileID) {
            try {
                // Prepared statement to find matching groups by email
                $stmtGroup = $conn2->prepare("SELECT groupProfile FROM tblgroups WHERE groupMail = ?");
                $stmtGroup->bind_param("s", $mail);
                $stmtGroup->execute();
                $resGroup = $stmtGroup->get_result();
        
                if ($resGroup->num_rows === 0) {
                    return; // No group found for this email
                }
        
                while ($group = $resGroup->fetch_assoc()) {
                    $groupProfile = $group['groupProfile'];
        
                    // Check if this user already has a contacts list
                    $stmtContact = $conn2->prepare("SELECT contactList FROM tblcontacts WHERE ownerProfile = ?");
                    $stmtContact->bind_param("s", $profileID);
                    $stmtContact->execute();
                    $resContact = $stmtContact->get_result();
        
                    if ($resContact->num_rows > 0) {
                        // Existing contact list
                        $row = $resContact->fetch_assoc();
                        $arr = json_decode($row['contactList'], true) ?? [];
        
                        // Check if email already exists
                        $emails = array_column($arr, 'eMail');
                        if (!in_array($mail, $emails)) {
                            $arr[] = [
                                "name" => $name,
                                "eMail" => $mail,
                                "linkID" => $groupProfile
                            ];
        
                            $jsonList = json_encode($arr, JSON_UNESCAPED_UNICODE);
                            $stmtUpdate = $conn2->prepare("UPDATE tblcontacts SET contactList = ? WHERE ownerProfile = ?");
                            $stmtUpdate->bind_param("ss", $jsonList, $profileID);
                            $stmtUpdate->execute();
                        }
                    } else {
                        // New contact list
                        $newList = [[
                            "name" => $name,
                            "eMail" => $mail,
                            "linkID" => $groupProfile
                        ]];
                        $jsonList = json_encode($newList, JSON_UNESCAPED_UNICODE);
        
                        $stmtInsert = $conn2->prepare("INSERT INTO tblcontacts (ownerProfile, contactList) VALUES (?, ?)");
                        $stmtInsert->bind_param("ss", $profileID, $jsonList);
                        $stmtInsert->execute();
                    }
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }             
        }        

        public function addNewGroups($conn, $conn2, $name, $mail, $profileID) {
            try {
                $arr = array();
                $object = array();
                $object2 = array();
                $currobject = array();
                $select = "SELECT * FROM tblgroups WHERE groupMail = '$mail'";
                $user = $conn2->query($select);
                if ($user->num_rows > 0) {
                    echo "ERR_DUPLICATE";
                } else {
                    $sqlinsert = "INSERT INTO tblgroups (groupProfile, groupName, groupMail, groupOwner, type, isActive, dateCreated)
                    VALUES('".$this->generateRandomString()."','$name', '$mail', '$profileID', 'user.created', 1, (SELECT NOW()))";
                    if ($conn2->query($sqlinsert) === TRUE){
                        $last_id = $conn2->insert_id;
                        $sql = "SELECT * FROM tblgroups WHERE groupID = '$last_id'";
                        $result = $conn2->query($sql);
                        if ($result->num_rows > 0){ //edit
                            $sql2 = "SELECT * FROM tblusers WHERE profileID = '$profileID'";
                            $result2 = $conn->query($sql2);
                            if ($result2->num_rows > 0) {
                                while($row2 = $result2->fetch_assoc()){ 
                                    $accntLevel = $row2["accntLevel"];
                                    switch($accntLevel){
                                        case "0"; //Admin
                                            $sqladmin = "SELECT firstName, lastName
                                            FROM tbladminprofile WHERE adminProfile = '".$profileID."'";
                                            $resadmin = $conn->query($sqladmin);
                                            if ($resadmin->num_rows > 0){
                                                while($admin = $resadmin->fetch_assoc()){
                                                    $object2 = array("name"=>$admin["firstName"] . " " . $admin["lastName"], "eMail"=>$row2["userName"], "linkID"=>$row2["profileID"]);  
                                                }    
                                            }
                                        break;
                                        case "1"; //faculty
                                            $sqlfaculty = "SELECT firstName, lastName
                                            FROM tblfacultyinfo WHERE profileID = '".$profileID."'";
                                            $resfaculty = $conn->query($sqlfaculty);
                                            if ($resfaculty->num_rows > 0){
                                                while($faculty = $resfaculty->fetch_assoc()){
                                                    $object2 = array("name"=>$faculty["firstName"] . " " . $faculty["lastName"], "eMail"=>$row2["userName"], "linkID"=>$row2["profileID"]);  
                                                }    
                                            }
                                        break;
                                        case "2";//student
                                            $sqlstudent = "SELECT firstName, lastName FROM tblstudentlist
                                            WHERE profileID = '".$profileID."'";
                                            $resstudent = $conn->query($sqlstudent);
                                            if ($resstudent->num_rows > 0){
                                                while($student = $resstudent->fetch_assoc()){
                                                    $object2 = array("name"=>$student["firstName"] . " " . $student["lastName"], "eMail"=>$row2["userName"], "linkID"=>$row2["profileID"]); 
                                                }    
                                            }
                                        break;
                                        case "3";//parent
                                            $sqlparent = "SELECT guardianFName, guardianLName
                                            FROM tblguardianprofile WHERE guardianProfileID = '".$profileID."'";
                                            $resparent = $conn->query($sqlparent);
                                            if ($resparent->num_rows > 0){
                                                while($parent = $resparent->fetch_assoc()){
                                                    $object2 = array("name"=>$parent["guardianFName"] . " " . $parent["guardianLName"], "eMail"=>$row2["userName"], "linkID"=>$row2["profileID"]); 
                                                }    
                                            }
                                        break;
                                    }                
                                }
                            }
                            $currobject[] = $object2;
        
                            $sqlinsert2 = "UPDATE tblgroups SET groupMembers = '".json_encode($currobject)."' WHERE groupID = '$last_id'";
                            if ($conn2->query($sqlinsert2) === TRUE){
                                echo true;
                            }else {
                                echo false;
                            }
                        }
                    } else {
                        echo false;
                    }                    
                }


            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }             
        }

        public function getGroupDetails($conn, $conn2, $groupProfile, $profileID) {
            try {
                $output = array();
                $sql = "SELECT * FROM tblgroups WHERE groupOwner = '$profileID' and groupProfile = '$groupProfile'";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){ 
                        $output[] =  $row;                  
                     }
                }
                echo json_encode($output);
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function getGroupMembers($conn, $conn2, $groupProfile, $profileID) {
            try {
                $output = array();
                $sql = "SELECT * FROM tblgroups WHERE groupProfile = '$groupProfile' AND groupOwner = '$profileID'";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){ 
                        if($row["groupMembers"]!=""){    
                            $output = json_decode($row["groupMembers"]);
                        }
                     }
                     echo json_encode($output);
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }     
        
        public function addContactsToGroup($conn, $conn2, $name, $mail, $groupProfile, $profileID) {
            try {
                $arr = array();
                $object = array();
                $select = "SELECT * FROM tblusers WHERE userName = '$mail'";
                $resUser = $conn->query($select);
                if ($resUser->num_rows > 0) {
                    while($user = $resUser->fetch_assoc()) {
                        $sql = "SELECT * FROM tblgroups WHERE groupOwner = '$profileID' AND groupProfile = '$groupProfile'";
                        $result = $conn2->query($sql);
                        if ($result->num_rows > 0){ //edit
                            while($row = $result->fetch_assoc()){
                                $arr = json_decode($row["groupMembers"],true);  
                                $object = array("name"=>$name, "eMail"=>$mail, "linkID"=>$user["profileID"]); 
                                array_push($arr, $object);
                            }
        
                            $sqlinsert = "UPDATE tblgroups SET groupMembers = '".json_encode($arr)."' WHERE groupOwner = '$profileID' AND groupProfile = '$groupProfile'";
                            if ($conn2->query($sqlinsert) === TRUE){
                                echo true;
                            }else {
                                echo false;
                            }
                        }
                    }
                } else {
                    echo "ERR_USERNOTFOUND";
                }


            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }             
        }  
        
        public function removeFromGroup($conn, $conn2, $mail, $groupProfile, $profileID) {
            try {
                $arr = array();
                $object = array();
                $sql = "SELECT * FROM tblgroups WHERE groupOwner = '$profileID' AND groupProfile = '$groupProfile'";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){ //edit
                    while($row = $result->fetch_assoc()){
                        $arr = json_decode($row["groupMembers"],true);  
                    }
                    // Find the index of the element with name
                    foreach ($arr as $index => $item) {
                        if ($item['eMail'] === $mail) {
                            unset($arr[$index]); // Delete the item
                            break; // Stop after finding the first match
                        }
                    }
                    // Re-index the array if needed
                    $arr = array_values($arr);

                    $sqlinsert = "UPDATE tblgroups SET groupMembers = '".json_encode($arr)."' WHERE groupOwner = '$profileID' AND groupProfile = '$groupProfile'";
                    if ($conn2->query($sqlinsert) === TRUE){
                        echo true;
                    }else {
                        echo false;
                    }
                }


            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }             
        }  
        
        public function updateGroupDetails($conn, $conn2, $name, $mail, $groupProfile, $profileID) {
            try {
                $sql = "SELECT * FROM tblgroups WHERE groupOwner = '$profileID' AND groupProfile = '$groupProfile'";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){ //edit
                    $sqlupdate = "UPDATE tblgroups SET groupName = '$name', groupMail = '$mail' WHERE groupOwner = '$profileID' AND groupProfile = '$groupProfile'";
                    if ($conn2->query($sqlupdate) === TRUE){
                        echo true;
                    }else {
                        echo false;
                    }
                } else {
                    echo "ERR_UPDATE";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }             
        }   
        
        public function deleteGroup($conn, $conn2, $groupProfile, $profileID) {
            try {
                $sql = "SELECT * FROM tblgroups WHERE groupOwner = '$profileID' AND groupProfile = '$groupProfile'";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){ //edit
                    $sqldelete = "DELETE FROM tblgroups WHERE groupOwner = '$profileID' AND groupProfile = '$groupProfile'";
                    if ($conn2->query($sqldelete) === TRUE){
                        echo true;
                    }else {
                        echo false;
                    }
                } else {
                    echo "ERR_DEL";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }             
        }   
        
        public function getContactDetails($conn, $conn2, $linkID, $profileID) {
            try {
                $output = array();
                $arr = array();
                $index = 0;
                $sql = "SELECT * FROM tblcontacts WHERE ownerProfile = '$profileID'";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){ 
                        if($row["contactList"]!=""){    
                            $arr = json_decode($row["contactList"], true);
                        }
                    }

                    foreach ($arr as $item) {
                        if ($item['linkID'] === $linkID) {
                            $output = $item;
                            break;
                        }
                    }

                }
                echo json_encode($output);
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }  
        
        public function updateContactDetails($conn, $conn2, $linkID, $name, $mail, $profileID) {
            try {
                $output = array();
                $arr = array();
                $index = 0;
                $sql = "SELECT * FROM tblcontacts WHERE ownerProfile = '$profileID'";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){ 
                        if($row["contactList"]!=""){    
                            $arr = json_decode($row["contactList"], true);
                        }
                    }

                    foreach ($arr as $item) {
                        if ($item['linkID'] === $linkID) {
                            $arr[$index]['name'] = $name;
                            $arr[$index]['eMail'] = $mail;
                            break;
                        }
                    }

                    $output = array_values($arr);

                    $sqlinsert = "UPDATE tblcontacts SET contactList = '".json_encode($output)."' WHERE ownerProfile = '$profileID'";
                    if ($conn2->query($sqlinsert) === TRUE){
                        echo true;
                    }else {
                        echo false;
                    }

                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }  

        public function deleteContacts($conn, $conn2, $linkID, $profileID) {
            try {
                $output = array();
                $arr = array();
                $index = 0;
                $sql = "SELECT * FROM tblcontacts WHERE ownerProfile = '$profileID'";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){ 
                        if($row["contactList"]!=""){    
                            $arr = json_decode($row["contactList"], true);
                        }
                    }

                    foreach ($arr as $item) {
                        if ($item['linkID'] === $linkID) {
                            unset($arr[$index]);
                            break;
                        }
                    }

                    $output = array_values($arr);

                    $sqlinsert = "UPDATE tblcontacts SET contactList = '".json_encode($output)."' WHERE ownerProfile = '$profileID'";
                    if ($conn2->query($sqlinsert) === TRUE){
                        echo true;
                    }else {
                        echo false;
                    }

                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }          

        public function uploadProfileImg($conn, $profileID, $file){
            try {
                // $image = $_FILES['profileUpload']['tmp_name'];
                $imgContent = file_get_contents($file);
        
                // Insert image data into database as BLOB
                $sql = "UPDATE tbladminprofile SET img = (?) WHERE adminProfile = '" . $profileID . "'";
                //$sql = "INSERT INTO tblfacultyinfo(img) VALUES(?) WHERE profileID = '" . $profileID . "'";
                $statement = $conn->prepare($sql);
                $statement->bind_param('s', $imgContent);
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }    
        } 
        #endregion
        #region ControlPanel
        public function getActiveStudentCount($conn, $schoolyearID){
            try {
                $output = 0;
                $sql = "SELECT * FROM tblusers AS users
                INNER JOIN tblstudentprofile AS profile ON profile.profileID = users.profileID
                WHERE users.accntLevel = 2 AND users.isActive = 1 AND profile.schoolyearID = '$schoolyearID'";
                $result = $conn->query($sql);
                echo $result->num_rows;  
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }    
        }  
        public function getActiveStrandCount($conn, $schoolyearID){
            try {
                $output = 0;
                $sql = "SELECT * FROM tblstrandlist WHERE isActive = 1 AND schoolyearID = '$schoolyearID'";
                $result = $conn->query($sql);
                echo $result->num_rows;  
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }      
        }  
        public function getActiveFacultyCount($conn){
            try {
                $output = 0;
                $sql = "SELECT * FROM tblusers WHERE accntLevel = 1 AND isActive = 1";
                $result = $conn->query($sql);
                echo $result->num_rows;  
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }      
        }   
        public function getCurrentActiveSchoolyear($conn, $schoolyearID){
            try {
                $output = array();
                $sqlselect = "SELECT * FROM tblschoolyear WHERE isCurrentSY = 1";
                $result = $conn->query($sqlselect);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                        
                    }  
                }
                echo json_encode($output); 
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }
        #endregion

        #region Strands
        public function getStrandsList($conn, $schoolyearID){
            try {
                $output = array();
                $sql = "SELECT * FROM tblstrandlist AS strandlist 
                INNER JOIN tbltracks AS tracks ON strandlist.trackID = tracks.trackID 
                INNER JOIN tblstrands AS strands ON strandlist.strandID = strands.strandID
                INNER JOIN tblschoolyear AS sy ON sy.schoolyearID = strandlist.schoolyearID
                INNER JOIN tblgradelevel AS grade ON strandlist.gradelevelID = grade.gradelevelID 
                WHERE strandlist.isActive = 1 AND strandlist.schoolyearID= '$schoolyearID'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                     
                }
                echo json_encode($output);  
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }
        
        public function generateColapsePanel($strandlistID, $strandID, $schoolyearID, $gradelevelID, $description){
            echo '
                <div class="accordion-body"> 
                    <div class="row">
                        <div class="col-lg-10 blockquote-footer">
                        '.$description.'
                        </div>
                        <div class="col" style="text-align: right; vertical-align: right; align-items: right;">
                            <a class="btn btn-danger btn-sm border-radius-sm w-100" data-strandID="'.$strandID.'" data-schoolyearID="'.$schoolyearID.'" data-gradelevelID="'.$gradelevelID.'" id="'.$strandlistID.'" onclick="deleteStrand(this)">Delete Strand</a>
                        </div>
                    </div>
                    <hr class="bg-dark"/>
                    <div class="nav nav-tabs border-radius-0 mb-2" role="group" aria-label="Basic radio toggle button group" >
                      <input data-table="tblclass'.$gradelevelID.$schoolyearID.'0'.$strandlistID.'" data-strand="'.$strandID.'" data-schoolyear="'.$schoolyearID.'" data-gradelevel="'.$gradelevelID.'" data-sem="0" onclick="openSem(event, class'.$gradelevelID.$schoolyearID.'0'.$strandlistID.', this)" id="class'.$strandlistID.'0" type="radio" class="btn-check" name="btnradio" autocomplete="off" />
                      <label class="nav-link tablinks" for="class'.$strandlistID.'0" id="lbl'.$gradelevelID.$schoolyearID.'0'.$strandID.'">Class</label>

                      <input data-table="tbl'.$gradelevelID.$schoolyearID.'1'.$strandlistID.'" data-strand="'.$strandID.'" data-schoolyear="'.$schoolyearID.'" data-gradelevel="'.$gradelevelID.'" data-sem="1" onclick="openSem(event, sem1'.$gradelevelID.$schoolyearID.'1'.$strandlistID.', this)" id="default'.$strandlistID.'1" type="radio" class="btn-check" name="btnradio" autocomplete="off" checked />
                      <label class="nav-link tablinks" for="default'.$strandlistID.'1" id="lbl'.$gradelevelID.$schoolyearID.'1'.$strandID.'">First Semester</label>

                      <input data-table="tbl'.$gradelevelID.$schoolyearID.'2'.$strandlistID.'" data-strand="'.$strandID.'" data-schoolyear="'.$schoolyearID.'" data-gradelevel="'.$gradelevelID.'" data-sem="2" onclick="openSem(event, sem2'.$gradelevelID.$schoolyearID.'2'.$strandlistID.', this)" id="default'.$strandlistID.'2" type="radio" class="btn-check" name="btnradio" autocomplete="off" />
                      <label class="nav-link tablinks" for="default'.$strandlistID.'2" id="lbl'.$gradelevelID.$schoolyearID.'2'.$strandID.'">Second Semester</label>
                    </div>
                    <div id="class'.$gradelevelID.$schoolyearID.'0'.$strandlistID.'" class="tabcontent">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title">Class List</h3>
                                <div class="card-tools">
                                    <ul class="pagination pagination-sm float-end">
                                        <li class="page-item"><a class="btn btn-link btn-sm" data-table="tblclass'.$gradelevelID.$schoolyearID.'0'.$strandlistID.'" data-strandID="'.$strandID.'" data-schoolyearID="'.$schoolyearID.'" data-gradelevelID="'.$gradelevelID.'" data-semesterID="1" data-strandlistID="'.$strandlistID.'" onclick="addSection()">Create new class</a></li>
                                        
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body flex p-3">
                                <table id="tblclass'.$gradelevelID.$schoolyearID.'0'.$strandlistID.'" class="table table-hover">
                                    <thead class="bg-secondary text-warning text-center">
                                        <tr>
                                            <th>Classes</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-xs">
                                    </tbody>    
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="sem1'.$gradelevelID.$schoolyearID.'1'.$strandlistID.'" class="tabcontent">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title">First Semester</h3>
                                <div class="card-tools">
                                    <ul class="pagination pagination-sm float-end">
                                        <li class="page-item"><a class="btn btn-link btn-sm" data-table="tbl'.$gradelevelID.$schoolyearID.'1'.$strandlistID.'" data-strandID="'.$strandID.'" data-schoolyearID="'.$schoolyearID.'" data-gradelevelID="'.$gradelevelID.'" data-semesterID="1" data-strandlistID="'.$strandlistID.'" onclick="addSubject(this)">Add Subject</a></li>
                                        <li class="page-item"><a class="btn btn-link btn-sm text-danger" data-table="tbl'.$gradelevelID.$schoolyearID.'1'.$strandlistID.'" data-gradelevel="'.$gradelevelID.'" data-schoolyear="'.$schoolyearID.'" data-strand="'.$strandID.'" data-semester="1" onclick="clearSemester(this)">Clear Subjects</a></li>
                                        <li class="page-divider">|</li>
                                        <li class="page-item"><a class="btn btn-link btn-sm" data-gradelevel="'.$gradelevelID.'" data-schoolyear="'.$schoolyearID.'" data-strand="'.$strandID.'" data-semester="1" onclick="studentPromotion(this)">Promotion Student Semester</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body flex p-3">
                                <table id="tbl'.$gradelevelID.$schoolyearID.'1'.$strandlistID.'" class="table table-hover">
                                    <thead class="bg-secondary text-warning text-center">
                                        <tr>
                                            <th>Subject</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-xs">
                                    </tbody>    
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="sem2'.$gradelevelID.$schoolyearID.'2'.$strandlistID.'" class="tabcontent">
                        <div class="card lg-4">
                            <div class="card-header">
                                <h3 class="card-title">Second Semester</h3>
                                <div class="card-tools">
                                    <ul class="pagination pagination-sm float-end">
                                        <li class="page-item"><a class="btn btn-link btn-sm" data-table="tbl'.$gradelevelID.$schoolyearID.'2'.$strandlistID.'" data-strandID="'.$strandID.'" data-schoolyearID="'.$schoolyearID.'" data-gradelevelID="'.$gradelevelID.'" data-semesterID="2" data-strandlistID="'.$strandlistID.'" onclick="addSubject(this)">Add Subject</a></li>
                                        <li class="page-item"><a class="btn btn-link btn-sm text-danger" data-table="tbl'.$gradelevelID.$schoolyearID.'2'.$strandlistID.'" data-gradelevel="'.$gradelevelID.'" data-schoolyear="'.$schoolyearID.'" data-strand="'.$strandID.'" data-semester="2" onclick="clearSemester(this)">Clear Subjects</a></li>
                                        <li class="page-divider">|</li>
                                        <li class="page-item"><a class="btn btn-link btn-sm" data-gradelevel="'.$gradelevelID.'" data-schoolyear="'.$schoolyearID.'" data-strand="'.$strandID.'" data-semester="2" onclick="studentPromotion(this)">Promotion Student Level</a></li>                                        
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body flex p-3">
                                <table id="tbl'.$gradelevelID.$schoolyearID.'2'.$strandlistID.'" class="table table-hover">
                                    <thead class="bg-dark text-warning text-center">
                                        <tr>
                                            <th>Subject</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-xs">
                                    </tbody>    
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>         
            ';
        }

        public function getSubjectList($conn, $schoolyearID, $strandID, $semesterID, $gradelevelID){
            try {
                $output = array();
                $sqlselect = "SELECT * FROM ((tblsubjectlist INNER JOIN tblsubjects ON tblsubjectlist.subjectID = tblsubjects.subjectID)) 
                WHERE tblsubjectlist.schoolyearID =' ".$schoolyearID."' AND tblsubjectlist.strandID = '".$strandID."' AND tblsubjectlist.semesterID = '".$semesterID."' AND tblsubjectlist.gradelevelID = '".$gradelevelID."' AND tblsubjectlist.isActive = 1";
                $result = $conn->query($sqlselect);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                        
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function getAcademicTrack($conn){
            try {
                $output = array();
                $sql = "SELECT * FROM tbltracks WHERE isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                        
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function getStrands($conn, $trackID){
            try {
                $output = array();
                $sql = "SELECT * FROM tblstrands WHERE trackID IN ('".$trackID."') AND isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                        
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function getSchoolyear($conn){
            try {
                $output = array();
                $sql = "SELECT * FROM tblschoolyear WHERE isCurrentSY = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                        
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function getSemester($conn){
            try {
                $output = array();
                $sql = "SELECT * FROM tblsemester WHERE isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                        
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function getGradelevel($conn){
            try {
                $output = array();
                $sql = "SELECT * FROM tblgradelevel WHERE isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                        
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function createStrand($conn, $trackID, $strandID, $schoolyearID, $semesterID, $gradelevelID, $desc){
            try {
                $isSuccess = true;
                $output = array();
                $sql = "SELECT * FROM tblstrandlist AS strandlist 
                INNER JOIN tblsubjectlist AS subjectlist 
                ON strandlist.strandID = subjectlist.strandID 
                WHERE strandlist.schoolyearID = '".$schoolyearID."' 
                AND subjectlist.semesterID = '".$semesterID."' 
                AND strandlist.trackID = '" . $trackID . "' 
                AND strandlist.strandID = '". $strandID . "' 
                AND strandlist.gradelevelID = '".$gradelevelID."' 
                AND strandlist.isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    $isSuccess = false;
                    echo "ERR_DUPLICATE";
                } else {
                    $sql2 = "SELECT * FROM tblstrandlist WHERE strandID = '".$strandID."' AND gradelevelID = '".$gradelevelID."' AND schoolyearID = '".$schoolyearID."' AND isActive = 1";
                    $result2 = $conn->query($sql2);
        
                    if ($result2->num_rows > 0) {
                        //no need create new entry
                    } 
                    else{
                        $sql3 = "INSERT INTO tblstrandlist (schoolyearID, trackID, strandID, gradelevelID, strandDescription)
                        VALUES ('".$schoolyearID."', '".$trackID."', '".$strandID."','".$gradelevelID."', '".$desc."')";
                        $conn->query($sql3);
                    }
                    $firstSem = ($semesterID == 1) ? 1 : 0;
                    $secondSem = ($semesterID == 2) ? 1 : 0;

                    switch($semesterID) {
                        case 1:
                            $sqlsubject = "SELECT * FROM tblsubjects 
                            WHERE strandID = '".$strandID."' 
                            AND (firstSem = '".$firstSem."')
                            AND gradelevelID LIKE '%".$gradelevelID."%' 
                            AND isActive = 1";
                            $resultSubject1 = $conn->query($sqlsubject);
                            if ($resultSubject1->num_rows > 0) {
                                // output data of each row
                                while($rowSubject1 = $resultSubject1->fetch_assoc()) {
                                    $sqlinsertsubject1 = "INSERT INTO tblsubjectlist (strandID, subjectID, schoolyearID, semesterID, gradelevelID) VALUE('".$strandID."','".$rowSubject1['subjectID']."','".$schoolyearID."','".$semesterID."','".$gradelevelID."')";
                                    $conn->query($sqlinsertsubject1);
                                }
                            }
                            break;
                        case 2:
                            $sqlsubject = "SELECT * FROM tblsubjects 
                            WHERE strandID = '".$strandID."' 
                            AND (secondSem = '".$secondSem."' )
                            AND gradelevelID LIKE '%".$gradelevelID."%' 
                            AND isActive = 1";
                            $resultSubject1 = $conn->query($sqlsubject);
                            if ($resultSubject1->num_rows > 0) {
                                // output data of each row
                                while($rowSubject1 = $resultSubject1->fetch_assoc()) {
                                    $sqlinsertsubject1 = "INSERT INTO tblsubjectlist (strandID, subjectID, schoolyearID, semesterID, gradelevelID) VALUE('".$strandID."','".$rowSubject1['subjectID']."','".$schoolyearID."','".$semesterID."','".$gradelevelID."')";
                                    $conn->query($sqlinsertsubject1);
                                }
                            }
                            break;
                    }
                    if($isSuccess == true){
                        echo "Strand has been created!";
                    } else {
                        throw new Exception("ERR_CREATE");
                    }
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function deleteStrand($conn, $strandID, $schoolyearID, $gradelevelID){
            try {
                $sqlupdate ="DELETE FROM tblsubjectlist WHERE strandID = '" . $strandID . "' AND schoolyearID = '".$schoolyearID."' AND gradelevelID = '".$gradelevelID."' AND isActive = 1";
                $result = $conn->query($sqlupdate);
            
                $sqldelete = "DELETE FROM tblstrandlist WHERE  strandID = '" . $strandID . "' AND schoolyearID = '".$schoolyearID."' AND gradelevelID = '".$gradelevelID."' AND isActive = 1";
                if ($conn->query($sqldelete) === TRUE){
                    echo "Strand has been Deleted!";
                }else {
                    throw new Exception("ERR_DEL");
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function getStrandName($conn, $strandID){
            try {
                $sql = "SELECT * FROM tblstrands WHERE strandID = '".$strandID."' AND isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo $row['strand'];
                    }  
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function getSubjects($conn, $semesterID, $gradelevelID, $schoolyearID, $strandID){
            try {
                $output = array();
                $sql = "SELECT * FROM tblsubjects WHERE strandID IN(1001,1002) AND subjectID NOT IN (SELECT subjectID FROM tblsubjectlist WHERE strandID = '".$strandID."' AND semesterID = '".$semesterID."' AND gradelevelID ='".$gradelevelID."' AND schoolyearID = '".$schoolyearID."') AND isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;  
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }
        
        public function addNewsubject($conn, $object){
            try {
                $output = array();
                $sql = "SELECT * FROM tblsubjectlist AS subjectlist 
                INNER JOIN tblsubjects AS subjects ON subjectlist.strandID = subjects.strandID 
                WHERE subjectlist.strandID = '".$object['strandID']."' 
                AND subjectlist.schoolyearID = '".$object['schoolyearID']."' 
                AND subjectlist.semesterID = '".$object['semesterID']."' 
                AND subjectlist.gradelevelID = '".$object['gradelevelID']."' 
                AND subjectlist.subjectID = '".$object['subjectID']."'
                OR subjects.subject = '".$object['subjectName']."'
                AND subjectlist.isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) { 
                    echo "ERR_DUPLICATE";
                } else {
                    $sql2 = "INSERT INTO tblsubjectlist (schoolyearID, strandID, gradelevelID, semesterID, subjectID)
                    VALUES ('".$object['schoolyearID']."', '".$object['strandID']."','".$object['gradelevelID']."', '".$object['semesterID']."', '".$object['subjectID']."')";
                    if($conn->query($sql2) === TRUE){ 
                        echo "Subject Added!";
                    } else{ 
                        throw new Exception("ERR_CREATE");
                    }
        
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function removeSubjects($conn, $subjectlistID){
            try {
                $sqldelete = "DELETE FROM tblsubjectlist WHERE subjectlistID = '".$subjectlistID."'";
                if ($conn->query($sqldelete) === TRUE){
                    echo "Subject Deleted!";
                }else {
                    throw new Exception("ERR_DEL");
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }


        public function clearSemester($conn, $strandID, $schoolyearID, $semesterID, $gradelevelID){
            try {
                $sqldelete = "DELETE FROM tblsubjectlist WHERE strandID = '".$strandID."' AND schoolyearID = '".$schoolyearID."' AND semesterID = '".$semesterID."' AND gradelevelID = '".$gradelevelID."' AND isActive = 1";
                if ($conn->query($sqldelete) === TRUE){
                    echo "Subject for Semester ".$semesterID." has been removed!";
                }else {
                    throw new Exception("ERR_DEL");
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }
        
        public function getStrandNameForPromo($conn, $object){
            try {
                $output = array();
                $sql = "SELECT * FROM tblstrandlist AS strandlist 
                INNER JOIN tbltracks AS tracks ON strandlist.trackID = tracks.trackID 
                INNER JOIN tblstrands AS strands ON strandlist.strandID = strands.strandID
                INNER JOIN tblschoolyear AS sy ON sy.schoolyearID = strandlist.schoolyearID
                INNER JOIN tblgradelevel AS grade ON strandlist.gradelevelID = grade.gradelevelID 
                WHERE strandlist.isActive = 1 AND strandlist.schoolyearID= '".$object["schoolyearID"]."' AND strandlist.strandID = '".$object["strandID"]."' AND strandlist.gradelevelID = '".$object["gradelevelID"]."'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                     
                }
                echo json_encode($output);  
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }  
        
        public function getAllStudentListForPromoPerStrand($conn, $object){
            try {
                $output = array();
                $sql = "SELECT * FROM tblgradesheet AS gs
                INNER JOIN tblstudentlist as s ON s.profileID = gs.studentID 
                INNER JOIN tblstudentprofile AS p ON p.profileID = s.profileID WHERE gs.strandID = '".$object["strandID"]."' AND gs.schoolyearID = '".$object["schoolyearID"]."' AND gs.gradelevelID = '".$object["gradelevelID"]."' AND p.semesterID = '".$object["semesterID"]."' AND gs.isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                     
                }
                echo json_encode($output);  
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }  
        
        public function promotionPreparation($conn, $conn2, $object){
            try {
                $className = "";
                $count = 1;
                $count2 = 1;
                $classProfile = "";
                $classMail = "";
                $strand = "SELECT shortName FROM tblstrands WHERE strandID = '".$object['strandID']."'";
                $rstrand = $conn->query($strand);
                if ($rstrand->num_rows > 0){
                    while($rowstrand = $rstrand->fetch_assoc()){
                        $className = $rowstrand['shortName'];
                    }
                }
                
                do{
                    $classNametmp = $className . $count;
                    $sqlTest = "SELECT * FROM tblclass WHERE className = '" . $classNametmp . "' AND isActive = 1";
                    $resultTest = $conn->query($sqlTest);
                    $count++;
                } while($resultTest->num_rows > 0); //loop till no match in className    
                $className = $classNametmp;
                
                do{
                    $classProfile = $this->generateRandomString();
                    $sqlTest = "SELECT * FROM tblclass WHERE classProfile = '" . $classProfile . "'";
                    $resultTest = $conn->query($sqlTest);
                } while($resultTest->num_rows > 0); //loop till no match in classProfile          
                
                //Generate Class Mail
                do{
                    $classMail = $className.'class_'.$object['schoolyearID'].$count2.'@paash.com';
                    $sqlTest = "SELECT * FROM tblclass WHERE classMail = '" . $classMail . "'";
                    $resultTest = $conn->query($sqlTest);
                    $count2++;
                } while($resultTest->num_rows > 0); //loop till no match in classMail    
                
                if($object['semesterID']==1) { $sem = 2; $glvl = $object['gradelevelID']; } elseif($object['semesterID']==2) { $sem = 1; $glvl = 12; }
                
                if(($glvl == 11) || ($glvl == 12 && $sem == 1)){
                    $sqlinsert = "INSERT INTO tblclass (classProfile, strandID, gradelevelID, schoolyearID, className, classMail,  enrolledStudent, maxStudent, semesterID)
                    VALUES ('$classProfile', '".$object['strandID']."', '$glvl', '".$object['schoolyearID']."', '$className', '".strtolower($classMail)."', '0', '50','$sem')";
                    if($conn->query($sqlinsert)===TRUE){
                        $classMailReg = "INSERT INTO tblgroups (groupProfile, groupName, groupMail, groupOwner, type, isActive, dateCreated)
                        VALUES ('$classProfile','$className','".strtolower($classMail)."','system.class', 'class', 1, (SELECT NOW()))";
                        if($conn2->query($classMailReg) === TRUE){
                            echo $classProfile;
                        }
                        else{ 
                            $sqldel = "DELETE FROM tblclass WHERE classID = '$classProfile'";
                            $conn->query($sqldel);
                            throw new Exception("ERR_CREATE");
                        }                    
                        
                    } else {
                        throw new Exception("ERR_CREATE");
                    }                      
                }
            
                
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        } 
        
        public function removeFromClassGroup($conn, $conn2, $mail, $groupProfile, $profileID) {
            try {
                $arr = array();
                
                $stmt = $conn2->prepare("SELECT groupMembers FROM tblgroups WHERE groupProfile = ?");
                $stmt->bind_param("s", $groupProfile);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $arr = json_decode($row["groupMembers"], true);
                
                    // Find and remove the member
                    foreach ($arr as $index => $item) {
                        if ($item['eMail'] === $mail) {
                            unset($arr[$index]);
                            break;
                        }
                    }
                
                    // Reindex array
                    $arr = array_values($arr);
                
                    // Update database
                    $stmt = $conn2->prepare("UPDATE tblgroups SET groupMembers = ? WHERE groupProfile = ?");
                    $json_members = json_encode($arr);
                    $stmt->bind_param("ss", $json_members, $groupProfile);
                
                    $stmt->execute();
                }
        
            } catch (Exception $err) {
                $err->profileID = $profileID ?? "paash.system.admin.function:" . __FUNCTION__;
                header('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }

        
        public function addContactsToGroupClass($conn, $conn2, $name, $mail, $groupProfile, $profileID) {
            try {
                // Find user by email
                $stmt = $conn->prepare("SELECT profileID FROM tblusers WHERE userName = ?");
                $stmt->bind_param("s", $mail);
                $stmt->execute();
                $resUser = $stmt->get_result();
        
                if ($resUser->num_rows === 0) {
                    echo "ERR_USERNOTFOUND";
                    return;
                }
        
                $user = $resUser->fetch_assoc();
                $linkID = $user["profileID"];
        
                // Find the target group
                $stmt2 = $conn2->prepare("SELECT groupMembers FROM tblgroups WHERE groupProfile = ?");
                $stmt2->bind_param("s", $groupProfile);
                $stmt2->execute();
                $result = $stmt2->get_result();
        
                if ($result->num_rows === 0) {
                    echo "ERR_GROUPNOTFOUND";
                    return;
                }
        
                $row = $result->fetch_assoc();
                $arr = json_decode($row["groupMembers"], true);
        
                if (!is_array($arr)) {
                    $arr = [];
                }
        
                // Prevent duplicate entry
                foreach ($arr as $member) {
                    if ($member["eMail"] === $mail) {
                        echo "ERR_DUPLICATE";
                        return;
                    }
                }
        
                // Add new member
                $object = ["name" => $name, "eMail" => $mail, "linkID" => $linkID];
                $arr[] = $object;
        
                // Update the group
                $membersJson = json_encode($arr, JSON_UNESCAPED_UNICODE);
                $update = $conn2->prepare("UPDATE tblgroups SET groupMembers = ? WHERE groupProfile = ?");
                $update->bind_param("ss", $membersJson, $groupProfile);
        
                $update->execute();
        
            } catch (Exception $err) {
                $err->profileID = $profileID ?? "system:" . __FUNCTION__;
                header('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }  
        
        
        public function sendNotif($conn, $conn2, $isAnnouncement, $to, $from, $title, $msg) {
            try {
                $conveID = $this->generateRandomString();
                $arr = [];
                $setAnn = false;
        
                if ($isAnnouncement === "true") {
                    $sql = "SELECT profileID FROM tblusers WHERE isActive = 1";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $arr[] = (object)["profileID" => $row['profileID'], "isRead" => 0];
                    }
                    $setAnn = true;
                } else {
                    $specialGroups = [
                        "all@paash.com"      => "SELECT profileID FROM tblusers",
                        "students@paash.com" => "SELECT profileID FROM tblusers WHERE accntLevel = 2",
                        "faculty@paash.com"  => "SELECT profileID FROM tblusers WHERE accntLevel = 1",
                        "parents@paash.com"  => "SELECT profileID FROM tblusers WHERE accntLevel = 3"
                    ];
        
                    foreach ($to as $x) {
                        if (isset($specialGroups[$x])) {
                            $result = $conn->query($specialGroups[$x]);
                            while ($row = $result->fetch_assoc()) {
                                $arr[] = (object)["profileID" => $row['profileID'], "isRead" => 0];
                            }
                        } else {
                            // Check if it's a group
                            $stmt = $conn2->prepare("SELECT groupMembers FROM tblgroups WHERE groupMail = ?");
                            $stmt->bind_param("s", $x);
                            $stmt->execute();
                            $resgroup = $stmt->get_result();
        
                            if ($resgroup->num_rows > 0) {
                                while ($group = $resgroup->fetch_assoc()) {
                                    $members = json_decode($group["groupMembers"], true);
                                    foreach ($members as $item) {
                                        $arr[] = (object)["profileID" => $item['linkID'], "isRead" => 0];
                                    }
                                }
                            } else {
                                // Regular user
                                $stmt = $conn->prepare("SELECT profileID FROM tblusers WHERE userName = ?");
                                $stmt->bind_param("s", $x);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                while ($row = $res->fetch_assoc()) {
                                    $arr[] = (object)["profileID" => $row['profileID'], "isRead" => 0];
                                }
                            }
                        }
                    }
                }
        
                // Insert new mail
                $stmt = $conn2->prepare("
                    INSERT INTO tblMail (conversationID, mailTitle, mailContent, mailCreatorID, mailSenderID, mailRecieverID, dateCreated, dateSent, isRead, isActive, isAnnouncement)
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW(), 1, 1, ?)
                ");
                $json = json_encode($arr);
                $stmt->bind_param("ssssssi", $conveID, $title, $msg, $from, $from, $json, $setAnn);
                $stmt->execute();
    
            } catch (Exception $e) {
                header('HTTP/1.1 500 Internal Server Error');
                echo json_encode(["error" => "Failed to compose mail", "details" => $e->getMessage()]);
            }
        }        
        
        public function step3PromoteStudent($conn, $conn2, $object) {
            try {
                foreach ($object['promoList'] as $ID) { 
                    $tmpEmail = "";
                    $tmpGID = "";
                    $sel = "SELECT * FROM tblstudentlist AS l
                    INNER JOIN tblstudentprofile AS p ON p.profileID = l.profileID 
                    INNER JOIN tblusers AS u ON u.profileID = l.profileID WHERE l.profileID = '".$ID['pid']."' AND l.isActive = 1";
                    $rsel = $conn->query($sel);
                    if ($rsel->num_rows > 0){
                        while($row = $rsel->fetch_assoc()){
                            $name = $row['lastName']. ', ' . $row['firstName'];
                            $tmpGID = $row['classID'];
                            if(($object["gradelevelID"] == 11) || ($object["gradelevelID"] == 12 && $object["semesterID"] == 1)) {
                                $this->addContactsToGroupClass($conn, $conn2, $name, $row['userName'], $object['classProfile'], $ID['pid']);
                            }
                            
                            $sel2 = "SELECT * FROM tblclass WHERE classProfile = '".$row['classID']."'";
                            $rsel2 = $conn->query($sel2);
                            if ($rsel2->num_rows > 0){
                                while($row2 = $rsel2->fetch_assoc()){
                                    $tmpEmail = $row2['classMail'];
                                    
                                    $output = array();
                                    $arr = array();
                                    $index = 0;
                                    $sql = "SELECT * FROM tblcontacts WHERE ownerProfile = '".$ID['pid']."'";
                                    $result = $conn2->query($sql);
                                    if ($result->num_rows > 0){
                                        while($row3 = $result->fetch_assoc()){ 
                                            if($row3["contactList"]!=""){    
                                                $arr = json_decode($row3["contactList"], true);
                                            }
                                        }
                    
                                        foreach ($arr as $item) {
                                            if ($item['eMail'] === $tmpEmail) {
                                                unset($arr[$index]);
                                                break;
                                            }
                                        }
                    
                                        $output = array_values($arr);
                    
                                        $sqlupdatemail = "UPDATE tblcontacts SET contactList = '".json_encode($output)."' WHERE ownerProfile = '".$ID['pid']."'";
                                        $conn2->query($sqlupdatemail);
                    
                                    }                                     
                                    $this->removeFromClassGroup($conn, $conn2, $row['userName'], $tmpGID, $ID['pid']);
                                }
                            }
                            if(($object["gradelevelID"] == 11) || ($object["gradelevelID"] == 12 && $object["semesterID"] == 1)){
                                if($object["semesterID"] == 1){
                                    $update = "UPDATE tblstudentprofile SET semesterID = 2, classID = '".$object['classProfile']."' WHERE profileID = '".$ID['pid']."'";
                                    $update2 = "UPDATE tblclassmember SET isActive = 0 WHERE studentProfile = '".$ID['pid']."'";
                                    // $conn->query($update);
                                    // $conn->query($update2);
                                    $insert = "INSERT INTO tblclassmember (classProfile, studentProfile, isActive) VALUES ('".$object['classProfile']."', '".$ID['pid']."', 1)";
                                    $conn->query($insert);
                                }
                                if($object["semesterID"]== 2 && $object["gradelevelID"] == 11){
                                    $update = "UPDATE tblstudentprofile SET semesterID = 1, gradelevelID = 12, classID = '".$object['classProfile']."' WHERE profileID = '".$ID['pid']."'";
                                    $update2 = "UPDATE tblclassmember SET isActive = 0 WHERE studentProfile = '".$ID['pid']."'";
                                    // $conn->query($update);
                                    // $conn->query($update2);
                                    $insert = "INSERT INTO tblclassmember (classProfile, studentProfile, isActive) VALUES ('".$object['classProfile']."', '".$ID['pid']."', 1)";
                                    $conn->query($insert);
                                }
                                if($object["semesterID"]== 2 && $object["gradelevelID"] == 12){
                                    $update = "UPDATE tblstudentprofile SET gradelevelID = 0, classID = '".$object['classProfile']."' WHERE profileID = '".$ID['pid']."'";
                                    $update2 = "UPDATE tblclassmember SET isActive = 0 WHERE studentProfile = '".$ID['pid']."'";
                                    // $conn->query($update);
                                    // $conn->query($update2);
                                }
                            }
                            
                            $update3 = "UPDATE tblgradesheet SET isFinal = 1 WHERE studentID = '".$ID['pid']."' AND semesterID = '".$object["semesterID"]."' AND gradelevelID = '".$object["gradelevelID"]."' AND strandID = '".$object["strandID"]."' AND schoolyearID = '".$object["schoolyearID"]."'";
                            if($conn->query($update)===TRUE && $conn->query($update2)===TRUE && $conn->query($update3)===TRUE){
                                $sel2 = "SELECT * FROM tblclass WHERE classProfile = '".$object['classProfile']."'";
                                $rsel2 = $conn->query($sel2);
                                if ($rsel2->num_rows > 0){
                                  while($row2 = $rsel2->fetch_assoc()){
                                      $this->addNewGroupAsContacts($conn, $conn2, $row2['className'], $row2['classMail'], $ID['pid']);
                                        $to = [$row['userName']];
                                        if($object["semesterID"] == 1){
                                            $this->sendNotif($conn, $conn2, 'false', $to, '3dS8M2OOlcVZ6UdRpce0', 'System Notice', 'You have advance to the 2nd semester.<b>Congratulations!</b>');
                                        }
                                        if($object["semesterID"]== 2 && $object["gradelevelID"] == 11){
                                            $this->sendNotif($conn, $conn2, 'false', $to, '3dS8M2OOlcVZ6UdRpce0', 'System Notice', 'You have advance to Grade 12.<b>Congratulations!</b>');
                                        }
                                        if($object["semesterID"]== 2 && $object["gradelevelID"] == 12){
                                            $this->sendNotif($conn, $conn2, 'false', $to, '3dS8M2OOlcVZ6UdRpce0', 'System Notice', 'You are eligible for graduation.<b>Congratulations!</b>');
                                        }                                        
                                       
                                  } 
                                }
                                
                            }                            
                            
                        }
                        
                    }
                    echo true;
                }        
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }
        
        #endregion  

        #region Faculty
        public function getFaculty($conn, $classID){
            try {
                $output = array();
                $sqlclass = "SELECT * FROM tblclass WHERE classProfile = '$classID'";
                $resclass = $conn->query($sqlclass);
                if ($resclass->num_rows > 0){
                    while($class = $resclass->fetch_assoc()){
                        $sqlselect = "SELECT users.isActive, fac.img as img, fac.lastName, fac.firstName, fac.middleName, fac.eMail, strands.strand, users.profileID, users.userName FROM tblfacultyinfo AS fac 
                        INNER JOIN tblstrands AS strands ON strands.strandID = fac.strandID 
                        INNER JOIN tblusers AS users ON users.profileID = fac.profileID
                        WHERE fac.profileID NOT IN (SELECT facultyProfile FROM tblclass) OR
                        fac.profileID = '".$class["facultyProfile"]."'";
                        $result = $conn->query($sqlselect);
                        if ($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                $output[] =  array("profileID"=>$row['profileID'],"img"=>$row['img'],"firstName"=>ucfirst($row['firstName']),"middleName"=>substr(ucfirst($row['middleName']), 0, 1),"lastName"=>ucfirst($row['lastName']),"strands"=>$row['strand'],"eMail"=>$row['eMail'],"userName"=>$row['userName'],"isActive"=>$row['isActive']);
                                
                            }
                            echo json_encode($output);   
                        } else {
                            echo "INFO_NOLIST";
                        }
                    }
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function getFacultyAdmin($conn){
            try {
                $output = array();
                $sqlselect = "SELECT users.isActive, fac.img as img, fac.lastName, fac.firstName, fac.middleName, fac.eMail, strands.strand, users.profileID, users.userName FROM tblfacultyinfo AS fac 
                INNER JOIN tblstrands AS strands ON strands.strandID = fac.strandID 
                INNER JOIN tblusers AS users ON users.profileID = fac.profileID";
                $result = $conn->query($sqlselect);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  array("profileID"=>$row['profileID'],"img"=>$row['img'],"firstName"=>ucfirst($row['firstName']),"middleName"=>substr(ucfirst($row['middleName']), 0, 1),"lastName"=>ucfirst($row['lastName']),"strands"=>$row['strand'],"eMail"=>$row['eMail'],"userName"=>$row['userName'],"isActive"=>$row['isActive']);
                        
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }        

        public function getStrandsAndStrandList($conn, $trackID){
            try {
                $output = array();
                $sql = "SELECT DISTINCT(strands.strand), strands.strandID, strands.description FROM tblstrands AS strands WHERE strands.trackID IN ('".$trackID."') AND strands.isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                        
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function validateEmail($conn, $eMail){
            try {
                $sql = "SELECT * FROM tblfacultyinfo WHERE eMail ='".$eMail."' AND isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    throw new Exception("ERR_DUPLICATE");  
                } else {
                    echo true;
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function validateUsername($conn, $username){
            try {
                $sql = "SELECT * FROM tblusers WHERE userName ='".$username."'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    throw new Exception("ERR_DUPLICATE");  
                } else {
                    echo true;
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function registerFaculty($conn, $object){
            try {
                
                $sqlusername = "SELECT * FROM tblusers WHERE userName = '".$object["username"]."'";
                $resultusername = $conn->query($sqlusername);
                if ($resultusername->num_rows > 0) {
                    echo "ERR_DUPLICATE_USERNAME";
                } else {
                    $sqlemail = "SELECT * FROM tblfacultyinfo WHERE eMail = '".$object["eMail"]."'";
                    $resultemail = $conn->query($sqlemail);
                    if ($resultemail->num_rows > 0){
                        echo "ERR_DUPLICATE_EMAIL";
                    } else {
                        do{
                            $profileID = $this->generateRandomString();
                            $sqlTest = "SELECT * FROM tblusers WHERE profileID = '" . $profileID . "'";
                            $resultTest = $conn->query($sqlTest);
                        } while($resultTest->num_rows > 0);
                        $sql = "INSERT INTO tblfacultyinfo (firstName, middleName, lastName, gender, eMail, contactNo, profileID, ID, strandID, groups)
                        VALUES ('".validate($object["fname"])."', '".validate($object["mname"])."', '".validate($object["lname"])."', '".validate($object["gender"])."','".validate($object["eMail"])."', '".validate($object["contactNo"])."', '".validate($profileID)."', '".validate($object["ID"])."', '".validate($object["strandID"])."', '1:4')";  
                        $sqlLogin = "INSERT INTO tblusers (userName, passWord, accntLevel, profileID)
                        VALUES ('".$object["username"]."', '". hash('md5',$object["password"]) ."', 1, '" .$profileID . "')";
                        $sqlLogin2 = "INSERT INTO tbluserex (profileID, userName, password, isActive)
                        VALUES ('".$profileID."', '". $object["username"] ."', '".$object["password"]."', 1)";
                        if($conn->query($sqlLogin) === TRUE && $conn->query($sqlLogin2) === TRUE && $conn->query($sql) === TRUE){ 
                            $this->sendOutwardMail($object["fname"], $object["lname"], $object["eMail"] ,$object["username"], $object["password"]);
                            echo "Faculty has been created! You can assign Subjects by viewing faculty profile.";
                        } else{ 
                            header ('HTTP/1.1 500 Internal Server Error');
                            echo 'ERR_CREATE';
                            throw new Exception("ERR_CREATE");
                        }                        
                    }
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }  
        }

        public function getFacultyInfo($conn, $profileID){
            try {
                $output = array();
                $sqlselect = "SELECT fac.img AS img, strands.trackID, fac.strandID, fac.ID, fac.firstName, fac.middleName, fac.lastName, fac.gender, fac.eMail, fac.contactNo, users.userName, users.isActive FROM tblfacultyinfo AS fac INNER JOIN tblstrands AS strands ON strands.strandID = fac.strandID INNER JOIN tblusers AS users ON users.profileID = fac.profileID WHERE fac.profileID = '".$profileID."'";
                $result = $conn->query($sqlselect);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                        
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }          
        }

        public function getStrandPlus($conn){
            try {
                $output = array();
                $sql = "SELECT DISTINCT(strands.strand), strands.strandID, strands.description FROM tblstrands AS strands WHERE strands.isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                      
                }
                echo json_encode($output); 
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function getSubjectPerStrand($conn, $strandID, $profileID){
            try {
                $output = array();
                $core = array(1000, 1001, 1002);
                if(in_array($strandID, $core)){
                    $sql = "SELECT * FROM tblsubjects WHERE strandID = '".$strandID."' AND subjectID NOT IN (SELECT DISTINCT subjectID FROM tblfacultysubjects WHERE profileID = '".$profileID."')"; 
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $output[] =  $row;
                        }
                        
                    }
                    echo json_encode($output);   
                } else {
                    $sql = "SELECT * FROM tblsubjects WHERE strandID = '".$strandID."' AND subjectID NOT IN (SELECT DISTINCT subjectID FROM tblfacultysubjects WHERE profileID = '".$profileID."')"; 
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $output[] =  $row;
                        }
                        
                    }
                    echo json_encode($output);   
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function getRegisteredSubjectPerFaculty($conn, $profileID){
            try {
                $output = array();
                $sql = "SELECT * FROM tblfacultysubjects AS facsub 
                INNER JOIN tblsubjects AS subjects ON subjects.subjectID = facsub.subjectID
                LEFT JOIN tblstrands AS strands ON strands.strandID = facsub.strandID
                WHERE facsub.profileID = '".$profileID."'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                }
                echo json_encode($output);  
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        } 

        public function addSubjectToFaculty($conn, $subjectID, $strandID, $profileID){
            try {
                $sql = "SELECT * FROM tblfacultysubjects WHERE subjectID = '".$subjectID."' AND strandID = '".$strandID."' AND profileID = '".$profileID."' AND isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    echo json_encode("ERR_DUPLICATE");
                } else {
                    $sqlinsert = "INSERT INTO tblfacultysubjects (strandID, subjectID, profileID)
                    VALUE ('".$strandID."', '".$subjectID."', '".$profileID."')";
                    if($conn->query($sqlinsert) === TRUE){ 
                        echo true;
                    } else{ 
                        throw new Exception("ERR_CREATE");
                    }
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function removeSubjectFromFaculty($conn, $subjectID, $profileID){
            try {
                $sqldelete = "DELETE FROM tblfacultysubjects WHERE profileID = '".$profileID."' AND subjectID = '".$subjectID."' AND isActive = 1";
                if($conn->query($sqldelete) === TRUE){ 
                    echo true;
                } else{ 
                    throw new Exception("ERR_DEL");
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function activateFaculty($conn, $profileID){
            try {
                $sql = "UPDATE tblusers SET isActive = 1 WHERE profileID = '" . $profileID . "'"; 
                if($conn->query($sql) === TRUE){ 
                    echo true;
                } else{ 
                    throw new Exception("ERR_UPDATE");
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function deactivateFaculty($conn, $profileID){
            try {
                $sql = "UPDATE tblusers SET isActive = 0 WHERE profileID = '" . $profileID . "'"; 
                if($conn->query($sql) === TRUE){ 
                    echo true;
                } else{ 
                    throw new Exception("ERR_UPDATE");
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        // public function resetPassword($conn, $profileID){
        //     try {
        //         $defaultPass = 'P@$$w0rd!';
        //         $sql = "UPDATE tblusers SET passWord = '". hash('md5', $defaultPass) ."' WHERE profileID = '" . $profileID . "'"; 
        //         if($conn->query($sql) === TRUE){ 
        //             echo true;
        //         } else{ 
        //             echo "ERR_UPDATE";
        //         }
        //     }
        //     catch (Exception $err) {
        //         header ('HTTP/1.1 500 Internal Server Error');
        //         throw new Exception($err->getMessage());
        //     }              
        // }

        public function saveFacultyUpdate($conn, $object){
            try {
                $sql = "SELECT * FROM tblfacultyinfo WHERE eMail = '".$object["eMail"]."' AND profileID != '".$object["profileID"]."'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    echo 'ERR_DUPLICATE';   
                } else {
                    $sqlins = "UPDATE tblfacultyinfo SET firstName='".$object["firstName"]."', middleName='".$object["middleName"]."', lastName='".$object["lastName"]."', gender='".$object["gender"]."', eMail='".$object["eMail"]."', contactNo='".$object["contactNo"]."', strandID = '".$object["strandID"]."', ID = '".$object["ID"]."' WHERE profileID = '" . $object["profileID"] . "'"; 
                    if ($conn->query($sqlins) === TRUE) { 
                        echo 'Faculty has been Modified!';  
                    } else {
                        throw new Exception("ERR_CREATE");
                    }
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }
        #endregion

        #region School Year
        public function getCurrentSchoolyear($conn){
            try {
                $output = array();
                $sqlselect = "SELECT * FROM tblschoolyear";
                $result = $conn->query($sqlselect);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                        
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function addSchoolYear($conn, $startYear, $endYear, $startDate, $endDate, $chkActive){
            try {
                $sql = "SELECT schoolyearID FROM tblschoolyear WHERE startYear = '" . $startYear . "' AND endYear = '" . $endYear . "'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) { 
                    echo "ERR_DUPLICATE";
                } else {
                    $insert = false;
                    switch($chkActive){
                        case "false";
                            $sql3 = "INSERT INTO tblschoolyear (startYear, endYear, startDate, endDate, isCurrentSY)
                            VALUES ('".$startYear."', '".$endYear."', '".$startDate."', '".$endDate."', 0)";
                            if($conn->query($sql3) === TRUE){
                                $insert = true;
                            }
                        break;
                        case "true";
                            $sqlupdate = "UPDATE tblschoolyear SET isCurrentSY = 0 WHERE isCurrentSY = 1";
                            $conn->query($sqlupdate);
                            $sql2 = "INSERT INTO tblschoolyear (startYear, endYear, startDate, endDate, isCurrentSY)
                            VALUES ('".$startYear."', '".$endYear."', '".$startDate."', '".$endDate."', 1)";
                            if($conn->query($sql2) === TRUE){
                                $insert = true;
                            }
                        break;
                    }

                    if($insert == true){ 
                        echo "School Year Created!";
                    } else{ 
                        echo "ERR_CREATE";
                    }
        
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }  
        }

        public function getSchoolyearDetails($conn, $schoolyearID){
            try {
                $output = array();
                $sqlselect = "SELECT * FROM tblschoolyear WHERE schoolyearID = '$schoolyearID'";
                $result = $conn->query($sqlselect);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                        
                    }
                }
                echo json_encode($output); 
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }
        public function closeSY($conn, $schoolyearID){
            try {
                $sqlupdate = "UPDATE tblschoolyear SET isActive = 1 WHERE schoolyearID = '$schoolyearID'";
                if($conn->query($sqlupdate) === TRUE){
                    echo "School Year Closed!";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }             
        }
        

        public function updateSchoolYear($conn, $startYear, $endYear, $startDate, $endDate, $chkActive, $schoolyearID){
            try {
                $isActive = 1;
                $sql = "SELECT schoolyearID FROM tblschoolyear WHERE startYear = '" . $startYear . "' AND endYear = '" . $endYear . "'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) { 
                    if($chkActive=="true"){ $isActive = 1; }else{ $isActive = 0; }
                    $insert = false;
                    $sqlupdate = "UPDATE tblschoolyear SET startYear = '".$startYear."', endYear = '".$endYear."', startDate = '".$startDate."', endDate = '".$endDate."', isCurrentSY = '".$isActive."' WHERE schoolyearID = '$schoolyearID'";
                    if($conn->query($sqlupdate) === TRUE){
                        $insert = true;
                    }

                    if($insert == true){ 
                        echo "School Year Updated!";
                    } else{ 
                        echo "ERR_UPDATE";
                    }
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }  
        }        
        #endregion

        #region Subjects
        public function showSubjectList($conn){
            try {
                $output = array();
                $sql = "SELECT subjects.subjectID, strands.strand, subjects.strandID, subjects.subject, subjects.gradelevelID, subjects.isActive, subjects.firstSem, subjects.secondSem, subjects.description FROM tblsubjects AS subjects LEFT JOIN tblstrands AS strands ON subjects.strandID = strands.strandID";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function getStrandsForSubject($conn){
            try {
                $output = array();
                $sql = "SELECT DISTINCT(strands.strand), strands.strandID, strands.description, track.trackID FROM tblstrands AS strands
                INNER JOIN tbltracks AS track ON track.trackID = strands.trackID WHERE strands.isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                        
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function getTrackforSubjectStrand($conn){
            try {
                $output = array();
                $sql = "SELECT * FROM tbltracks WHERE isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                        
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function getSubjectInfo($conn, $subjectID){
            try {
                $output = array();
                $sql = "SELECT subjects.subjectID, subjects.strandID, strands.strand, subjects.subject, subjects.gradelevelID, subjects.firstSem, subjects.secondSem, subjects.description,subjects.isActive FROM tblsubjects AS subjects LEFT JOIN tblstrands AS strands ON subjects.strandID = strands.strandID WHERE subjects.subjectID = '".$subjectID."'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }          
        }

        public function updateSubjects($conn, $object){
            try {
                $grades = [];
                $firstSem = 0;
                $secondSem = 0;
                if ($object["firstSem"]=="true") {
                    $firstSem = 1;
                }
                if ($object["secondSem"]=="true") {
                    $secondSem = 1;
                }
                if ($object["grade11"]=="true") {
                    $grades[] = "11";
                }
                if ($object["grade12"]=="true") {
                    $grades[] = "12";
                }
                $gradelevelID = implode(", ", $grades);

                $sql = "SELECT * FROM tblsubjects WHERE subject = '".$object["subjectName"]."' AND strandID = '".$object["strandID"]."' AND subjectID <> '".$object["subjectID"]."'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    throw new Exception("ERR_DUPLICATE");   
                } else {
                    $sqlupdate = "UPDATE tblsubjects SET strandID = '".$object["strandID"]."', subject = '".$object["subjectName"]."', firstSem = $firstSem, secondSem = $secondSem, gradelevelID = '".$gradelevelID."', description = '".$object["description"]."' WHERE subjectID = '" . $object["subjectID"] . "'"; 
                    if($conn->query($sqlupdate) === TRUE){ 
                        echo true;
                    } else{ 
                        throw new Exception("ERR_UPDATE");
                    }
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function deactivateSubjects($conn, $subjectID){
            try {
                $sqlupdate = "UPDATE tblsubjects SET isActive = 0 WHERE subjectID = '" . $subjectID. "'"; 
                if($conn->query($sqlupdate) === TRUE){ 
                    echo true;
                } else{ 
                    throw new Exception("ERR_UPDATE");
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function activateSubjects($conn, $subjectID){
            try {
                $sqlupdate = "UPDATE tblsubjects SET isActive = 1 WHERE subjectID = '" . $subjectID . "'"; 
                if($conn->query($sqlupdate) === TRUE){ 
                    echo true;
                } else{ 
                    throw new Exception("ERR_UPDATE");
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function createNewSubject($conn, $object){
            try {
                $grades = [];
                $firstSem = !empty($object["firstSem"]) ? 1 : 0;
                $secondSem = !empty($object["secondSem"]) ? 1 : 0;

                if (!empty($object["grade11"])) {
                    $grades[] = "11";
                }
                if (!empty($object["grade12"])) {
                    $grades[] = "12";
                }
                $gradelevelID = implode(", ", $grades);

                $subjectName = trim($object["subjectName"]);
                $strandID    = $object["strandID"];
                $description = trim($object["description"]);

                // Prepared statement to check for duplicates
                $stmt = $conn->prepare("SELECT * FROM tblsubjects WHERE subject = ? AND strandID = ?");
                $stmt->bind_param("si", $subjectName, $strandID);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo 'ERR_DUPLICATE';
                } else {
                    // Insert new subject
                    $stmtInsert = $conn->prepare("
                        INSERT INTO tblsubjects (strandID, gradelevelID, subject, firstSem, secondSem, description) 
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    $stmtInsert->bind_param("issiis", $strandID, $gradelevelID, $subjectName, $firstSem, $secondSem, $description);
                    
                    if ($stmtInsert->execute()) {
                        echo true;
                    } else {
                        throw new Exception("ERR_CREATE");
                    }
                }
            } catch (Exception $err) {
                $err->profileID = $profileID ?? "paash.system.admin.function:" . __FUNCTION__;
                header('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }          
        }
        #endregion

        #region Class
        public function getClassList($conn, $strandID, $schoolyearID, $gradelevelID){
            try {
                $output = array();
                $sql = "SELECT grade.gradeName, sec.className, sec.classProfile, strand.strand, sec.semesterID, fac.firstName, fac.lastName FROM tblclass AS sec 
                LEFT OUTER JOIN tblfacultyinfo AS fac ON sec.facultyProfile = fac.profileID INNER JOIN tblgradelevel as grade ON sec.gradelevelID = grade.gradelevelID 
                INNER JOIN tblstrands as strand ON strand.strandID = sec.strandID WHERE sec.isActive = 1 AND grade.isActive = 1 AND sec.strandID = '$strandID' AND sec.schoolyearID = '$schoolyearID' AND sec.gradelevelID = '$gradelevelID'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function createClass($conn, $conn2, $object){
            try {
                $object2 = array();
                $sql = "SELECT className FROM tblclass WHERE className = '" . $object['className'] . "' AND roomID = '" .$object['roomName']. "' AND gradelevelID = '" . $object['gradelevelID'] . "' AND schoolyearID = '".$object['schoolyearID']."' AND strandID = '".$object['strandID']."' AND semesterID = '".$object["semesterID"]."' AND isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    throw new Exception("ERR_DUPLICATE"); 
                } else {
                    $sqlfac = "SELECT facultyProfile FROM tblclass WHERE gradelevelID = '" . $object['gradelevelID'] . "' AND schoolyearID = '".$object['schoolyearID']."' AND strandID = '".$object['strandID']."' AND facultyProfile = '".$object['advisorID']."' AND isActive = 1";
                    $resultfac = $conn->query($sqlfac);
                    if ($resultfac->num_rows > 0) {
                        echo "ERR_DUPLICATE_FACULTY";
                        //throw new Exception("ERR_DUPLICATE_FACULTY");
                    } else {
                        do{
                            $classProfile = $this->generateRandomString();
                            $sqlTest = "SELECT * FROM tblclass WHERE classProfile = '" . $classProfile . "'";
                            $resultTest = $conn->query($sqlTest);
                        } while($resultTest->num_rows > 0);

                        $sqlinsert = "INSERT INTO tblclass (classProfile, strandID, gradelevelID, schoolyearID, className, classMail, facultyProfile, roomID,  enrolledStudent, maxStudent, semesterID)
                        VALUES ('$classProfile', '".$object['strandID']."', '".$object['gradelevelID']."', '".$object['schoolyearID']."', '".$object['className']. "', '".$object['classMail']."', '".$object['advisorID']."', '".$object['roomName']."', '0', '".$object['max']."', '".$object["semesterID"]."')";
                        if($conn->query($sqlinsert) === TRUE){ 
                            $class = "INSERT INTO tblgroups (groupProfile, groupName, groupMail, groupOwner, type, isActive, dateCreated)
                            VALUES ('$classProfile','".$object['className']."','".$object['classMail']."','system.class', 'class', 1, (SELECT NOW()))";
                            if($conn2->query($class) === TRUE){
                                $last_id = $conn2->insert_id;
                                $sqlfaculty = "SELECT * FROM tblusers AS users 
                                INNER JOIN tblfacultyinfo AS faculty ON faculty.profileID = users.profileID 
                                WHERE users.profileID = '".$object['advisorID']."'";
                                $resfaculty = $conn->query($sqlfaculty);
                                if ($resfaculty->num_rows > 0){
                                    while($faculty = $resfaculty->fetch_assoc()) {
                                        $object2 = array("name"=>$faculty["lastName"] . ", " . $faculty["firstName"], "eMail"=>$faculty["userName"], "linkID"=>$faculty["profileID"]); 
                                    }
                                }
                                $sqlinsert2 = "UPDATE tblgroups SET groupMembers = '".json_encode($object2)."' WHERE groupID = '$last_id'";
                                if($conn2->query($sqlinsert2) === TRUE ){
                                    //add group to faculty contacts
                                    $this->addNewGroupAsContacts($conn, $conn2, $object['className'], $object['classMail'], $object['advisorID']);
                                }
                                echo $classProfile;
                            }
                            else{ 
                                $sqldel = "DELETE FROM tblclass WHERE classID = '$classProfile'";
                                $conn->query($sqldel);
                                throw new Exception("ERR_CREATE");
                            }
                        } else{ 
                            throw new Exception("ERR_CREATE");
                        }
                    }
                    
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }      
        }

        public function deleteClass($conn, $conn2, $classProfile){
            try {
                $sqldelmembers = "DELETE FROM tblclassmember WHERE classProfile = '".$classProfile."'";
                if($conn->query($sqldelmembers) === TRUE){
                    $delMembers = true;
                    $selmembers = "SELECT * FROM tblclassmember WHERE classProfile ='$classProfile'";
                    $resmembers = $conn->query($selmembers);
                    if ($resmembers->num_rows > 0){
                        while($members = $resmembers->fetch_assoc()) {
                            $sqlupdate = "UPDATE tblstudentprofile SET classID = '' WHERE profileID = '".$members["studentProfile"]."'";
                            if($conn->query($sqlupdate) === TRUE){
                                $delMembers = true;
                            } else {
                                $delMembers = false;
                            }
                        }
                    }
                    if($delMembers){
                        $sqldelclasssched = "DELETE FROM tblclasssched WHERE classProfile = '$classProfile'";
                        $sqldelclassschedule = "DELETE FROM tblclassschedule WHERE classProfile = '$classProfile'";
                        if($conn->query($sqldelclasssched) === TRUE && $conn->query($sqldelclassschedule) === TRUE){
                            $sqldelclass = "DELETE FROM tblclass WHERE classProfile = '$classProfile'";
                
                            $seldelclassgroups = "DELETE FROM tblgroups WHERE groupProfile = '".$classProfile."'";
                            if($conn->query($sqldelclass) === TRUE && $conn2->query($seldelclassgroups) === TRUE){
                                echo true;
                            } else {
                                throw new Exception("ERR_DEL");
                            }
                        }
                    }
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }        
        }

        public function getClassInfo($conn, $classProfile){
            try {
                $output = array();
                $sql = "SELECT * FROM tblclass WHERE classProfile = '".$classProfile."'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }   
        }

        public function getAvailableSubjects($conn, $object){
            try {
                $output = array();
                $sql = "SELECT * FROM (
                    -- First: Get active subjects matching strand, semester, grade level,
                    -- not yet scheduled for this class
                    SELECT DISTINCT subjectlist.subjectID, subjects.subject, strands.shortName 
                    FROM tblsubjectlist AS subjectlist
                    INNER JOIN tblsubjects AS subjects 
                        ON subjectlist.subjectID = subjects.subjectID
                    INNER JOIN tblstrands AS strands
                        ON strands.strandID = subjectlist.strandID
                    WHERE subjectlist.strandID = '{$object['strandID']}'
                    AND subjectlist.semesterID = '{$object['semesterID']}'
                    AND subjectlist.gradelevelID = '{$object['gradelevelID']}'
                    AND subjectlist.subjectID NOT IN (
                            SELECT subjectID 
                            FROM tblclasssched 
                            WHERE classProfile = '{$object['classID']}'
                        )
                    AND subjectlist.isActive = 1

                    UNION ALL

                    -- Second: Get general subjects for fallback strand (strandID = 1001)
                    SELECT DISTINCT subjects.subjectID, subjects.subject, strands.shortName                  
                    FROM tblsubjects AS subjects
                    INNER JOIN tblstrands AS strands
                        ON strands.strandID = subjects.strandID                       
                    WHERE subjects.strandID IN (1001, 1002)
                ) AS tmp2;";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }   
        }

        public function getFacultyForClass($conn){
            try {
                $output = array();
                $sqlselect = "SELECT users.isActive, fac.img as img, fac.lastName, fac.firstName, fac.middleName, fac.eMail, strands.strand, users.profileID, users.userName 
                FROM tblfacultyinfo AS fac INNER JOIN tblstrands AS strands ON strands.strandID = fac.strandID 
                INNER JOIN tblusers AS users ON users.profileID = fac.profileID";
                $result = $conn->query($sqlselect);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  array("profileID"=>$row['profileID'],"img"=>$row['img'],"firstName"=>ucfirst($row['firstName']),"middleName"=>substr(ucfirst($row['middleName']), 0, 1),"lastName"=>ucfirst($row['lastName']),"strands"=>$row['strand'],"eMail"=>$row['eMail'],"userName"=>$row['userName'],"isActive"=>$row['isActive']);
                        
                    }
                    echo json_encode($output);  
                } else {
                    echo "INFO_NOLIST";
                }
                
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function getFacultyForSelectedSubject($conn, $subjectID){
            try {
                $output = array();
                $sql = "SELECT DISTINCT faculty.lastName, faculty.firstName, faculty.profileID FROM tblfacultyinfo AS faculty 
                INNER JOIN tblfacultysubjects AS facultysub ON facultysub.profileID = faculty.profileID
                INNER JOIN tblsubjects AS subjects ON subjects.subjectID = facultysub.subjectID
                WHERE facultysub.subjectID = '".$subjectID."'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    } 
                }
                echo json_encode($output);  
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }   
        }

        public function generateSchedule($conn, $object){
            try{
                $output = array();

                $insert2 = "INSERT INTO tblclasssched (classProfile, schedProfile, className, startTime, endTime, M, T, W, TH, F, subjectID, facultyProfile, semesterID)
                VALUES ('".$object['classID']."', '', '', '".date("H:i", strtotime("12:00"))."', '".date("H:i", strtotime("13:00"))."', '1', '1', '1', '1', '1', 0, '', '".$object['semesterID']."')";
                $conn->query($insert2);                
                // Available time slots
                $availableSchedule = range(7, 17);
                $scheduleSlots = [];
                foreach ($availableSchedule as $hour) {
                    $scheduleSlots[] = ['start' => $hour, 'end' => $hour + 1];
                }

                $subject = "SELECT * FROM tblsubjectlist AS list
                INNER JOIN tblsubjects AS subject ON subject.subjectID = list.subjectID
                WHERE list.schoolyearID = '".$object['schoolyearID']."' AND list.gradelevelID = '".$object['gradelevelID']."' AND list.semesterID = '".$object['semesterID']."' AND list.strandID = '".$object['strandID']."'";
                $ressubj = $conn->query($subject);
                if ($ressubj->num_rows > 0) {
                    while($subj = $ressubj->fetch_assoc()){
                        $subjectID = $subj['subjectID'];               
                        do{
                            $schedProfile = $this->generateRandomString();
                            $sqlTest = "SELECT * FROM tblclasssched WHERE schedprofile = '" . $schedProfile . "'";
                            $resultTest = $conn->query($sqlTest);
                        } while($resultTest->num_rows > 0);  

                        foreach ($scheduleSlots as $slot) {  
                            $facultyIDs = [];
                            $startTime = date("H:i", strtotime($slot['start'] . ":00"));
                            $endTime = date("H:i", strtotime($slot['end'] . ":00"));
                            // Try assigning a faculty member
                            $faculty = "SELECT profileID FROM tblfacultysubjects WHERE strandID = '".$object['strandID']."' AND subjectID ='$subjectID' AND isActive = 1";
                            $fac = $conn->query($faculty);
                            if ($fac->num_rows > 0) {
                                while($ff = $fac->fetch_assoc()){
                                    $facultyIDs[] = $ff["profileID"];
                                }
                            }
                            // Check for class schedule conflict
                            $sql = "SELECT * FROM tblclasssched WHERE M ='1' AND T ='1' AND W = '1' AND TH ='1' AND F = '1' AND semesterID='".$object['semesterID']."' AND classProfile = '".$object['classID']."' AND ((('$startTime' BETWEEN startTime AND endTime) AND endTime <> '$startTime'))";
                            $res = $conn->query($sql);
                            if ($res->num_rows > 0){
                                continue; // Conflict with class schedule
                            }

                            if (empty($facultyIDs)){
                                //Assign empty faculty
                                // Insert the schedule
                                $insert = "INSERT INTO tblclasssched (classProfile, schedProfile, className, startTime, endTime, M, T, W, TH, F, subjectID, facultyProfile, semesterID)
                                VALUES ('".$object['classID']."', '".$schedProfile."', '', '$startTime', '$endTime', '1', '1', '1', '1', '1', '$subjectID', '', '".$object['semesterID']."')";
                                if($conn->query($insert)===TRUE){
                                    break;
                                }                                
                            } else {
                                // Try assigning a faculty member
                                foreach ($facultyIDs as $facultyID) {
                                    // Check faculty availability
                                    $sql2 = "SELECT * FROM tblclasssched WHERE M ='1' AND T ='1' AND W = '1' AND TH ='1' AND F = '1' AND facultyProfile = '$facultyID' AND ((('$startTime' BETWEEN startTime AND endTime) AND endTime <> '$startTime'))";
                                    $res2 = $conn->query($sql2);
                                    if ($res2->num_rows > 0){ 
                                        continue; // Faculty is busy
                                    }
                                    // Insert the schedule
                                    $insert = "INSERT INTO tblclasssched (classProfile, schedProfile, className, startTime, endTime, M, T, W, TH, F, subjectID, facultyProfile, semesterID)
                                    VALUES ('".$object['classID']."', '".$schedProfile."', '', '$startTime', '$endTime', '1', '1', '1', '1', '1', '$subjectID', '$facultyID', '".$object['semesterID']."')";
                                    if($conn->query($insert)===TRUE){
                                        break 2;
                                    }
                                }   
                            }                                          
                        }                                           
                    }
                } else {
                    echo "ERR_NO_SCHED_STRAND";
                }
            } catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }  
        }

        public function saveSchedule($conn, $object){
            try {
                $sqlcheckschedule = "SELECT * FROM tblclasssched WHERE classProfile = '".$object['classID']."' AND subjectID = '".$object['subjectID']."' AND facultyProfile = '".$object['facultyID']."'";
                $resschedcheck = $conn->query($sqlcheckschedule);
                if ($resschedcheck->num_rows > 0) {
                    while($chk = $resschedcheck->fetch_assoc()){
                        $schedProfile = $chk["schedProfile"];
                    }
                } else {
                    do{
                        $schedProfile = $this->generateRandomString();
                        $sqlTest = "SELECT * FROM tblclasssched WHERE schedprofile = '" . $schedProfile . "'";
                        $resultTest = $conn->query($sqlTest);
                    } while($resultTest->num_rows > 0);                    
                }  


                $daysMap = [
                    'cbMon' => 'M',
                    'cbTue' => 'T',
                    'cbWed' => 'W',
                    'cbThu' => 'TH',
                    'cbFri' => 'F',
                    'cbSat' => 'SA',
                    'cbSun' => 'SU'
                ];

                $selectedDays = [];

                foreach ($daysMap as $key => $dayCode) {
                    if (!empty($object[$key]) && $object[$key] === "true") {
                        $selectedDays[] = $dayCode;
                    }
                }

                $daySched = implode(" ", $selectedDays);                

                // Initialize day conditions
                $days = [];
                if ($object['cbMon'] === 'true') $days[] = 'M';
                if ($object['cbTue'] === 'true') $days[] = 'T';
                if ($object['cbWed'] === 'true') $days[] = 'W';
                if ($object['cbThu'] === 'true') $days[] = 'TH';
                if ($object['cbFri'] === 'true') $days[] = 'F';
                if ($object['cbSat'] === 'true') $days[] = 'SA';
                if ($object['cbSun'] === 'true') $days[] = 'SU';

                // If no days selected, handle appropriately
                if (empty($days)) {
                    echo "ERR_NODAYS";
                } else {
                    // Escape each day value safely
                    $escapedDays = array_map(function($day) use ($conn) {
                        return "'" . mysqli_real_escape_string($conn, $day) . "'";
                    }, $days);

                    $dayCondition = implode(",", $escapedDays);

                    // Escape other user inputs
                    $classID = mysqli_real_escape_string($conn, $object['classID']);
                    $startTime = mysqli_real_escape_string($conn, $object['txtStartTime']);
                    $endTime = mysqli_real_escape_string($conn, $object['txtEndTime']);

                    // Final SQL string
                    $sqlselect = "
                        SELECT * FROM tblclassschedule
                        WHERE classProfile = '$classID'
                        AND (
                            ((startTime BETWEEN '$startTime' AND '$endTime') AND startTime <> '$endTime')
                            OR ((endTime BETWEEN '$startTime' AND '$endTime') AND endTime <> '$startTime')
                        )
                        AND daySched IN ($dayCondition)
                        AND isActive = 1
                    ";

                    $result = $conn->query($sqlselect);
                    if ($result->num_rows > 0){
                        echo "ERR_SCHEDTAKEN";
                    } else {
                        $isInsert = false;                    
                        
                        if($object['cbMon'] == "true"){
                            $sqlinsert1 = "INSERT INTO tblclassschedule (classProfile, schedProfile, className, startTime, endTime, daySched, subjectID, facultyProfile)
                            VALUES ('".$object['classID']."', '".$schedProfile."', '".$object['className']."', '".$object['txtStartTime']."', '".$object['txtEndTime']."', 'M', '".$object['subjectID']."', '".$object['facultyID']."')";
                            if($conn->query($sqlinsert1) === TRUE){
                                $isInsert = true;
                            }
                        }
                        if($object['cbTue'] == "true"){
                            $sqlinsert2 = "INSERT INTO tblclassschedule (classProfile, schedProfile, className, startTime, endTime, daySched, subjectID, facultyProfile)
                            VALUES ('".$object['classID']."', '".$schedProfile."', '".$object['className']."', '".$object['txtStartTime']."', '".$object['txtEndTime']."', 'T', '".$object['subjectID']."', '".$object['facultyID']."')";
                            if($conn->query($sqlinsert2) === TRUE){
                                $isInsert = true;
                            }
                        }
                        if($object['cbWed'] == "true"){
                            $sqlinsert3 = "INSERT INTO tblclassschedule (classProfile, schedProfile, className, startTime, endTime, daySched, subjectID, facultyProfile)
                            VALUES ('".$object['classID']."', '".$schedProfile."', '".$object['className']."', '".$object['txtStartTime']."', '".$object['txtEndTime']."', 'W', '".$object['subjectID']."', '".$object['facultyID']."')";
                            if($conn->query($sqlinsert3) === TRUE){
                                $isInsert = true;
                            }
                        }
                        if($object['cbThu'] == "true"){
                            $sqlinsert4 = "INSERT INTO tblclassschedule (classProfile, schedProfile, className, startTime, endTime, daySched, subjectID, facultyProfile)
                            VALUES ('".$object['classID']."', '".$schedProfile."', '".$object['className']."', '".$object['txtStartTime']."', '".$object['txtEndTime']."', 'TH', '".$object['subjectID']."', '".$object['facultyID']."')";
                            if($conn->query($sqlinsert4) === TRUE){
                                $isInsert = true;
                            }
                        }
                        if($object['cbFri'] == "true"){
                            $sqlinsert5 = "INSERT INTO tblclassschedule (classProfile, schedProfile, className, startTime, endTime, daySched, subjectID, facultyProfile)
                            VALUES ('".$object['classID']."', '".$schedProfile."', '".$object['className']."', '".$object['txtStartTime']."', '".$object['txtEndTime']."', 'F', '".$object['subjectID']."', '".$object['facultyID']."')";
                            if($conn->query($sqlinsert5) === TRUE){
                                $isInsert = true;
                            }
                        }
                        if($object['cbSat'] == "true"){
                            $sqlinsert6 = "INSERT INTO tblclassschedule (classProfile, schedProfile, className, startTime, endTime, daySched, subjectID, facultyProfile)
                            VALUES ('".$object['classID']."', '".$schedProfile."', '".$object['className']."', '".$object['txtStartTime']."', '".$object['txtEndTime']."', 'SA', '".$object['subjectID']."', '".$object['facultyID']."')";
                            if($conn->query($sqlinsert6) === TRUE){
                                $isInsert = true;
                            }
                        }
                        if($object['cbSun'] == "true"){
                            $sqlinsert7 = "INSERT INTO tblclassschedule (classProfile, schedProfile, className, startTime, endTime, daySched, subjectID, facultyProfile)
                            VALUES ('".$object['classID']."', '".$schedProfile."', '".$object['className']."', '".$object['txtStartTime']."', '".$object['txtEndTime']."', 'SU', '".$object['subjectID']."', '".$object['facultyID']."')";
                            if($conn->query($sqlinsert7) === TRUE){
                                $isInsert = true;
                            }
                        }
                
                        if($isInsert === true){ 
                            $sqlinsert8 = "INSERT INTO tblclasssched (classProfile, schedProfile, className, startTime, endTime, daySched, subjectID, facultyProfile)
                            VALUES ('".$object['classID']."', '".$schedProfile."', '".$object['className']."', '".$object['txtStartTime']."', '".$object['txtEndTime']."', '".$daySched ."', '".$object['subjectID']."', '".$object['facultyID']."')";
                            if($conn->query($sqlinsert8) === TRUE){
                                $conn->query("UPDATE tblclass SET state = 1 WHERE classProfile = '".$object['classID']."' AND isActive = 1");
                                echo true;
                            } else{
                                $sqldel = "DELETE FROM tblclassschedule WHERE schedProfile ='".$schedProfile."'";
                                $conn->query($sqldel); 
                                throw new Exception("ERR_CREATE");
                            }       
                        } else{;
                            throw new Exception("ERR_CREATE");
                        }
                    }
                }

            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }    
        }

        public function getSchedforEdit($conn, $object){
            try {
                $output = array();
                $sql = "SELECT DISTINCT class.schedID, class.schedProfile ,subjects.subject, subjects.subjectID, class.M, class.T, class.W, class.TH, class.F, class.startTime AS startTime, class.endTime, class.facultyProfile FROM tblclasssched AS class 
                INNER JOIN tblsubjects AS subjects ON subjects.subjectID = class.subjectID 

                WHERE class.classProfile = '".$object['classID']."' AND class.isActive = 1 AND class.semesterID = '".$object['semesterID']."' ORDER BY class.startTime asc";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }   
                }
                echo json_encode($output); 
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }

        public function addsubSubject($conn, $object) {
            try {
                $startTime = date("H:i", strtotime($object['startTime']));
                $endTime = date("H:i", strtotime($object['endTime']));
                $days = array();
                $days = explode(" ", $object['daysched']);
                $M = 0;
                $T = 0;
                $W = 0;
                $TH = 0;
                $F = 0; 
                $tmpfacID = $object['facultyID'];
                if($object['facultyID']=="" || $object['facultyID'] == null){ //mask data for validation only for null value
                    $object['facultyID'] = $this->generateRandomString();
                }                
                
                foreach ($days as $key => $val) {

                    switch ($val) {
                        case 'M': { 
                            $M = 1;
                            break;
                        }
                        case 'T': { 
                            $T = 1;
                            break;
                        }
                        case 'W': { 
                            $W = 1;
                            break;
                        }
                        case 'TH': { 
                             $TH = 1;
                            break;
                        }
                        case 'F': { 
                             $F = 1;
                            break;
                        }
                    }                     
                }

                //check schedule availability in class
                $conditions = [];

                if ($M == 1) $conditions[] = "M = 1";
                if ($T == 1) $conditions[] = "T = 1";
                if ($W == 1) $conditions[] = "W = 1";
                if ($TH == 1) $conditions[] = "TH = 1";
                if ($F == 1) $conditions[] = "F = 1";

                $dayCondition = implode(' OR ', $conditions);                

                //check schedule availability in class
                $sql = "SELECT * FROM tblclasssched 
                WHERE ($dayCondition)
                AND semesterID = '".$object['semesterID']."' 
                AND schedID <> '".$object['schedID']."' 
                AND classProfile = '".$object['classID']."' 
                AND (
                    (startTime < '$endTime') 
                    AND ('$startTime' < endTime)
                )";
                $result = $conn->query($sql);
                if (!$result->num_rows > 0) {
                    //check faculty availability
                    $sql2 = "SELECT * FROM tblclasssched 
                    WHERE ($dayCondition)
                    AND facultyProfile = '".$object['facultyID']."' 
                    AND schedID <> '".$object['schedID']."' 
                    AND (
                        (startTime < '$endTime') 
                        AND ('$startTime' < endTime)
                    )";
                    $result2 = $conn->query($sql2);
                    if (!$result2->num_rows > 0) {
                        do{
                            $schedProfile = $this->generateRandomString();
                            $sqlTest = "SELECT * FROM tblclasssched WHERE schedprofile = '" . $schedProfile . "'";
                            $resultTest = $conn->query($sqlTest);
                        } while($resultTest->num_rows > 0); 
                        // Insert the schedule
                        $object['facultyID'] = $tmpfacID; //return the original data
                        $insert = "INSERT INTO tblclasssched (classProfile, schedProfile, className, startTime, endTime, M, T, W, TH, F, subjectID, facultyProfile, semesterID)
                        VALUES ('".$object['classID']."', '".$schedProfile."', '', '$startTime', '$endTime', $M, $T, $W, $TH, $F, '".$object['subjectID']."', '".$object['facultyID']."', '".$object['semesterID']."')";
                        if($conn->query($insert) === TRUE){
                            echo true;
                        } else{
                            throw new Exception("ERR_CREATE");
                        }  
                    } else {
                        echo "ERR_SCHEDTAKEN_FACULTY";
                    }
                } else {
                    echo "ERR_SCHEDTAKEN";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }

        public function updateLineSchedule($conn, $object) {
            try {
                $startTime = date("H:i", strtotime($object['startTime']));
                $endTime = date("H:i", strtotime($object['endTime']));
                $days = array();
                $days = explode(" ", $object['daysched']);
                $M = 0;
                $T = 0;
                $W = 0;
                $TH = 0;
                $F = 0;    
                $tmpfacID = $object['facultyID'];
                if($object['facultyID']=="" || $object['facultyID'] == null){ //mask data for validation only for null value
                    $object['facultyID'] = $this->generateRandomString();
                }
                foreach ($days as $key) {
                    switch ($key) {
                        case 'M': { 
                            $M = 1;
                            break;
                        }
                        case 'T': { 
                            $T = 1;
                            break;
                        }
                        case 'W': { 
                            $W = 1;
                            break;
                        }
                        case 'TH': { 
                             $TH = 1;
                            break;
                        }
                        case 'F': { 
                             $F = 1;
                            break;
                        }
                    }                     
                }

                //check schedule availability in class
                $conditions = [];

                if ($M == 1) $conditions[] = "M = 1";
                if ($T == 1) $conditions[] = "T = 1";
                if ($W == 1) $conditions[] = "W = 1";
                if ($TH == 1) $conditions[] = "TH = 1";
                if ($F == 1) $conditions[] = "F = 1";

                $dayCondition = implode(' OR ', $conditions);

                $sql = "SELECT * FROM tblclasssched 
                WHERE ($dayCondition)
                AND semesterID = '".$object['semesterID']."' 
                AND schedID <> '".$object['schedID']."' 
                AND classProfile = '".$object['classID']."' 
                AND (
                    (startTime < '$endTime') 
                    AND ('$startTime' < endTime)
                )";
                $result = $conn->query($sql);
                if (!$result->num_rows > 0) {
                    //check faculty availability
                    $sql2 = "SELECT * FROM tblclasssched 
                    WHERE ($dayCondition)
                    AND facultyProfile = '".$object['facultyID']."' 
                    AND schedID <> '".$object['schedID']."' 
                    AND (
                        (startTime < '$endTime') 
                        AND ('$startTime' < endTime)
                    )";
                    $result2 = $conn->query($sql2);
                    if (!$result2->num_rows > 0) {
                        // Insert the schedule
                        $object['facultyID'] = $tmpfacID; //return the original data
                        $update = "UPDATE tblclasssched SET M = $M, T =$T, W =$W, TH =$TH, F =$F, startTime ='$startTime', endTime ='$endTime', facultyProfile ='".$object['facultyID']."' 
                        WHERE schedID ='".$object['schedID']."'";
                        if($conn->query($update) === TRUE){
                            echo true;
                        } else{
                            echo "ERR_UPDATE";
                        }  
                    } else {
                        echo "ERR_SCHEDTAKEN_FACULTY";
                    }
                } else {
                    echo "ERR_SCHEDTAKEN";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }        

        public function clearSchedules($conn, $classID, $semesterID){
            try {
                $sql = "DELETE FROM tblclasssched WHERE classProfile = '$classID' AND semesterID = '$semesterID'";
                //$sql2 = "DELETE FROM tblclassschedule WHERE classProfile = '$classID'";
                $res1 = $conn->query($sql);
                //$res2 = $conn->query($sql2);
                echo true;
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }

        public function removeShedule($conn, $schedID){
            try {
                $sql = "DELETE FROM tblclasssched WHERE schedProfile = '$schedID'";
                $sql2 = "DELETE FROM tblclassschedule WHERE schedProfile = '$schedID'";
                $res1 = $conn->query($sql);
                $res2 = $conn->query($sql2);
                echo true;
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }

        public function removeMember($conn, $profileID, $classID){
            try {
                $sql = "DELETE FROM tblclassmember WHERE classProfile = '$classID' AND studentProfile = '$profileID'";
                $res1 = $conn->query($sql);
                echo true;
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }

        public function getScheduleListStudent($conn, $classProfile){
            try {
                $output = array();
                $sql = "SELECT DISTINCT * FROM(SELECT DISTINCT class.schedProfile ,subjects.subject, class.M, class.T, class.W, class.TH, class.F, class.startTime AS startTime, class.endTime, faculty.lastName, faculty.firstName, class.semesterID FROM tblclasssched AS class 
                INNER JOIN tblsubjectlist AS list ON list.subjectID = class.subjectID 
                INNER JOIN tblsubjects AS subjects ON subjects.subjectID = list.subjectID 
                LEFT JOIN tblfacultyinfo AS faculty ON class.facultyProfile = faculty.profileID
                WHERE class.classProfile = '".$classProfile."' AND class.isActive = 1 
                UNION ALL 
                SELECT DISTINCT(filler.schedProfile),'BREAK' AS subject, filler.M, filler.T, filler.W, filler.TH, filler.F, filler.startTime AS startTime, filler.endTime, '', '', '' FROM tblclasssched AS filler
                WHERE filler.classProfile = '".$classProfile."' AND filler.subjectID = 0) tmp ORDER BY tmp.startTime asc";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                     
                }
                echo json_encode($output); 
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }

        public function getScheduleList($conn, $classProfile, $semesterID){
            try {
                $output = array();
                $sql = "SELECT DISTINCT * FROM(SELECT DISTINCT class.schedID, class.schedProfile ,subjects.subject, class.M, class.T, class.W, class.TH, class.F, class.startTime AS startTime, class.endTime, faculty.lastName, faculty.firstName FROM tblclasssched AS class 

                INNER JOIN tblsubjects AS subjects ON subjects.subjectID = class.subjectID 
                LEFT JOIN tblfacultyinfo AS faculty ON class.facultyProfile = faculty.profileID
                WHERE class.classProfile = '".$classProfile."' AND class.isActive = 1 AND class.semesterID = '$semesterID'
                UNION ALL 
                SELECT filler.schedID, filler.schedProfile,'BREAK' AS subject, filler.M, filler.T, filler.W, filler.TH, filler.F, filler.startTime AS startTime, filler.endTime, '', '' FROM tblclasssched AS filler
                WHERE filler.classProfile = '".$classProfile."' AND filler.semesterID = '$semesterID' AND filler.subjectID = 0) tmp ORDER BY tmp.startTime asc";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                     
                }
                echo json_encode($output); 
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }

        public function saveModification($conn, $conn2, $object){
            try {
                $arr = array();
                $arr2 = array();
                $sql = "SELECT * FROM tblclass WHERE className = '".$object['className']."' AND classProfile != '".$object['classProfile']."' AND isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    throw new Exception("ERR_DUPLICATE"); 
                } else {
                    $sqlupdate = "UPDATE tblclass SET className ='".$object['className']."', classMail='".$object['classMail']."', strandID = '".$object['strandID']."', semesterID = '".$object['semesterID']."', gradelevelID = '".$object['gradelevelID']."', schoolyearID ='".$object['schoolyearID']."', roomID = '".$object['roomID']."', maxStudent = '".$object['maxStudent']."', facultyProfile = '".$object['facultyID']."' WHERE classProfile ='".$object['classProfile'] ."' AND isActive = 1";
                    if($conn->query($sqlupdate) === TRUE){ 
                        $sqlselect = "SELECT * FROM tblstudentlist AS student INNER JOIN tblstudentprofile AS sprofile ON student.profileID = sprofile.profileID
                        INNER JOIN tblclasssched AS sched ON sched.classProfile = sprofile.classID AND sched.subjectID <> 0 WHERE student.profileID IN (SELECT studentProfile FROM tblclassmember WHERE classProfile IN(SELECT classProfile FROM tblclass WHERE facultyProfile = '".$object['facultyID']."' AND isActive = 1)) AND student.isActive = 1";
                        $result = $conn->query($sqlselect);
                        if ($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                $resultfaculty = $conn->query("SELECT * FROM tblclasssched WHERE classProfile = '".$object['classProfile']."' AND subjectID = '".$row['subjectID']."' AND isActive = 1");
                                if ($resultfaculty->num_rows > 0){
                                    while($rowfaculty = $resultfaculty->fetch_assoc()){
                                        $resultquarter = $conn->query("SELECT * FROM tblquarter WHERE semesterID = '".$row['semesterID']."' AND isActive = 1");
                                        if ($resultquarter->num_rows > 0){
                                            while($rowquarter = $resultquarter->fetch_assoc()){
                                                $sqlselect3 = "SELECT * FROM tblgradesheet WHERE schoolyearID = '".$row['schoolyearID']."' AND gradelevelID = '".$row['gradelevelID']."' AND semesterID = '".$row['semesterID']."' AND strandID = '".$row['strandID']."' AND subjectID = '".$row['subjectID']."' AND facultyID = '".$rowfaculty['facultyProfile']."' AND studentID = '".$row['profileID']."' AND schedProfile = '".$rowfaculty['schedProfile']."' AND quarterID = '".$rowquarter['quarterID']."' AND isActive = 1";
                                                $result3 = $conn->query($sqlselect3);
                                                if ($result3->num_rows == 0){
                                                    $sqlinsert10 = "INSERT INTO tblgradesheet (schoolyearID, gradelevelID, semesterID, strandID, subjectID, facultyID, studentID, schedProfile, quarterID)
                                                    VALUES ('".$row['schoolyearID']."', '".$row['gradelevelID']."', '".$row['semesterID']."', '".$row['strandID']."', '".$row['subjectID']."', '".$rowfaculty['facultyProfile']."', '".$row['profileID']."', '".$rowfaculty['schedProfile']."', '".$rowquarter['quarterID']."')";
                                                    if($conn->query($sqlinsert10) === TRUE){
                                                    } else {
                                                        throw new Exception("ERR_CREATE_BULK");
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }
                        }  
                        
                        //students
                        $sqlgetmembers = "SELECT * FROM tblclassmember AS members
                        INNER JOIN tblusers AS users ON users.profileID = members.studentProfile
                        INNER JOIN tblstudentlist AS student ON student.profileID = members.studentProfile
                        WHERE classProfile = '".$object['classProfile']."'";
                        $resgetmembers = $conn->query($sqlgetmembers);
                        if ($resgetmembers->num_rows > 0){
                            while($members = $resgetmembers->fetch_assoc()){
                                //Creating a group for this class
                                $arr2 = array("name"=>$members["lastName"] . ", " . $members["firstName"], "eMail"=>$members["userName"], "linkID"=>$members["profileID"]); 
                                array_push($arr, $arr2);    
                            }
                        }    
                        //faculty
                        $sqlgetmembers2 = "SELECT * FROM tblclass AS class
                        INNER JOIN tblusers AS users ON users.profileID = class.facultyProfile
                        INNER JOIN tblfacultyinfo AS faculty ON faculty.profileID = class.facultyProfile
                        WHERE classProfile = '".$object['classProfile']."'";
                        $resgetmembers2 = $conn->query($sqlgetmembers2);
                        if ($resgetmembers2->num_rows > 0){
                            while($members2 = $resgetmembers2->fetch_assoc()){
                                //Creating a group for this class
                                $arr2 = array("name"=>$members2["lastName"] . ", " . $members2["firstName"], "eMail"=>$members2["userName"], "linkID"=>$members2["profileID"]); 
                                array_push($arr, $arr2);    
                            }
                        } 
                        //$currobject[] = $arr;


                        $sqlfindgroups = "SELECT * FROM tblgroups WHERE groupMail = '".$object["classMail"]."'";
                        $resgroups = $conn2->query($sqlfindgroups);
                        if ($resgroups->num_rows > 0){
                            while($groups = $resgroups->fetch_assoc()){
                                $sqlupdate2 = "UPDATE tblgroups SET groupMembers = '".json_encode($arr)."', groupMail = '".$object['classMail']."' WHERE groupProfile = '".$groups['groupProfile']."'";
                                $conn2->query($sqlupdate2);   
                                foreach ($arr as $item) {
                                    $this->addNewGroupAsContacts($conn, $conn2, $object['className'], $object['classMail'], $item['linkID']);
                                }                      
                            }
                        } else {
                            $class = "INSERT INTO tblgroups (groupProfile, groupName, groupMail, groupOwner, type, isActive, dateCreated, groupMembers)
                            VALUES ('".$object['classProfile']."','".$object['className']."','".$object['classMail']."','system.class', 'class', 1, (SELECT NOW()), '".json_encode($arr)."')";
                            if($conn2->query($class) === TRUE){
                                //add group to faculty contacts
                                $this->addNewGroupAsContacts($conn, $conn2, $object['className'], $object['classMail'], $object['facultyID']);
                            }                            
                        }                                                   
                        
                        return true;      
                    } else {
                        throw new Exception("ERR_CREATE");
                    }
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }    
        }

        public function getMembers($conn, $classProfile){
            try {
                $output = array();
                $sql = "SELECT student.img AS img, student.isActive, student.profileID, student.firstName, student.middleName, student.lastName, student.eMail, users.userName FROM tblclassmember AS member 
                INNER JOIN tblstudentlist AS student ON student.profileID = member.studentProfile
                INNER JOIN tblstudentprofile AS profile ON profile.profileID = student.profileID
                INNER JOIN tblusers AS users ON users.profileID = student.profileID
                WHERE classProfile = '".$classProfile."'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }  
        }
        public function getStudentListForEnroll($conn, $classID){
            try {
                $output = array();
                $sql = "SELECT * FROM tblclass WHERE classProfile = '".$classID."'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $strandID = $row["strandID"];
                        $schoolyearID = $row["schoolyearID"];
                        $semesterID = $row["semesterID"];
                        $gradelevelID = $row["gradelevelID"];
                        $student = "SELECT * FROM tblstudentprofile AS profile
                        INNER JOIN tblstudentlist AS list ON list.profileID = profile.profileID
                        WHERE profile.strandID = '".$strandID."' AND profile.schoolyearID = '".$schoolyearID."' AND profile.gradelevelID = '".$gradelevelID."' AND profile.classID = ''";
                        $resstudent = $conn->query($student);
                        if ($resstudent->num_rows > 0) {
                            while($students = $resstudent->fetch_assoc()) {
                                $output[] =  $students;
                            }
                        }
                    } 
                }
                echo json_encode($output);  
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }
        public function enrollStudentToClass($conn, $classID, $profileIDs){
            try {
                $output = array();
                $update = false;
                foreach ($profileIDs as $ID) { 
                    $sqllastclass = "SELECT * FROM tblclassmember WHERE studentProfile = '$ID' AND isActive = 1";
                    $resultlastclass = $conn->query($sqllastclass);
                    if ($resultlastclass->num_rows > 0) {
                        $sqlupdatelastclass = "UPDATE tblclassmember SET isActive = 0 WHERE studentProfile = '$ID'";
                        $conn->query($sqlupdatelastclass);
                    }
                    $sql = "UPDATE tblstudentprofile SET classID = '$classID' WHERE profileID = '$ID'";
                    if($conn->query($sql) === TRUE){
                        $sqlins = "INSERT INTO tblclassmember (classProfile, studentProfile, isActive)
                        VALUES ('$classID', '$ID', 1)";
                        if($conn->query($sqlins) === TRUE) {
                            $update = true;
                        } else {
                            $update = false;
                        } 
                    } else {
                        $update = false;
                    }
                }
                if($update){
                    echo true; 
                } else {
                    echo false;
                }
                 
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }
        public function removeStudentAccount($conn, $classID, $studentID){
            try {
                $del = false;
                $sql = "UPDATE tblstudentprofile SET classID = '' WHERE profileID = '$studentID'";
                if($conn->query($sql) === TRUE){
                    $sqldel = "DELETE FROM tblclassmember WHERE studentProfile = '$studentID' AND classProfile = '$classID'";
                    if($conn->query($sqldel) === TRUE) {
                        $del = true;
                    } else {
                        $del = false;
                    } 
                } else {
                    $del = false;
                }

                if($del){
                    echo true; 
                } else {
                    echo false;
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }

        public function getProfileEmail($conn, $profileID){
            try {
                $output = array();
                $sql = "SELECT userName FROM tblusers WHERE profileID = '$profileID'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }          
        }
        #endregion

        #region student
        public function getStudentList($conn){
            try {
                $output = array();
                $sql = "SELECT s.img AS img, u.isActive, s.firstName, s.lastName, s.middleName, s.eMail, s.profileID, st.strand FROM tblstudentlist AS s 
                INNER JOIN tblstudentprofile AS p ON p.profileID = s.profileID
                INNER JOIN tblstrands AS st ON st.strandID = p.strandID
                iNNER JOIN tblusers AS u ON u.profileID = s.profileID
                WHERE s.isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                    echo json_encode($output);
                }  else {
                    echo "INFO_NOLIST";
                }
                  
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }   
        }

        public function getActiveStrandsPerTrack($conn, $object){
            try {
                $output = array();
                $sql = "SELECT DISTINCT(strand.strandID),strand.strand FROM tblstrandlist AS list
                INNER JOIN tblstrands AS strand ON strand.strandID = list.strandID 
                INNER JOIN tblsubjectlist AS sublist ON sublist.strandID = list.strandID AND sublist.schoolyearID = list.schoolyearID AND sublist.gradelevelID = list.gradelevelID
                WHERE list.trackID = '".$object['trackID']."' AND list.gradelevelID = '".$object['gradelevelID']."' AND list.schoolyearID = '".$object['schoolyearID']."' AND sublist.semesterID = '".$object['semesterID']."' AND list.isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                        
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function getFilteredClassList($conn, $object){
            try {
                $output = array();
                $sql = "SELECT sec.className, sec.classProfile FROM tblclass AS sec 
                INNER JOIN tblfacultyinfo AS fac ON sec.facultyProfile = fac.profileID 
                INNER JOIN tblgradelevel as grade ON sec.gradelevelID = grade.gradelevelID 
                WHERE sec.isActive = 1 AND grade.isActive = 1 AND sec.strandID = '".$object['strandID']."' AND sec.gradelevelID = '".$object['gradelevelID']."' AND sec.schoolyearID = '".$object['schoolyearID']."' AND sec.semesterID = '".$object['semesterID']."'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    } 
                }
                echo json_encode($output); 
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function saveStudent($conn, $object){
            try {
                $randPassword = $this->randomPassword();
                $randGuardianPassword = $this->randomPassword();

                do{
                    $profileID = $this->generateRandomString();
                    $sqlTest = "SELECT * FROM tblusers WHERE profileID = '" . $profileID . "'";
                    $resultTest = $conn->query($sqlTest);
                } while($resultTest->num_rows > 0);

                do{
                    $guardianProfileID = $this->generateRandomString();
                    $sqlTest = "SELECT * FROM tblusers WHERE profileID = '" . $guardianProfileID . "'";
                    $resultTest = $conn->query($sqlTest);
                } while($resultTest->num_rows > 0);

                //overwrite random password for now
                // $randPassword = 'P@$$w0rd!';
                // $randGuardianPassword = 'P@$$w0rd!';

                $sqlselectemail = "SELECT eMail FROM tblstudentlist WHERE eMail = '".$object['eMail']."' AND isActive = 1";
                $resultemail = $conn->query($sqlselectemail);
                if ($resultemail->num_rows > 0) {
                    throw new Exception("ERR_DUPLICATE_EMAIL");
                }
                $sqlselectlrn = "SELECT LRN FROM tblstudentprofile WHERE LRN = '".$object['lrn']."' AND isActive = 1";
                $resultlrn = $conn->query($sqlselectlrn); 
                if ($resultlrn->num_rows > 0) {
                    throw new Exception("ERR_DUPLICATE_LRN");
                }
                $sqlselectusername = "SELECT userName FROM tblusers WHERE userName = '".$object['userName']."' AND isActive = 1";
                $resultusername = $conn->query($sqlselectusername); 
                if ($resultusername->num_rows > 0) {
                    throw new Exception("ERR_DUPLICATE_USERNAME");
                }
                $sqlselectname = "SELECT firstName, lastName FROM tblstudentlist WHERE firstName = '".$object['firstName']."' AND lastName = '".$object['lastName']."' AND isActive = 1";
                $resultname = $conn->query($sqlselectname); 
                if ($resultname->num_rows > 0) {
                    throw new Exception("ERR_DUPLICATE");
                }


                if(is_numeric($object['gContactNo'])){
                    if($object['studentType'] == "Regular"){ //Regular Student block allotment of subjects
                        $sqlGuardian = "SELECT userName, profileID FROM tblusers WHERE userName = '".$object['gEMail']."' AND isActive = 1";
                        $resultGuardian = $conn->query($sqlGuardian); 
                        if ($resultGuardian->num_rows > 0) { 
                            while($rowGuardian = $resultGuardian->fetch_assoc()) { 
                                //if profile already exist, get profile ID instead
                                $guardianProfileID = $rowGuardian["profileID"];
                            }  
                        } else {
                            //create guardian login credentials
                            $sqlLoginG = "INSERT INTO tblusers (userName, passWord, accntLevel, profileID)
                            VALUES ('".$object['gEMail']."', '". hash('md5', $randGuardianPassword) ."', 3, '" .$guardianProfileID . "')"; 
                            $sqlLoginG2 = "INSERT INTO tbluserex (userName, passWord, profileID)
                            VALUES ('".$object['gEMail']."', '". $randGuardianPassword ."', '" .$guardianProfileID . "')"; 
                            $sqlInsertGuardian = "INSERT INTO tblguardianprofile (guardianProfileID, prefix, guardianFName, guardianMName, guardianLName, guardianGender, guardianEmail, guardianContactNo, groups)
                            VALUE ('".$guardianProfileID."', '".$object['gPrefix']."', '".$object['gFirstName']."', '".$object['gMiddleName']."', '".$object['gLastName']."', '".$object['gGender']."', '".$object['gEMail']."', '".$object['gContactNo']."', '3:4')";
                                $conn->query($sqlLoginG);
                                $conn->query($sqlLoginG2);
                                $conn->query($sqlInsertGuardian);
                        }

                        //add student to list
                        $sql = "INSERT INTO tblstudentlist (studentType, firstName, middleName, lastName, gender, eMail, profileID)
                        VALUES ('".$object['studentType']."', '".$object['firstName']."', '".$object['middleName']."', '".$object['lastName']."', '".$object['gender']."','".$object['eMail']."', '".$profileID."')";  
                        $sqlinsertprofile = "INSERT INTO tblstudentprofile (LRN, profileID, strandID, schoolyearID, semesterID, gradelevelID, classID, guardianID, guardianRelation, groups)
                        VALUE ('".$object['lrn']."', '".$profileID."', '".$object['strandID']."', '".$object['schoolyearID']."', '".$object['semesterID']."', '".$object['gradelevelID']."', '', '".$guardianProfileID."', '".$object['gRelation']."', '2:4')";
                        $sqlLogin = "INSERT INTO tblusers (userName, passWord, accntLevel, profileID)
                        VALUES ('".$object['userName']."', '". hash('md5', $randPassword) ."', 2, '" .$profileID . "')"; 
                        $sqlLogin2 = "INSERT INTO tbluserex (userName, passWord, profileID)
                        VALUES ('".$object['userName']."', '". $randPassword ."', '" .$profileID . "')"; 

                        // //set student class
                        // $sqlinsertclass ="INSERT INTO tblclassmember (classProfile, studentProfile) 
                        // VALUES ('".$object['classID']."', '" .$profileID . "')";
                        // if($conn->query($sqlinsertclass) === TRUE){
                        //     $sqlselect = "SELECT * FROM tblclasssched AS sched 
                        //     INNER JOIN tblsubjectlist AS subjectlist ON subjectlist.subjectlistID = sched.subjectID
                        //     INNER JOIN tblsubjects AS subjects ON subjects.subjectID = subjectlist.subjectID
                        //     WHERE subjectlist.strandID = '".$object['strandID']."' 
                        //     AND subjectlist.gradelevelID = '".$object['gradelevelID']."' 
                        //     AND subjectlist.schoolyearID = '".$object['schoolyearID']."' 
                        //     AND sched.classProfile = '".$object['classID']."'
                        //     AND subjectlist.isActive = 1";
    
                        //     $result = $conn->query($sqlselect);
                        //     if ($result->num_rows > 0) {
                        //         while($row = $result->fetch_assoc()){
                                    
                        //             // $sqlinsertstudentgrades = "INSERT INTO tblstudentgrades (profileID, strandID, schoolyearID, gradelevelID, facultyProfile, roomID, subjectID,  dateAdded)
                        //             // VALUE ('".$profileID."', '".$object['strandID']."', '".$object['schoolyearID']."', '".$object['gradelevelID']."', '".$row['facultyProfile']."', '".$row['classProfile']."', '".$row['subjectlistID']."', (SELECT NOW()))";
                        //             // $conn->query($sqlinsertstudentgrades); 
    
                        //             $resultquarter = $conn->query("SELECT * FROM tblquarter WHERE semesterID = '".$object['semesterID']."' AND isActive = 1");
                        //             if ($resultquarter->num_rows > 0) {
                        //                 while($quarter = $resultquarter->fetch_assoc()){
                        //                     $sqlinsertgradesheet = "INSERT INTO tblgradesheet (schoolyearID, gradelevelID, semesterID, strandID, subjectID, facultyID, studentID, quarterID)
                        //                     VALUES ('".$object['schoolyearID']."', '".$object['gradelevelID']."', '".$object['semesterID']."', '".$object['strandID']."', '".$row['subjectlistID']."', '".$row['facultyProfile']."', '".$profileID."', '".$quarter['quarterID']."')";
                        //                     $conn->query($sqlinsertgradesheet);
                        //                 }
                        //             }
    
                        //             // $sqlinsert = "INSERT INTO tblstudentsubjects (profileID, subjectID, strandID, schoolyearID, semesterID, gradelevelID, dateAdded)
                        //             // VALUE ('".$profileID."', '".$row['subjectlistID']."', '".$strand."', '".$schoolyear."', '".$semesterID."', '".$gradelevel."', (SELECT NOW()))"; 
                        //             // $conn->query($sqlinsert);                    
                        //         } 
                        //     }
                        // }
                        if($conn->query($sql) === TRUE &&
                            $conn->query($sqlLogin) === TRUE &&
                            $conn->query($sqlLogin2) === TRUE &&
                            $conn->query($sqlinsertprofile) === TRUE){ 
                                $this->sendOutwardMail($object["firstName"], $object["lastName"], $object["eMail"] ,$object["userName"], $randPassword); //student
                                $this->sendOutwardMail($object["gFirstName"], $object["gLastName"], $object["gEMail"] ,$object["gEMail"], $randGuardianPassword); //guardian
                            echo true;
                            } else { 
                                throw new Exception("ERR_CREATE");
                            }
                    } else { //For Irregular and/or Transferee Manual adding of subjects

                    }                                    
                } else {
                    throw new Exception("INFO_WRONG_FORMAT_REGEX");
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }  
        }

        public function getStudentInfo($conn, $studentID) {
            try {
                $output = array();
                $sql = "SELECT 
                            sProfile.LRN, 
                            sProfile.classID, 
                            studentlist.studentType, 
                            sProfile.schoolyearID, 
                            sProfile.semesterID, 
                            sProfile.gradelevelID, 
                            sProfile.strandID, 
                            track.trackID, 
                            studentlist.firstName, 
                            studentlist.middleName, 
                            studentlist.lastName, 
                            studentlist.gender, 
                            studentlist.eMail, 
                            sProfile.guardianID, 
                            guardian.prefix, 
                            guardian.guardianFName, 
                            guardian.guardianMName, 
                            guardian.guardianLName, 
                            sProfile.guardianRelation, 
                            guardian.guardianGender, 
                            guardian.guardianEmail, 
                            guardian.guardianContactNo, 
                            users.userName, 
                            TO_BASE64(studentlist.img) AS img, 
                            users.isActive, 
                            class.className, 
                            users.profileID
                        FROM tblstudentlist AS studentlist 
                        INNER JOIN tblstudentprofile AS sProfile ON studentlist.profileID = sProfile.profileID 
                        LEFT JOIN tblguardianprofile AS guardian ON sProfile.guardianID = guardian.guardianProfileID
                        INNER JOIN tblstrandlist AS strand ON strand.strandID = sProfile.strandID 
                            AND strand.schoolyearID = sProfile.schoolyearID 
                            AND strand.gradelevelID = sProfile.gradelevelID
                        INNER JOIN tbltracks AS track ON track.trackID = strand.trackID
                        INNER JOIN tblusers AS users ON users.profileID = studentlist.profileID
                        LEFT JOIN tblclass AS class ON class.classProfile = sProfile.classID
                        WHERE studentlist.profileID = ?";
        
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $studentID);
                $stmt->execute();
                $result = $stmt->get_result();
        
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $output[] = $row;
                    }
                    echo json_encode($output);
                } else {
                    echo "INFO_NOLIST";
                }
            } catch (Exception $err) {
                $err->profileID = "paash.system.admin.function:" . __FUNCTION__;
                header('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }      

        public function updateStudent($conn, $object){
            try {
                $sqlselectemail = "SELECT eMail FROM tblstudentlist WHERE eMail = '".$object['eMail']."' AND profileID <> '".$object['profileID']."' AND isActive = 1";
                $resultemail = $conn->query($sqlselectemail);
                if ($resultemail->num_rows > 0) {
                    throw new Exception("ERR_DUPLICATE");
                } else {
                    $sqlselectusername = "SELECT userName FROM tblusers WHERE userName = '".$object['userName']."' AND profileID <> '".$object['profileID']."' AND isActive = 1";
                    $resultusername = $conn->query($sqlselectusername); 
                    if ($resultusername->num_rows > 0) {
                        throw new Exception("ERR_DUPLICATE");
                    }
                    $sqlselectlrn = "SELECT LRN FROM tblstudentprofile WHERE LRN = '".$object['lrn']."' AND profileID <> '".$object['profileID']."' AND isActive = 1";
                    $resultlrn = $conn->query($sqlselectlrn); 
                    if ($resultlrn->num_rows > 0) {
                        throw new Exception("ERR_DUPLICATE_LRN");
                    }
                    $sqlselectname = "SELECT firstName, lastName FROM tblstudentlist WHERE firstName = '".$object['firstName']."' AND lastName = '".$object['lastName']."' AND profileID <> '".$object['profileID']."' AND isActive = 1";
                    $resultname = $conn->query($sqlselectname); 
                    if ($resultname->num_rows > 0) {
                        throw new Exception("ERR_DUPLICATE");
                    }
                    if(is_numeric($object['gContactNo'])){
                        $resultprofile = $conn->query("SELECT * FROM tblstudentprofile WHERE profileID = '".$object['profileID']."' AND isActive = 1"); // GET OLD PROFILE
                        if ($resultprofile->num_rows > 0) {
                            while($row = $resultprofile->fetch_assoc()){ 

                                $sqlupdatelist = "UPDATE tblstudentlist SET studentType='".$object['studentType']."', firstName = '".$object['firstName']."', middleName = '".$object['middleName']."', lastName = '".$object['lastName']."', gender = '".$object['gender']."', eMail = '".$object['eMail']."' WHERE profileID = '".$object['profileID']."'";

                                $sqlGuardian = "SELECT userName, profileID FROM tblusers WHERE profileID = '".$row["guardianID"]."' AND isActive = 1";
                                $resultGuardian = $conn->query($sqlGuardian); 
                                if ($resultGuardian->num_rows > 0) { 
                                    while($rowGuardian = $resultGuardian->fetch_assoc()) { 
                                        //if profile already exist, get profile ID instead
                                        $guardianProfileID = $rowGuardian["profileID"];
                                        $sqlupdateguardianprofile = "UPDATE tblguardianprofile SET prefix = '".$object['gPrefix']."', guardianFName = '".$object['gFirstName']."', guardianMName = '".$object['gMiddleName']."', guardianLName = '".$object['gLastName']."', guardianGender ='".$object['gGender']."', guardianEmail = '".$object['gEMail']."', guardianContactNo ='".$object['gContactNo']."' WHERE guardianProfileID = '".$guardianProfileID."'";
                                        $conn->query($sqlupdateguardianprofile);
                                        $sqlupdateguardianusername = "UPDATE tblusers SET userName = '".$object['gEMail']."' WHERE profileID = '".$row['guardianID']."'";
                                        $conn->query($sqlupdateguardianusername);
                                        $sqlupdateguardianusernameex = "UPDATE tbluserex SET userName = '".$object['gEMail']."' WHERE profileID = '".$row['guardianID']."'";
                                        $conn->query($sqlupdateguardianusernameex);
                                    }  
                                }

                                $sqlupdateprofile = "UPDATE tblstudentprofile SET strandID = '".$object['strandID']."', schoolyearID = '".$object['schoolyearID']."', semesterID = '".$object['semesterID']."', gradelevelID = '".$object['gradelevelID']."', guardianID = '".$guardianProfileID."', guardianRelation = '".$object['gRelation']."', LRN = '".$object["lrn"]."' WHERE profileID = '".$object['profileID']."'";

                                if ($conn->query($sqlupdatelist) === TRUE && $conn->query($sqlupdateprofile) === TRUE ) {
                                    echo true;
                                } else {
                                    throw new Exception("ERR_CREATE");
                                }
                            }
                        }
                    }
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }     
        }

        public function getAcademicTrackm($conn){
            try {
                $output = array();
                $sql = "SELECT * FROM tbltracks WHERE isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                        
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }  
        }
        public function getActiveStrandsPerTrackm($conn, $object, $profileID){
            try {
                $output = array();
                $sql = "SELECT DISTINCT(strand.strandID),strand.strand FROM tblstrandlist AS list
                INNER JOIN tblstrands AS strand ON strand.strandID = list.strandID 
                INNER JOIN tblsubjectlist AS sublist ON sublist.strandID = list.strandID AND sublist.schoolyearID = list.schoolyearID AND sublist.gradelevelID = list.gradelevelID
                WHERE list.trackID = '".$object['trackID']."' AND list.gradelevelID = '".$object['gradelevelID']."' AND list.schoolyearID = '".$object['schoolyearID']."' AND sublist.semesterID = '".$object['semesterID']."' AND list.isActive = 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                        
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }  
        }

        public function transferStudent($conn, $classProfile, $studentProfile){
            try {
                $sqlupdate ="UPDATE tblstudentprofile SET classID = '$classProfile' WHERE profileID = '$studentProfile'";
                if($conn->query($sqlupdate) === TRUE){
                    echo true;
                } else {
                    echo false;
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function activateStudent($conn, $profileID){
            try {
                $sql = "UPDATE tblusers SET isActive = 1 WHERE profileID = '" . $profileID . "'"; 
                if($conn->query($sql) === TRUE){ 
                    echo true;
                } else{
                    throw new Exception("ERR_UPDATE");
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function deactivateStudent($conn, $profileID){
            try {
                $sql = "UPDATE tblusers SET isActive = 0 WHERE profileID = '" . $profileID . "'"; 
                if($conn->query($sql) === TRUE){ 
                    echo true;
                } else{
                    throw new Exception("ERR_UPDATE");
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }
        #endregion

        #region Database
        public function createBackup($conn, $conn2, $conn3, $profileID){
            try {
                // Database configuration
                // $host = "localhost";
                // $username = "root";
                // $password = "";
                $database_name = "db_paash";

                // Get connection object and set the charset
                //$conn = mysqli_connect($host, $username, $password, $database_name);
                $conn->set_charset("utf8");


                // Get All Table Names From the Database
                $tables = array();
                $sql = "SHOW TABLES";
                $result = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_row($result)) {
                    $tables[] = $row[0];
                }

                $sqlScript = "";
                foreach ($tables as $table) {
                    //Drop table first
                    $sqlScript .= "DROP TABLE $table" . ";||\n";

                    // Prepare SQLscript for creating table structure
                    $query = "SHOW CREATE TABLE $table";
                    $result = mysqli_query($conn, $query);
                    $row = mysqli_fetch_row($result);
                    
                    
                    $sqlScript .= $row[1] . ";||\n";
                    
                    
                    $query = "SELECT * FROM $table";
                    $result = mysqli_query($conn, $query);
                    
                    $columnCount = mysqli_num_fields($result);
                    
                    // Prepare SQLscript for dumping data for each table
                    for ($i = 0; $i < $columnCount; $i ++) {
                        while ($row = mysqli_fetch_row($result)) {
                            $sqlScript .= "INSERT INTO $table VALUES(";
                            for ($j = 0; $j < $columnCount; $j ++) {
                                $row[$j] = $row[$j];
                                
                                if (isset($row[$j])) {
                                    $sqlScript .= '"' . $row[$j] . '"';
                                } else {
                                    $sqlScript .= '""';
                                }   
                                if ($j < ($columnCount - 1)) {
                                    $sqlScript .= ',';
                                }
                            }
                            $sqlScript .= ");||\n";
                        }
                    }
                    
                    $sqlScript .= "\n"; 
                    
                }

                if(!empty($sqlScript))
                {
                    // Save the SQL script to a backup file
                    $backup_file_name = '../../db_backup/'.$database_name . '_backup_' . time() . '.sql';
                    $fileHandler = fopen($backup_file_name, 'w+');
                    $number_of_lines = fwrite($fileHandler, $sqlScript);
                    fclose($fileHandler); 

                    //Download the SQL backup file to the browser
                    // header('Content-Description: File Transfer');
                    // header('Content-Type: application/octet-stream');
                    // header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
                    // header('Content-Transfer-Encoding: binary');
                    // header('Expires: 0');
                    // header('Cache-Control: must-revalidate');
                    // header('Pragma: public');
                    // header('Content-Length: ' . filesize($backup_file_name));
                    // ob_clean();
                    // flush();
                    // readfile($backup_file_name);
                    // exec('rm ' . $backup_file_name); 

                    // header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . basename($backup_file_name) .'"');
                    // header('Content-Transfer-Encoding: binary');
                    // header('Expires: 0');
                    // header('Cache-Control: must-revalidate');
                    // header('Pragma: public');
                    // header('Content-Length: ' . filesize($backup_file_name));
                    // ob_clean();
                    // flush();
                    //readfile($backup_file_name); //showing the path to the server where the file is to be download
                    //exit;


                    //$response = true;
                    $logs = "INSERT INTO tblsystemlogs (logTitle, log, trace, dateLog, loggedBy)
                    VALUES ('0001', 'Backup Database', 'A backup has been created!', (SELECT NOW()), '".$profileID."')";
                    $sql = "INSERT INTO tblbackuplist (backupName, location, dateCreated, createdBy)
                    VALUES('".$database_name . '_backup_' . time()."', '".$backup_file_name."', (SELECT NOW()), '$profileID')";
                    if($conn3->query($sql) === TRUE && $conn->query($logs) === TRUE){
                        echo true;
                    } else {
                        throw new Exception("ERR_DB_BACKUP");
                    }   

                    echo true;
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }      
        }

        public function restoreMysqlDB($filePath, $conn, $profileID)
        {
            $sql = '';
            $error = '';
            
            if (file_exists($filePath)) {
                $lines = file($filePath);
                
                foreach ($lines as $line) {
                    
                    // Ignoring comments from the SQL script
                    if (substr($line, 0, 2) == '--' || $line == '') {
                        continue;
                    }
                    
                    $sql .= $line;
                    
                    if (substr(trim($line), - 1, 1) == ';') {
                        $result = mysqli_query($conn, $sql);
                        if (! $result) {
                            $error .= mysqli_error($conn) . "\n";
                        } else {
                            $error .= $line . "\n";
                        }
                        $sql = '';
                    }
                } // end foreach
                
                if ($error) {
                    $response = array(
                        "type" => "error",
                        "message" => $error
                    );
                } else {
                    $logs = "INSERT INTO tblsystemlogs (logTitle, log, trace, dateLog, loggedBy)
                    VALUES ('0001', 'Restore Database', 'A database backup has been imported! All data ahs been overwritten', (SELECT NOW()), '".$profileID."')";
                    if($conn->query($logs) === TRUE) {
                        $response = array(
                            "type" => "success",
                            "message" => "Database Restore Completed Successfully."
                        );
                    }

                }
                exec('rm ' . $filePath);
            } // end if file exists
            return $response;
        }

        public function getBackupList($conn3, $profileID){
            try {
                $output = array();
                $sql = "SELECT * FROM tblbackuplist ORDER BY dateCreated desc";
                $result = $conn3->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                      
                }
                echo json_encode($output); 
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }

        public function importDB($conn, $conn3, $backupID, $profileID){
            try {
                $sql = "SELECT * FROM tblbackuplist WHERE backupID = '$backupID'";
                $result = $conn3->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $sql2 = '';
                        $error = '';
                        
                        if (file_exists($row["location"])) {
                            $lines = file_get_contents($row["location"]);
                            $queries = explode('||', $lines);
                            foreach ($queries as $line) {
                                $line = str_replace("||","",trim($line));
                                if ($line){
                                    if(!$conn->query(strval($line))){
                                        $error .= mysqli_error($conn) . "\n";
                                    }
                                }
                            } // end foreach
                            
                            if ($error) {
                                throw new Exception($error);
                                // $response = array(
                                //     "type" => "error",
                                //     "message" => $error
                                // );
                            } else {
                                $logs = "INSERT INTO tblsystemlogs (logTitle, log, trace, dateLog, loggedBy)
                                VALUES ('0002', 'Restore Database', 'A database backup has been imported! All data ahs been overwritten', (SELECT NOW()), '".$profileID."')";
                                if($conn->query($logs) === TRUE) {
                                    echo true;
                                }
                                
                                // $response = array(
                                //     "type" => "success",
                                //     "message" => "Database Restore Completed Successfully."
                                // );
                            }
                            // exec('rm ' . $filePath);
                        } // end if file exists
                        return $response;
                    }
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }

        public function getSystemLogs($conn){
            try {
                $output = array();
                $sql = "SELECT * FROM tblsystemlogs ORDER BY logid desc";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                    
                }  
                echo json_encode($output);
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }   
        }
        #endregion

        #region Admin
        public function getAdminList($conn){
            try {
                $output = array();
                $sql = "SELECT img, isActive, firstName, lastName, middleName, eMail, adminProfile, adminLevel FROM tbladminprofile";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }   
        }

        public function getSelectedProfile($conn, $ID){
            try {
                $output = array();
                $sql = "SELECT img, isActive, firstName, lastName, middleName, eMail, adminProfile, adminLevel FROM tbladminprofile WHERE adminProfile = '$ID'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }   
        }

        public function deleteAdminAccnt($conn, $profileID){
            try {
                $output = array();
                $del1 = "DELETE FROM tbladminprofile WHERE adminProfile = '$profileID'";
                $del2 = "DELETE FROM tbluserex WHERE profileID = '$profileID'";
                $del3 = "DELETE FROM tblusers WHERE profileID = '$profileID'";
                if($conn->query($del1) === TRUE && $conn->query($del2) === TRUE && $conn->query($del3) === TRUE){
                    echo true;
                } else {
                    throw new Exception("ERR_DELETE");
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }   
        }

        public function createNewAdminAccnt($conn, $firstName, $middleName, $lastName, $email, $userName){
            try {
                $profileID = $this->generateRandomString();
                $password = $this->randomPassword();
                $sql = "INSERT INTO tbladminprofile (adminProfile, eMail, adminLevel, firstName, middleName, lastName, dateCreated, isActive)
                VALUES ('$profileID', '$email', 1, '$firstName', '$middleName', '$lastName', (SELECT NOW()), 1)"; 
                $sql2 = "INSERT INTO tbluserex (profileID, userName, password, isActive)
                VALUES ('$profileID', '$email', '$password', 1)"; 
                $sql3 = "INSERT INTO tblusers (userName, passWord, profileID, isActive, accntLevel)
                VALUES ('$userName', '".hash('md5',$password)."', '$profileID', 1, 0)"; 
                if($conn->query($sql) === TRUE && $conn->query($sql2) === TRUE && $conn->query($sql3) === TRUE){ 
                    $this->sendOutwardMail($firstName, $lastName, $email ,$userName, $password);
                    echo true;
                } else{ 
                    $del1 = "DELETE FROM tbladminprofile WHERE adminProfile = '$profileID'";
                    $del2 = "DELETE FROM tbluserex WHERE profileID = '$profileID'";
                    $del3 = "DELETE FROM tblusers WHERE profileID = '$profileID'";
                    $conn->query($del1);
                    $conn->query($del2);
                    $conn->query($del3);
                    throw new Exception("ERR_CREATE");
                }
            }
            catch (Exception $err) {
                $del1 = "DELETE FROM tbladminprofile WHERE adminProfile = '$profileID'";
                $del2 = "DELETE FROM tbluserex WHERE profileID = '$profileID'";
                $del3 = "DELETE FROM tblusers WHERE profileID = '$profileID'";
                $conn->query($del1);
                $conn->query($del2);
                $conn->query($del3);
                $this->systemLogs($conn, "System logs", "ERROR", $err->getMessage());
                header ('HTTP/1.1 500 Internal Server Error');
                echo $err->getMessage();
            }        
        }

        public function getProfile($conn, $profileID) {
            try {
                $output = array();
                $sql = "SELECT profile.eMail, profile.firstName, profile.middleName, profile.lastName, img , users.userName, users.passWord, profile.adminLevel FROM tblusers AS users
                INNER JOIN tbladminprofile AS profile ON profile.adminProfile = users.profileID
                WHERE users.profileID = '$profileID'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $output[] =  $row;
                        
                    }
                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }   
        }

        public function updatePassword($conn, $profileID, $oldPassword, $newPassword){
            try {
                $sql = "SELECT * FROM tblusers WHERE profileID = '$profileID'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        if($row["passWord"]==hash('md5', $oldPassword)){
                            $sqlupdate = "UPDATE tblusers SET passWord = '".hash('md5',$newPassword)."' WHERE profileID = '$profileID'"; 
                            $sqlupdate2 = "UPDATE tbluserex SET passWord = '$newPassword' WHERE profileID = '$profileID'"; 
                            if($conn->query($sqlupdate) === TRUE && $conn->query($sqlupdate2) === TRUE){ 
                                echo true;
                            } else{ 
                                throw new Exception("ERR_UPDATE");
                            }
                        } else {
                            throw new Exception("VERIFICATION_FAILED");
                        }
                    }  
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }        
        }

        public function updateAdminProfile($conn, $profileID, $firstName, $middleName, $lastName){
            try {
                $sqlupdate = "UPDATE tbladminprofile SET firstName = '$firstName', middleName = '$middleName', lastName = '$lastName' WHERE adminProfile = '$profileID'"; 
                if($conn->query($sqlupdate) === TRUE){ 
                    echo true;
                } else{ 
                    throw new Exception("ERR_UPDATE");
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }        
        }

        public function resetPassword($conn, $myProfileID, $profileID, $password){
            try {
                $output= array();
                $sql = "SELECT * FROM tblusers WHERE profileID = '$myProfileID'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        if($row["passWord"]==hash('md5', $password)){
                            $newPass = $this->randomPassword();
                            $sqlupdate = "UPDATE tblusers SET passWord = '".hash('md5',$newPass)."' WHERE profileID = '$profileID'"; 
                            $sqlupdate2 = "UPDATE tbluserex SET passWord = '$newPass' WHERE profileID = '$profileID'"; 
                            if($conn->query($sqlupdate) === TRUE && $conn->query($sqlupdate2) === TRUE){ 
                                $object = new stdClass();
                                $object->state = true;
                                $object->passkey = $newPass;
                                $output[] = $object;
                                echo json_encode($output);
                            } else{ 
                                throw new Exception("ERR_UPDATE");
                            }
                        } else {
                            throw new Exception("VERIFICATION_FAILED");
                        }
                    }  
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }         
        }

        public function resetDatabase($conn, $conn2, $profileID, $password){
            try {
                $output= array();
                $sql = "SELECT * FROM tblusers AS users
                INNER JOIN tbladminprofile AS profile ON profile.adminProfile = users.profileID
                WHERE users.profileID = '$profileID' AND profile.adminLevel = 0";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        if($row["passWord"]==hash('md5', $password)){
                            if($row["adminLevel"]==0){
                                $sqld1 = "DELETE FROM tblclass";
                                $sqld2 = "DELETE FROM tblclassmember";
                                $sqld3 = "DELETE FROM tblclasssched";
                                $sqld4 = "DELETE FROM tblclassschedule";
                                $sqld5 = "DELETE FROM tblfacultyinfo";
                                $sqld6 = "DELETE FROM tblfacultysubjects";
                                $sqld7 = "DELETE FROM tblgradesheet";
                                $sqld8 = "DELETE FROM tblguardianprofile";
                                $sqld9 = "DELETE FROM tblhps";
                                $sqld10 = "DELETE FROM tblschoolyear";
                                $sqld11 = "DELETE FROM tblstrandlist";
                                $sqld12 = "DELETE FROM tblstudentlist";
                                $sqld13 = "DELETE FROM tblstudentprofile";
                                $sqld14 = "DELETE FROM tblsubjectlist";
                                $sqlsel = "SELECT * FROM tbladminprofile WHERE adminLevel = 0";
                                $result = $conn->query($sqlsel);
                                if ($result->num_rows > 0){
                                    while($row = $result->fetch_assoc()){ 
                                        $id = $row['adminProfile'];
                                        $sqld15 = "DELETE FROM tblusers WHERE profileID <> '$id'";
                                        $sqld16 = "DELETE FROM tbluserex WHERE profileID <> '$id'";
                                    }
                                }
                                $sqld17 = "DELETE FROM tbladminprofile WHERE adminLevel <> 0";
                                $sqld18 = "DELETE FROM tblcontacts";
                                $sqld19 = "DELETE FROM tblgroups WHERE type <> 'default'";
                                $sqld20 = "DELETE FROM tblMail";
                                if($conn->query($sqld1) === TRUE
                                && $conn->query($sqld2) === TRUE
                                && $conn->query($sqld3) === TRUE
                                && $conn->query($sqld4) === TRUE
                                && $conn->query($sqld5) === TRUE
                                && $conn->query($sqld6) === TRUE
                                && $conn->query($sqld7) === TRUE
                                && $conn->query($sqld8) === TRUE
                                && $conn->query($sqld9) === TRUE
                                && $conn->query($sqld10) === TRUE
                                && $conn->query($sqld11) === TRUE
                                && $conn->query($sqld12) === TRUE
                                && $conn->query($sqld13) === TRUE
                                && $conn->query($sqld14) === TRUE
                                && $conn->query($sqld15) === TRUE
                                && $conn->query($sqld16) === TRUE
                                && $conn->query($sqld17) === TRUE
                                && $conn2->query($sqld18) === TRUE
                                && $conn2->query($sqld19) === TRUE
                                && $conn2->query($sqld20) === TRUE){ 
                                    $auth1 = "ALTER TABLE tblusers AUTO_INCREMENT=1001";
                                    $auth2 = "ALTER TABLE tblclass AUTO_INCREMENT=1001";
                                    $auth3 = "ALTER TABLE tblclassmember AUTO_INCREMENT=1001";
                                    $auth4 = "ALTER TABLE tblclasssched AUTO_INCREMENT=1001";
                                    $auth5 = "ALTER TABLE tblclassschedule AUTO_INCREMENT=1001";
                                    $auth6 = "ALTER TABLE tblfacultyinfo AUTO_INCREMENT=1001";
                                    $auth7 = "ALTER TABLE tblfacultysubjects AUTO_INCREMENT=1001";
                                    $auth8 = "ALTER TABLE tblgradesheet AUTO_INCREMENT=1001";
                                    $auth9 = "ALTER TABLE tblguardianprofile AUTO_INCREMENT=1001";
                                    $auth10 = "ALTER TABLE tblhps AUTO_INCREMENT=1001";
                                    $auth11 = "ALTER TABLE tblschoolyear AUTO_INCREMENT=1001";
                                    $auth12 = "ALTER TABLE tblstrandlist AUTO_INCREMENT=1001";
                                    $auth13 = "ALTER TABLE tblstudentlist AUTO_INCREMENT=1001";
                                    $auth14 = "ALTER TABLE tblstudentprofile AUTO_INCREMENT=1001";
                                    $auth15 = "ALTER TABLE tblsubjectlist AUTO_INCREMENT=1001";
                                    $auth16 = "ALTER TABLE tbluserex AUTO_INCREMENT=1001";
                                    $auth17 = "ALTER TABLE tbladminprofile AUTO_INCREMENT=1001";
                                    $auth18 = "ALTER TABLE tblcontacts AUTO_INCREMENT=1001";
                                    $auth19 = "ALTER TABLE tblgroups AUTO_INCREMENT=1001";
                                    $auth20 = "ALTER TABLE tblMail AUTO_INCREMENT=1001";
                                    $conn->query($auth1);
                                    $conn->query($auth2);
                                    $conn->query($auth3);
                                    $conn->query($auth4);
                                    $conn->query($auth5);
                                    $conn->query($auth6);
                                    $conn->query($auth7);
                                    $conn->query($auth8);
                                    $conn->query($auth9);
                                    $conn->query($auth10);
                                    $conn->query($auth11);
                                    $conn->query($auth12);
                                    $conn->query($auth13);
                                    $conn->query($auth14);
                                    $conn->query($auth15);
                                    $conn->query($auth16);
                                    $conn->query($auth17);
                                    $conn2->query($auth18);
                                    $conn2->query($auth19);
                                    $conn2->query($auth20);

                                    $logs = "INSERT INTO tblsystemlogs (logTitle, log, trace, dateLog, loggedBy)
                                    VALUES ('0000', 'Database Reset', 'Database has been reset to default state. all data ahs been wiped out except the master adminstrators account.', (SELECT NOW()), '".$profileID."')";
                                    if($conn->query($logs)===TRUE){
                                        echo true;
                                    }
                                    
                                } else{ 
                                    echo "ERR_UPDATE";
                                }
                            } else {
                                echo "ERR_UPDATE";
                            }

                        } else {
                            echo "VERIFICATION_FAILED";
                        }
                    }  
                } else {
                    echo "INVALID_ACCESS";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }         
        }
        #endregion
        #region Mail
        public function getMessageList($conn, $conn2, $profileID){
            try {
                $output = array();
                $sender = array();
                $reciever = array();
                $arr;
                $x ="";
                $y ="";
                $tmp = "";

                $sql = "SELECT * FROM tblMail WHERE isReply = 0 AND isSent = 1 AND isActive = 1";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $mailRecieverID = json_decode($row['mailRecieverID']);
                        foreach ($mailRecieverID as $xx){

                            if($xx->profileID == $profileID){
                                $object = new stdClass();
                                $object->conversationID = $row['conversationID'];
                                $object->isAnnouncement = $row['isAnnouncement'];
                                $subDetails = new stdClass();
                                $subDetails->mailID = $row['mailID'];
                                $subDetails->conversationID = $row['conversationID'];
                                $subDetails->mailTitle = $row['mailTitle'];
                                $subDetails->mailContent = $row['mailContent'];
                                $subDetails->mailCreatorID = $row['mailCreatorID'];
                                $x ="";
                                $x = json_decode($this->getProfileDetail($conn, $conn2, $row['mailSenderID'],0));
                                $sender = [];
                                foreach ($x as $xx) {
                                    $objectx = new stdClass();
                                    $objectx->userName = $xx->userName;
                                    $objectx->displayName = $xx->displayName;
                                    $objectx->localAdd = $xx->localAdd;
                                    $objectx->profileID = $xx->profileID;
                                    $sender[] = $objectx;
                                }
                                $subDetails->mailSenderID = $sender;
                                $subDetails->senderID=$row['mailSenderID'];
                                $y ="";
                                $y = json_decode($row['mailRecieverID']);
                                $reciever = [];
                                foreach ($y as $yy) {
                                    $tmp = json_decode($this->getProfileDetail($conn, $conn2, $yy->profileID, $yy->isRead));
                                    foreach($tmp as &$yy){ 
                                        $objecty = new stdClass();
                                        $objecty->userName = $yy->userName;
                                        $objecty->displayName = $yy->displayName;
                                        $objecty->localAdd = $yy->localAdd;
                                        $objecty->profileID = $yy->profileID;
                                        $objecty->isRead = $yy->isRead;
                                        $reciever[] = $objecty;
                                    }
                                    
                                }
        
                                $subDetails->mailRecieverID = $reciever;
                                $subDetails->dateCreated = $row['dateCreated'];
                                $subDetails->dateSent = $row['dateSent'];
                                $subDetails->isReply = $row['isReply'];
                                $subDetails->isRead = $row['isRead'];
                                $subDetails->isActive = $row['isActive'];
                                $subDetails->isSent = $row['isSent'];
                                $object->subDetails[] = $subDetails;
        
                                if(!empty($output)){
                                    if (in_array($row['conversationID'], array_column($output, "conversationID"))) {
                                        $index = array_search($row['conversationID'],array_column($output, "conversationID"));
                                        array_push($output[$index]->subDetails, $object);
                                      } else { 
                                        $object->conversationID = $row['conversationID'];
                                        $output[] = $object;
                                      }
                                } else {
                                    $object->conversationID = $row['conversationID'];
                                    $output[] = $object;
                                }   
                            } 
                        }
                    }
                    echo json_encode($output);   
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function getMessageSentList($conn, $conn2, $profileID){
            try {
                $output = array();
                $sender = array();
                $reciever = array();
                $arr;
                $sql = "SELECT * FROM tblMail WHERE mailSenderID = '$profileID' AND isSent = 1 AND isActive = 1";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $object = new stdClass();
                        $object->conversationID = $row['conversationID'];
                        // $subDetails = new stdClass();
                        $object->mailID = $row['mailID'];
                        $object->conversationID = $row['conversationID'];
                        $object->mailTitle = $row['mailTitle'];
                        $object->mailContent = $row['mailContent'];
                        $object->mailCreatorID = $row['mailCreatorID'];
                        $x = json_decode($this->getProfileDetail($conn, $conn2, $row['mailSenderID'],0));
                        $objectx = new stdClass();
                        foreach ($x as $xx) {
                            $objectx->userName = $xx->userName;
                            $objectx->displayName = $xx->displayName;
                            $objectx->localAdd = $xx->localAdd;
                            $objectx->profileID = $xx->profileID;
                            $sender[] = $objectx;
                        }
                        $object->mailSenderID = $sender;
                        $object->senderID=$row['mailSenderID'];
                        $y = json_decode($row['mailRecieverID']);
                        foreach ($y as $yy) {
                            $tmp = json_decode($this->getProfileDetail($conn, $conn2, $yy->profileID, $yy->isRead));
                            foreach($tmp as &$yy){ 
                                $objecty = new stdClass();
                                $objecty->userName = $yy->userName;
                                $objecty->displayName = $yy->displayName;
                                $objecty->localAdd = $yy->localAdd;
                                $objecty->profileID = $yy->profileID;
                                $reciever[] = $objecty;
                            }
                            
                        }

                        $object->mailRecieverID = $reciever;
                        $object->dateCreated = $row['dateCreated'];
                        $object->dateSent = $row['dateSent'];
                        $object->isReply = $row['isReply'];
                        $object->isRead = $row['isRead'];
                        $object->isActive = $row['isActive'];
                        $object->isSent = $row['isSent'];
                        //$object->subDetails[] = $subDetails;

                        $object->conversationID = $row['conversationID'];
                        $output[] = $object;
                    }
                    echo json_encode($output);   
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }               
        }

        public function getMessageDeletedList($conn, $conn2, $profileID){
            try {
                $output = array();
                $sender = array();
                $reciever = array();
                $arr;
                $sql = "SELECT * FROM tblMail WHERE mailSenderID = '$profileID' AND isSent = 1 AND isActive = 0";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $object = new stdClass();
                        $object->conversationID = $row['conversationID'];
                        // $subDetails = new stdClass();
                        $object->mailID = $row['mailID'];
                        $object->conversationID = $row['conversationID'];
                        $object->mailTitle = $row['mailTitle'];
                        $object->mailContent = $row['mailContent'];
                        $object->mailCreatorID = $row['mailCreatorID'];
                        $x = "";
                        $x = json_decode($this->getProfileDetail($conn, $conn2, $row['mailSenderID'],0));
                        $sender = [];
                        foreach ($x as $xx) {
                            $objectx = new stdClass();
                            $objectx->userName = $xx->userName;
                            $objectx->displayName = $xx->displayName;
                            $objectx->localAdd = $xx->localAdd;
                            $objectx->profileID = $xx->profileID;
                            $sender[] = $objectx;
                        }
                        $object->mailSenderID = $sender;
                        $object->senderID=$row['mailSenderID'];
                        $y ="";
                        $y = json_decode($row['mailRecieverID']);
                        $reciever = [];
                        foreach ($y as $yy) {
                            $tmp = json_decode($this->getProfileDetail($conn, $conn2, $yy->profileID, $yy->isRead));
                            foreach($tmp as &$yy){ 
                                $objecty = new stdClass();
                                $objecty->userName = $yy->userName;
                                $objecty->displayName = $yy->displayName;
                                $objecty->localAdd = $yy->localAdd;
                                $objecty->profileID = $yy->profileID;
                                $reciever[] = $objecty;
                            }
                            
                        }

                        $object->mailRecieverID = $reciever;
                        $object->dateCreated = $row['dateCreated'];
                        $object->dateSent = $row['dateSent'];
                        $object->isReply = $row['isReply'];
                        $object->isRead = $row['isRead'];
                        $object->isActive = $row['isActive'];
                        $object->isSent = $row['isSent'];
                        //$object->subDetails[] = $subDetails;

                        $object->conversationID = $row['conversationID'];
                        $output[] = $object;
                    }
                    echo json_encode($output);   
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }             
        }

        public function getProfileDetail($conn, $conn2, $profileID, $isRead){
            try {
                $output = array();
                $arr = array();
                $object = new stdClass();
                $sql = "SELECT users.accntLevel as accntLevel, users.userName as userName, users.profileID as profileID, admins.firstName as adminFName, admins.lastName as adminLName, faculty.firstName as facultyFName, faculty.lastName as facultyLName, student.firstName as studentFName, student.lastName as studentLName, parent.guardianFName as parentFName, parent.guardianLName as parentLName FROM tblusers as users
                LEFT OUTER JOIN tbladminprofile as admins ON admins.adminProfile = users.profileID
                LEFT OUTER JOIN tblfacultyinfo as faculty ON faculty.profileID = users.profileID
                LEFT OUTER JOIN tblstudentlist as student ON student.profileID = users.profileID
                LEFT OUTER JOIN tblguardianprofile as parent ON parent.guardianProfileID = users.profileID
                WHERE users.profileID = '$profileID'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        switch ($row['accntLevel']) {
                            case '0': { //admin
                                $object->userName = $row["userName"];
                                $object->profileID = $row["profileID"];  
                                $object->localAdd = $row["userName"];
                                $object->displayName = $row["adminFName"] . " " . $row["adminLName"];
                                $object->isRead = $isRead; 
                                break;
                            }
                            case '1': { //faculty
                                $object->userName = $row["userName"];
                                $object->profileID = $row["profileID"];  
                                $object->localAdd = $row["userName"];
                                $object->displayName = $row["facultyFName"] . " " . $row["facultyLName"];
                                $object->isRead = $isRead; 
                                break;
                            }
                            case '2': { //student
                                $object->userName = $row["userName"];
                                $object->profileID = $row["profileID"];  
                                $object->localAdd = $row["userName"];
                                $object->displayName = $row["studentFName"] . " " . $row["studentLName"];
                                $object->isRead = $isRead; 
                                break;
                            }
                            case '3': { //parent
                                $object->userName = $row["userName"];
                                $object->profileID = $row["profileID"];  
                                $object->localAdd = $row["userName"];
                                $object->displayName = $row["parentFName"] . " " . $row["parentLName"];
                                $object->isRead = $isRead; 
                                break;
                            }
                        } 
                    }      
                }
                $output[] = $object; 
                return json_encode($output);
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function getMessageContent($conn, $conn2, $mailID, $profileID){
            try {
                $output = array();
                $arr;
                $conversationID = "";
                $sql = "SELECT * FROM tblMail WHERE mailID = '$mailID' AND isReply = 0 AND isSent = 1 AND isActive = 1";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $conversationID = $row['conversationID'];
                        $sender = array();
                        $reciever = array();
                        $object = new stdClass();
                        $object->mailID = $row['mailID'];
                        $object->conversationID = $row['conversationID'];
                        $object->mailTitle = $row['mailTitle'];
                        $object->mailContent = $row['mailContent'];
                        $object->mailCreatorID = $row['mailCreatorID'];
                        $x = json_decode($this->getProfileDetail($conn, $conn2, $row['mailSenderID'],1));
                        $objectx = new stdClass();
                        foreach ($x as $xx) {
                            $objectx->userName = $xx->userName;
                            $objectx->displayName = $xx->displayName;
                            $objectx->localAdd = $xx->localAdd;
                            $objectx->profileID = $xx->profileID;
                            $sender[] = $objectx;
                        }
                        $object->mailSenderID = $sender;
                        $object->senderID=$row['mailSenderID'];
                        $y = json_decode($row['mailRecieverID']);
                        foreach ($y as $yy) {
                            $tmp = json_decode($this->getProfileDetail($conn, $conn2, $yy->profileID, $yy->isRead));
                            foreach($tmp as &$yy){ 
                                $objecty = new stdClass();
                                $objecty->userName = $yy->userName;
                                $objecty->displayName = $yy->displayName;
                                $objecty->localAdd = $yy->localAdd;
                                $objecty->profileID = $yy->profileID;
                                $reciever[] = $objecty;
                            }
                            
                        }

                        $object->mailRecieverID = $reciever;
                        $object->dateCreated = $row['dateCreated'];
                        $object->dateSent = $row['dateSent'];
                        $object->isReply = $row['isReply'];
                        $object->isRead = $row['isRead'];
                        $object->isActive = $row['isActive'];
                        $object->isSent = $row['isSent'];
                        $output[] = $object;

                        //update isRead in mailreciever
                        $arr = array();
                        foreach ($y as $yy) {
                            $sql2 = "SELECT profileID FROM tblusers WHERE profileID = '$yy->profileID' ";
                            $result2 = $conn->query($sql2);
                            if ($result2->num_rows > 0){
                                while($row2 = $result2->fetch_assoc()){
                                    $objectxxx = new stdClass();
                                    $objectxxx->profileID = $row2['profileID'];
                                    if($row2['profileID'] == $profileID){
                                        $objectxxx->isRead = 1;
                                    } else {
                                        $objectxxx->isRead = 0;
                                    }
                                    $arr[] = $objectxxx;
                                }
                            }
                        }

                        $sqlup = "UPDATE tblMail SET mailRecieverID = '".json_encode($arr, true)."' WHERE conversationID = '$conversationID'"; 
                        $conn2->query($sqlup);
                    }

                    echo json_encode($output);   
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }  
        }

        public function getSentMessageDetails($conn, $conn2, $mailID, $profileID){
            try {
                $output = array();
                $arr;
                $sql = "SELECT * FROM tblMail WHERE mailID = '$mailID' AND isSent = 1 AND isActive = 1";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $sender = array();
                        $reciever = array();
                        $object = new stdClass();
                        $object->mailID = $row['mailID'];
                        $object->conversationID = $row['conversationID'];
                        $object->mailTitle = $row['mailTitle'];
                        $object->mailContent = $row['mailContent'];
                        $object->mailCreatorID = $row['mailCreatorID'];
                        $x = json_decode($this->getProfileDetail($conn, $conn2, $row['mailSenderID'],0));
                        $objectx = new stdClass();
                        foreach ($x as $xx) {
                            $objectx->userName = $xx->userName;
                            $objectx->displayName = $xx->displayName;
                            $objectx->localAdd = $xx->localAdd;
                            $objectx->profileID = $xx->profileID;
                            $sender[] = $objectx;
                        }
                        $object->mailSenderID = $sender;
                        $object->senderID=$row['mailSenderID'];
                        $y = json_decode($row['mailRecieverID']);
                        foreach ($y as $yy) {
                            $tmp = json_decode($this->getProfileDetail($conn, $conn2, $yy->profileID, $yy->isRead));
                            foreach($tmp as &$yy){ 
                                $objecty = new stdClass();
                                $objecty->userName = $yy->userName;
                                $objecty->displayName = $yy->displayName;
                                $objecty->localAdd = $yy->localAdd;
                                $objecty->profileID = $yy->profileID;
                                $reciever[] = $objecty;
                            }
                            
                        }

                        $object->mailRecieverID = $reciever;
                        $object->dateCreated = $row['dateCreated'];
                        $object->dateSent = $row['dateSent'];
                        $object->isReply = $row['isReply'];
                        $object->isRead = $row['isRead'];
                        $object->isActive = $row['isActive'];
                        $object->isSent = $row['isSent'];
                        $output[] = $object;
                    }

                    echo json_encode($output);   
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }           
        }

        public function getDeletedMessageDetails($conn, $conn2, $mailID, $profileID){
            try {
                $output = array();
                $arr;
                $sql = "SELECT * FROM tblMail WHERE mailID = '$mailID' AND isSent = 1 AND isActive = 0";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $sender = array();
                        $reciever = array();
                        $object = new stdClass();
                        $object->mailID = $row['mailID'];
                        $object->conversationID = $row['conversationID'];
                        $object->mailTitle = $row['mailTitle'];
                        $object->mailContent = $row['mailContent'];
                        $object->mailCreatorID = $row['mailCreatorID'];
                        $x = json_decode($this->getProfileDetail($conn, $conn2, $row['mailSenderID'],0));
                        $objectx = new stdClass();
                        foreach ($x as $xx) {
                            $objectx->userName = $xx->userName;
                            $objectx->displayName = $xx->displayName;
                            $objectx->localAdd = $xx->localAdd;
                            $objectx->profileID = $xx->profileID;
                            $sender[] = $objectx;
                        }
                        $object->mailSenderID = $sender;
                        $object->senderID=$row['mailSenderID'];
                        $y = json_decode($row['mailRecieverID']);
                        foreach ($y as $yy) {
                            $tmp = json_decode($this->getProfileDetail($conn, $conn2, $yy->profileID, $yy->isRead));
                            foreach($tmp as &$yy){ 
                                $objecty = new stdClass();
                                $objecty->userName = $yy->userName;
                                $objecty->displayName = $yy->displayName;
                                $objecty->localAdd = $yy->localAdd;
                                $objecty->profileID = $yy->profileID;
                                $reciever[] = $objecty;
                            }
                            
                        }

                        $object->mailRecieverID = $reciever;
                        $object->dateCreated = $row['dateCreated'];
                        $object->dateSent = $row['dateSent'];
                        $object->isReply = $row['isReply'];
                        $object->isRead = $row['isRead'];
                        $object->isActive = $row['isActive'];
                        $object->isSent = $row['isSent'];
                        $output[] = $object;
                    }

                    echo json_encode($output);   
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }             
        }

        public function getReplyDetails($conn, $conn2, $conversationID, $profileID){
            try {
                $output = array();
                $arr;
                $sql = "SELECT * FROM tblMail WHERE conversationID = '$conversationID' AND isReply=1 AND isSent = 1 ORDER BY dateSent desc";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $sender = array();
                        $reciever = array();
                        $y = json_decode($row['mailRecieverID']);
                        $object = new stdClass();
                        $object->mailID = $row['mailID'];
                        $object->conversationID = $row['conversationID'];
                        $object->mailTitle = $row['mailTitle'];
                        $object->mailContent = $row['mailContent'];
                        $object->mailCreatorID = $row['mailCreatorID'];
                        $x = json_decode($this->getProfileDetail($conn, $conn2, $row['mailSenderID'],0));
                        $objectx = new stdClass();
                        foreach ($x as $xx) {
                            $objectx->userName = $xx->userName;
                            $objectx->displayName = $xx->displayName;
                            $objectx->localAdd = $xx->localAdd;
                            $objectx->profileID = $xx->profileID;
                            $sender[] = $objectx;
                        }
                        $object->mailSenderID = $sender;
                        $object->senderID=$row['mailSenderID'];
                        $y = json_decode($row['mailRecieverID']);
                        
                        foreach ($y as $yy) {
                            $tmp = json_decode($this->getProfileDetail($conn, $conn2, $yy->profileID, $yy->isRead));
                            foreach($tmp as &$yy){ 
                                $objecty = new stdClass();
                                $objecty->userName = $yy->userName;
                                $objecty->displayName = $yy->displayName;
                                $objecty->localAdd = $yy->localAdd;
                                $objecty->profileID = $yy->profileID;
                                $reciever[] = $objecty;
                            }
                            
                        }

                        $object->mailRecieverID = $reciever;
                        $object->dateCreated = $row['dateCreated'];
                        $object->dateSent = $row['dateSent'];
                        $object->isReply = $row['isReply'];
                        $object->isRead = $row['isRead'];
                        $object->isActive = $row['isActive'];
                        $object->isSent = $row['isSent'];
                        $output[] = $object;
                    }

                    echo json_encode($output);   
                } else {
                    echo "INFO_NOLIST";
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            } 
                        
        }

        public function getRecipientInfo($conn, $conn2, $conversationID, $profileID, $isReply){
            try {
                $output = array();
                $sql = "SELECT mailRecieverID FROM tblMail WHERE conversationID = '$conversationID' AND isReply = '$isReply'";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){ 
                        $arr = json_decode($row['mailRecieverID'], true);
                        foreach ($arr as $x) {
                            $mailRecieverID = $x['profileID'];
                            $sql2 = "SELECT * FROM tblusers WHERE profileID = '$mailRecieverID'";
                            $result2 = $conn->query($sql2);
                            if ($result2->num_rows > 0){
                                while($row2 = $result2->fetch_assoc()){
                                    $object = new stdClass();
                                    // Validate the email input
                                    $object->userName = $row2['userName'];
                                    $object->profileID = $row2['profileID'];
                                    $object->localAdd = $row2['userName'];
                                    // if (filter_var($row2['userName'], FILTER_VALIDATE_EMAIL)) {
                                    //     $object->localAdd = $row2['userName'];
                                    // } else {
                                    //     $object->localAdd = $row2['userName']."@paash.local";
                                    // }
                                    if($row2['profileID'] == $profileID){
                                        $object->displayName = 'me';
                                    } else {
                                        $object->displayName = $row2['userName'];
                                    } 
                                    $output[] = $object;                                  
                                }  
                            } else {
                                echo "INFO_NOLIST";
                            }
                            
                        }
                    }
                }

                echo json_encode($output); 
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function getReplyRecipientInfo($conn, $conn2, $mailID, $profileID, $isReply){
            try {
                $str = "";
                $output = array();
                $sql = "SELECT mailRecieverID FROM tblMail WHERE mailID = '$mailID' AND isReply = '$isReply'";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){ 
                        $arr = json_decode($row['mailRecieverID'], true);
                        foreach ($arr as $x) {
                            $mailRecieverID = $x['profileID'];
                            $sql2 = "SELECT * FROM tblusers WHERE profileID = '$mailRecieverID'";
                            $result2 = $conn->query($sql2);
                            if ($result2->num_rows > 0){
                                while($row2 = $result2->fetch_assoc()){
                                    $object = new stdClass();
                                    // Validate the email input
                                    $object->userName = $row2['userName'];
                                    $object->localAdd = $row2['userName'];
                                    // if (filter_var($row2['userName'], FILTER_VALIDATE_EMAIL)) {
                                    //     $object->localAdd = $row2['userName'];
                                    // } else {
                                    //     $object->localAdd = $row2['userName']."@paash.local";
                                    // }
                                    if($row2['profileID'] == $profileID){
                                        $object->displayName = 'me';
                                    } else {
                                        $object->displayName = $row2['userName'];
                                    } 
                                    $output[] = $object;   
                                    
                                }  
                            } else {
                                echo "INFO_NOLIST";
                            }
                        }
                    }
                }

                echo json_encode($output); 
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function getAllContacts($conn, $conn2, $profileID){
            try {
                $output = array();
                $arr = array();
                $sql = "SELECT * FROM tblcontacts WHERE ownerProfile = '$profileID'";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){ 
                        if($row["contactList"]!=""){    
                            $output = json_decode($row["contactList"]);
                        }   
                     }
                }
                //include all since this is admi account
                $sql2 = "SELECT * FROM tblgroups WHERE groupOwner = '$profileID' OR groupOwner = 'system'";
                $result2 = $conn2->query($sql2);
                if ($result2->num_rows > 0){
                    while($row2 = $result2->fetch_assoc()){ 
                        $arr[] = array("name"=>$row2["groupName"], "eMail" => $row2["groupMail"], "linkID" => $row2["groupProfile"]); 
                     }
                     $output = array_merge($output, $arr);
                }               
                echo json_encode($output);
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }
      
        public function deleteUploadedImage($conn, $src){
            try {
                // $response = FroalaEditor_Image::delete($src);
                $filename =  pathinfo($src);
                unlink('../UploadFiles/'.$filename['basename']);
                echo stripslashes(json_encode('Success'));
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }

        public function deleteUploadedFile($conn, $src){
            try {
                // $response = FroalaEditor_Image::delete($src);
                $filename =  pathinfo($src);
                unlink('../UploadFiles/'.$filename['basename']);
                echo stripslashes(json_encode('Success'));
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }
        
        public function composeNewMail($conn, $conn2, $isAnnouncement, $to, $from, $title, $msg) {
            try {
                $conveID = $this->generateRandomString();
                $arr = [];
                $setAnn = false;
        
                if ($isAnnouncement === "true") {
                    $sql = "SELECT profileID FROM tblusers WHERE isActive = 1";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $arr[] = (object)["profileID" => $row['profileID'], "isRead" => 0];
                    }
                    $setAnn = true;
                } else {
                    $specialGroups = [
                        "all@paash.com"      => "SELECT profileID FROM tblusers",
                        "students@paash.com" => "SELECT profileID FROM tblusers WHERE accntLevel = 2",
                        "faculty@paash.com"  => "SELECT profileID FROM tblusers WHERE accntLevel = 1",
                        "parents@paash.com"  => "SELECT profileID FROM tblusers WHERE accntLevel = 3"
                    ];
        
                    foreach ($to as $x) {
                        if (isset($specialGroups[$x])) {
                            $result = $conn->query($specialGroups[$x]);
                            while ($row = $result->fetch_assoc()) {
                                $arr[] = (object)["profileID" => $row['profileID'], "isRead" => 0];
                            }
                        } else {
                            // Check if it's a group
                            $stmt = $conn2->prepare("SELECT groupMembers FROM tblgroups WHERE groupMail = ?");
                            $stmt->bind_param("s", $x);
                            $stmt->execute();
                            $resgroup = $stmt->get_result();
        
                            if ($resgroup->num_rows > 0) {
                                while ($group = $resgroup->fetch_assoc()) {
                                    $members = json_decode($group["groupMembers"], true);
                                    foreach ($members as $item) {
                                        $arr[] = (object)["profileID" => $item['linkID'], "isRead" => 0];
                                    }
                                }
                            } else {
                                // Regular user
                                $stmt = $conn->prepare("SELECT profileID FROM tblusers WHERE userName = ?");
                                $stmt->bind_param("s", $x);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                while ($row = $res->fetch_assoc()) {
                                    $arr[] = (object)["profileID" => $row['profileID'], "isRead" => 0];
                                }
                            }
                        }
                    }
                }
        
                // Insert new mail
                $stmt = $conn2->prepare("
                    INSERT INTO tblMail (conversationID, mailTitle, mailContent, mailCreatorID, mailSenderID, mailRecieverID, dateCreated, dateSent, isRead, isActive, isAnnouncement)
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW(), 1, 1, ?)
                ");
                $json = json_encode($arr);
                $stmt->bind_param("ssssssi", $conveID, $title, $msg, $from, $from, $json, $setAnn);
                $stmt->execute();
        
                echo json_encode(["success" => true]);
            } catch (Exception $e) {
                header('HTTP/1.1 500 Internal Server Error');
                echo json_encode(["error" => "Failed to compose mail", "details" => $e->getMessage()]);
            }
        }


        public function replyToMail($conn, $conn2, $conversationID, $title, $to, $from, $msg){
            try {
                $arr = array();
                $isAnnouncement = "false";
                $sql = "SELECT 1 FROM tblMail WHERE conversationID = '$conversationID' AND isAnnouncement = 1";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0) {
                    $isAnnouncement = "true";
                }               
                if($isAnnouncement === "true"){
                    $sql = "SELECT profileID FROM tblusers WHERE isActive = 1";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()){
                            $object = new stdClass();
                            $object->profileID = $row['profileID'];
                            $object->isRead = 0;
                            $arr[] = $object;
                        }
                    }
                    $setAnn = true;
                } else {
                    foreach ($to as &$x) {
                        if($x == "all@paash.com"){
                            $sql = "SELECT profileID FROM tblusers";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()){
                                    $object = new stdClass();
                                    $object->profileID = $row['profileID'];
                                    $object->isRead = 0;
                                    $arr[] = $object;
                                }
                            }
                        } else if ($x == "students@paash.com"){
                            $sql = "SELECT profileID FROM tblusers WHERE accntLevel = 2";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()){
                                    $object = new stdClass();
                                    $object->profileID = $row['profileID'];
                                    $object->isRead = 0;
                                    $arr[] = $object;
                                }
                            }
                        } else if ($x == "faculty@paash.com"){
                            $sql = "SELECT profileID FROM tblusers WHERE accntLevel = 1";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()){
                                    $object = new stdClass();
                                    $object->profileID = $row['profileID'];
                                    $object->isRead = 0;
                                    $arr[] = $object;
                                }
                            }
                        } else if ($x == "parents@paash.com"){
                            $sql = "SELECT profileID FROM tblusers WHERE accntLevel = 3";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()){
                                    $object = new stdClass();
                                    $object->profileID = $row['profileID'];
                                    $object->isRead = 0;
                                    $arr[] = $object;
                                }
                            }
                        } else {
                            $sqlgroup = "SELECT * FROM tblgroups WHERE groupMail ='$x'";
                            $resgroup = $conn2->query($sqlgroup);
                            if ($resgroup->num_rows > 0){
                                while($group = $resgroup->fetch_assoc()){
                                    $arr2 = json_decode($group["groupMembers"],true); 
                                    foreach ($arr2 as $index => $item) {
                                        $object = new stdClass();
                                        $object->profileID = $item['linkID'];
                                        $object->isRead = 0;
                                        $arr[] = $object;
                                    }                            
                                }
                            } else {
                                $sql = "SELECT profileID FROM tblusers WHERE userName = '$x' ";
                                $result = $conn->query($sql);
                                while($row = $result->fetch_assoc()){
                                    if ($result->num_rows > 0){
                                        $object = new stdClass();
                                        $object->profileID = $row['profileID'];
                                        $object->isRead = 0;
                                        $arr[] = $object;
                                    }
                
                                }
                            }
                        }
                    }
                }

                $sqlupdate = "UPDATE tblMail SET mailRecieverID = '".json_encode($arr, true)."', dateSent = (SELECT NOW()) WHERE conversationID = '$conversationID' AND isReply = 0"; 
                $conn2->query($sqlupdate);
                $sqlinsert = "INSERT INTO tblMail (conversationID, mailTitle, mailContent, mailCreatorID, mailSenderID, mailRecieverID, dateCreated, dateSent, isReply, isRead, isActive)
                VALUES ('".$conversationID."', '".$title."', '".$msg."', '".$from."', '".$from."', '".json_encode($arr, true)."', (SELECT NOW()), (SELECT NOW()), 1, 1, 1)";
                if($conn2->query($sqlinsert) === TRUE) {
                    $sql = "SELECT * FROM tblMail WHERE conversationID = '$conversationID' AND isReply = 0";
                    $result = $conn2->query($sql);
                    if ($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $object = new stdClass();
                            $object->conversationID = $conversationID;
                            $object->mailRecieverID = json_encode($arr, true);
                            $arr2[] = $object;
                            echo json_encode($arr2);
                        }
                    }
                } else {
                    echo "ERR_CREATE";
                    throw new Exception("ERR_CREATE");
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }   
        }

        public function replyToAllMail($conn, $conn2, $conversationID, $from, $msg, $profileID){
            try {
                $sql = "SELECT mailRecieverID FROM tblMail WHERE conversationID = '$conversationID' AND isActive = 1 AND isReply = 0";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){ 
                        $ID = json_decode($row['mailRecieverID'], true);
                        foreach ($ID as &$x) {
                            if ($x['profileID']==$profileID) {
                                $x['isRead'] = 0;
                            }
                        }
                        $arr = json_encode($ID, true);
                        $sqlinsert = "INSERT INTO tblMail (conversationID, mailContent, mailCreatorID, mailSenderID, mailRecieverID, dateCreated, dateSent, isReply, isRead, isActive)
                        VALUES ('".$conversationID."', '".$msg."', '".$from."', '".$from."', '".$arr."', (SELECT NOW()), (SELECT NOW()), 1, 1, 1)";
                        if($conn2->query($sqlinsert) === TRUE) {
                            $sql = "SELECT mailID FROM tblMail WHERE conversationID = '$conversationID' AND isReply = 0";
                            $result = $conn2->query($sql);
                            if ($result->num_rows > 0){
                                while($row = $result->fetch_assoc()){
                                    echo $row['mailID'];
                                }
                            }
                        } else {
                            throw new Exception("ERR_CREATE");
                        }
                     }
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }     
        }

        public function deleteMail($conn2, $mailID) {
            try {
                $sql = "SELECT * FROM tblMail WHERE mailID = '$mailID' AND isActive = 1";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){ 
                        $update = "UPDATE tblMail SET isActive = 0 WHERE mailID = '$mailID'"; 
                        if($conn2->query($update) === TRUE) { 
                            return true;
                        }
                     }
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function restoreMail($conn2, $mailID) {
            try {
                $sql = "SELECT * FROM tblMail WHERE mailID = '$mailID' AND isActive = 0";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){ 
                        $update = "UPDATE tblMail SET isActive = 1 WHERE mailID = '$mailID'"; 
                        if($conn2->query($update) === TRUE) { 
                            return true;
                        }
                     }
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }

        public function deleteReply($conn2, $mailID) {
            try {
                $sql = "SELECT * FROM tblMail WHERE mailID = '$mailID' AND isActive = 1";
                $result = $conn2->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){ 
                        $update = "UPDATE tblMail SET isActive = 0 WHERE mailID = '$mailID'"; 
                        if($conn2->query($update) === TRUE) { 
                            return true;
                        }
                     }
                }
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }              
        }
        #endregion

        #region Bulk Uploader
        public function uploadFileToDB($conn, $form, $data) {
            if($form=="faculty"){
               $this-> uploadFaculty($conn, $data);
            } else if ($form=="student"){
                $this-> uploadStudent($conn, $data);
            }
        }

        public function uploadFaculty($conn, $data) {
            try {
                $arr = array();
                $randPassword = "";
                foreach($data as &$x){
                    if(!empty($x['fname']) || !empty($x['lname'])){
                        $object = new stdClass();
                        if($x["uname"] == ""){
                            $object ->name  = $x['fname'] . " " . $x['lname'] . "";
                        } else {
                            if(str_contains($x["uname"], '@paash.com')){
                                $object ->name  = $x["uname"];
                            } else {
                                $object ->name  = $x['fname'] . " " . $x['lname'] . "";
                            }
                        }
                         if($x["pass"] == ""){
                            $randPassword = 'P@$$w0rd!';
                        } else {
                            $randPassword = $x["pass"] ;
                        }
                        
                        do{
                            $profileID = $this->generateRandomString();
                            $sqlTest = "SELECT * FROM tblusers WHERE profileID = '" . $profileID . "'";
                            $resultTest = $conn->query($sqlTest);
                        } while($resultTest->num_rows > 0);
                        $object->profileID = $profileID;
                        //check if strandID exist
                        $sqlstrand = "SELECT * FROM tblstrands WHERE shortName = '".$x['strand']."' AND isActive = 1";
                        $resStrandID = $conn->query($sqlstrand);
                        if ($resStrandID->num_rows > 0){
                            while($rowStrandID = $resStrandID->fetch_assoc()){
                                if (!filter_var($x['email'], FILTER_VALIDATE_EMAIL)) {
                                    $object->status = 0;//failed
                                    $object->err = "Invalid email format";
                                } else {
                                    $sql = "SELECT * FROM tblusers WHERE userName ='".$x["uname"]."'";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0){
                                        $object->status = 0;//failed
                                        $object->err = "Username already exist";  
                                    } else {
                                        $sql = "SELECT * FROM tblfacultyinfo WHERE eMail ='".$x["email"]."' AND isActive = 1";
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0){
                                            $object->status = 0;//failed
                                            $object->err = "Email already exist"; 
                                        } else {
                                            $sql = "INSERT INTO tblfacultyinfo (firstName, middleName, lastName, gender, eMail, contactNo, profileID, ID, strandID)
                                            VALUES ('".validate($x["fname"])."', '".validate($x["mname"])."', '".validate($x["lname"])."', '".validate($x["gender"])."','".validate($x["email"])."', '".validate($x["contact"])."', '".validate($profileID)."', '".$x["id"]."', '".$rowStrandID["strandID"]."')";  
                                            $sqlLogin = "INSERT INTO tblusers (userName, passWord, accntLevel, profileID)
                                            VALUES ('".$this->createUserName($conn, $x['fname'],$x['mname'],$x['lname'])."', '". hash('md5', $randPassword) ."', 1, '" .$profileID . "')";
                                            //create login masterlist
                                            $sqlLogin2 = "INSERT INTO tbluserex (userName, passWord, profileID)
                                            VALUES ('".$this->createUserName($conn, $x['fname'],$x['mname'],$x['lname'])."', '". $randPassword ."', '" .$profileID . "')"; 
                                            if($conn->query($sqlLogin) === TRUE && $conn->query($sqlLogin2) === TRUE && $conn->query($sql) === TRUE){ 
                                                $object->status = 1;//success
                                                $object->err = "Faculty created";
                                                $this->sendOutwardMail($x["fname"], $x["lname"], $x["email"], $this->createUserName($conn, $x['fname'],$x['mname'],$x['lname']), $randPassword);
                                            } else{ 
                                                $object->status = 0;//failed
                                                $object->err = "Faculty creation failed"; 
                                            }
                                        }
                                    }
                                }                                
                            }
                        } else {
                            $object->status = 0;//failed
                            $object->err = "StrandID does not exist.";   
                        }
                        $arr[] = $object;
                    }
                }
                echo json_encode($arr);
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }             
        }

        public function getCurrentSY($conn){
            $sql = "SELECT schoolyearID FROM tblschoolyear WHERE isCurrentSY = 1 AND isActive =1";
            $result = $conn->query($sql);
            if ($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    return $row['schoolyearID'];
                 }
            }
        }

        public function getClassName($conn, $str){
            $sql = "SELECT classProfile FROM tblclass WHERE className = '$str' AND isActive =1";
            $result = $conn->query($sql);
            if ($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    return $row['classProfile'];
                 }
            } else {
                return null;
            }
        }      

        public function uploadStudent($conn, $data) {
            try {
                $arr = array();
                foreach($data as &$x){
                    if(!empty($x['fname']) || !empty($x['lname'])) {
                        $object = new stdClass();
                        $object ->name  = $x['fname'] . " " . $x['lname'] . "";
                        
                        $randPassword = $this->generateRandomString();

                        do{
                            $profileID = $this->generateRandomString();
                            $sqlTest = "SELECT * FROM tblusers WHERE profileID = '" . $profileID . "'";
                            $resultTest = $conn->query($sqlTest);
                        } while($resultTest->num_rows > 0);
                        $object->profileID = $profileID;
                        do{
                            $guardianProfileID = $this->generateRandomString();
                            $sqlTest = "SELECT * FROM tblusers WHERE profileID = '" . $guardianProfileID . "'";
                            $resultTest = $conn->query($sqlTest);
                        } while($resultTest->num_rows > 0);
        
                        //overwrite random password for now
                        $randPassword = 'P@$$w0rd!';
                        $randGuardianPassword = 'P@$$w0rd!';

                        //check if strandID exist
                        $sqlstrand = "SELECT * FROM tblstrandlist WHERE strandID = '".$x['strand']."' AND gradelevelID = '".$x['grade']."' AND isActive = 1";
                        $resStrandID = $conn->query($sqlstrand);
                        if ($resStrandID->num_rows > 0) { 
                            //check if semester exist
                            $sqlsemester = "SELECT * FROM tblsemester WHERE semesterID = '".$x['semester']."'";
                            $resSemester = $conn->query($sqlsemester);
                            if ($resSemester->num_rows > 0) {
                                //check if gradelevel exist
                                $sqlgrade = "SELECT * FROM tblgradelevel WHERE gradelevelID = '".$x['grade']."' AND isActive = 1";
                                $resGrade = $conn->query($sqlgrade);
                                if ($resGrade->num_rows > 0){
                                    //check if class exist
                                    $sqlclass = "SELECT * FROM tblclass WHERE className = '".$x['section']."' AND gradelevelID = '".$x['grade']."' AND strandID ='".$x['strand']."' AND isActive = 1";
                                    $resclass = $conn->query($sqlclass);
                                    if ($resclass->num_rows > 0) { 
                                        if (!filter_var($x['email'], FILTER_VALIDATE_EMAIL)) {
                                            $object->status = 0;//failed
                                            $object->err = "Invalid email format";
                                        } else {
                                            if (!filter_var($x['gemail'], FILTER_VALIDATE_EMAIL)) {
                                                $object->status = 0;//failed
                                                $object->err = "Invalid guardian/parent email format";
                                            } else {
                                                $sqlselectemail = "SELECT eMail FROM tblstudentlist WHERE eMail = '".$x['email']."' AND isActive = 1";
                                                $resultemail = $conn->query($sqlselectemail);
                                                if ($resultemail->num_rows > 0) {
                                                    $object->status = 0;//failed
                                                    $object->err = "Email already exist";
                                                } else {
                                                    $sqlselectusername = "SELECT userName FROM tblusers WHERE userName = '".$this->createUserName($conn, $x['fname'],$x['mname'],$x['lname'])."' AND isActive = 1";
                                                    $resultusername = $conn->query($sqlselectusername); 
                                                    if ($resultusername->num_rows > 0) {
                                                        $object->status = 0;//failed
                                                        $object->err = "Username already exist";
                                                    } else {
                                                        $sqlselectname = "SELECT firstName, lastName FROM tblstudentlist WHERE firstName = '".$x['fname']."' AND lastName = '".$x['lname']."' AND isActive = 1";
                                                        $resultname = $conn->query($sqlselectname); 
                                                        if ($resultname->num_rows > 0) {
                                                            $object->status = 0;//failed
                                                            $object->err = "Duplicate entry";
                                                        } else {
                                                            if(is_numeric($x['gcontact'])){
                                                                $sqlGuardian = "SELECT userName, profileID FROM tblusers WHERE userName = '".$x['gemail']."' AND isActive = 1";
                                                                $resultGuardian = $conn->query($sqlGuardian); 
                                                                if ($resultGuardian->num_rows > 0) { 
                                                                    while($rowGuardian = $resultGuardian->fetch_assoc()) { 
                                                                        //if profile already exist, get profile ID instead
                                                                        $guardianProfileID = $rowGuardian["profileID"];
                                                                    }  
                                                                } else {
                            
                            
                                                                    //create guardian login credentials
                                                                    $sqlLoginG = "INSERT INTO tblusers (userName, passWord, accntLevel, profileID)
                                                                    VALUES ('".$x['gemail']."', '". hash('md5', $randGuardianPassword) ."', 3, '" .$guardianProfileID . "')"; 

                                                                    //create guardian login masterlist
                                                                    $sqlLoginG2 = "INSERT INTO tbluserex (userName, passWord, profileID)
                                                                    VALUES ('".$x['gemail']."', '". $randGuardianPassword ."', '" .$guardianProfileID . "')"; 

                                                                    //create guardian profile
                                                                    $sqlInsertGuardian = "INSERT INTO tblguardianprofile (guardianProfileID, prefix, guardianFName, guardianMName, guardianLName, guardianGender, guardianEmail, guardianContactNo)
                                                                    VALUE ('".$guardianProfileID."', '".$x['gprefix']."', '".$x['gfname']."', '".$x['gmname']."', '".$x['glname']."', '".$x['ggender']."', '".$x['gemail']."', '".$x['gcontact']."')";
                                                                    $conn->query($sqlLoginG);
                                                                    $conn->query($sqlLoginG2);
                                                                    $conn->query($sqlInsertGuardian);
                                                                    
                                                                    $this->sendOutwardMail($x["gfname"], $x["glname"], $x["gemail"], $x['gemail'], $randGuardianPassword);
                                                                }

                                                                $classMemberCount = 0;
                                                                $maxCount = 0;

                                                                $isMax = false;
                                                                $className = null;

                                                                //Count members
                                                                $sqlclass2 = "SELECT * FROM tblclass WHERE classProfile = '".$this->getClassName($conn, $x['section'])."'";
                                                                $resclass2 = $conn->query($sqlclass2); 
                                                                if ($resclass2->num_rows > 0) { 
                                                                    while($rowclass2 = $resclass2->fetch_assoc()) { 
                                                                        $classMemberCount = $rowclass2["enrolledStudent"];
                                                                        $maxCount = $rowclass2["maxStudent"];
                                                                    }
                                                                }       
                                                                
                                                                if($classMemberCount >= $maxCount){
                                                                    $isMax = true;
                                                                    $className = null;
                                                                } else {
                                                                    $classMemberCount++;
                                                                    $className = $x['section'];
                                                                    $sqlupdateclass = "UPDATE tblclass SET enrolledStudent = '$classMemberCount' WHERE classProfile = '".$this->getClassName($conn, $x['section'])."'";    
                                                                    $conn->query($sqlupdateclass);  
                                                                }
                                                                                                        

                                                                //add student to list
                                                                $sql = "INSERT INTO tblstudentlist (studentType, firstName, middleName, lastName, gender, eMail, profileID)
                                                                VALUES ('Regular', '".$x['fname']."', '".$x['mname']."', '".$x['lname']."', '".$x['gender']."','".$x['email']."', '".$profileID."')";  
                                                                //create student profile
                                                                $sqlinsertprofile = "INSERT INTO tblstudentprofile (profileID, strandID, schoolyearID, semesterID, gradelevelID, classID, guardianID, guardianRelation, LRN)
                                                                VALUE ('".$profileID."', '".$x['strand']."', '".$this->getCurrentSY($conn)."', '".$x['semester']."', '".$x['grade']."', '".$this->getClassName($conn, $x['section'])."', '".$guardianProfileID."', '".$x['grelation']."', '".$x['LRN']."')";
                                                                //create student login credentials
                                                                $sqlLogin = "INSERT INTO tblusers (userName, passWord, accntLevel, profileID)
                                                                VALUES ('".$this->createUserName($conn, $x['fname'],$x['mname'],$x['lname'])."', '". hash('md5', $randPassword) ."', 2, '" .$profileID . "')"; 
                            
                                                                //create login masterlist
                                                                $sqlLogin2 = "INSERT INTO tbluserex (userName, passWord, profileID)
                                                                VALUES ('".$this->createUserName($conn, $x['fname'],$x['mname'],$x['lname'])."', '". $randPassword ."', '" .$profileID . "')"; 

                                                                if($isMax==false){
                                                                    //set student class
                                                                    $sqlinsertclass ="INSERT INTO tblclassmember (classProfile, studentProfile) 
                                                                    VALUES ('".$this->getClassName($conn, $x['section'])."', '" .$profileID . "')";
                                                                    if($conn->query($sqlinsertclass) === TRUE){
                                                                        //MMODIFY
                                                                        $sqlselect = "SELECT * FROM tblclasssched AS sched 
                                                                        INNER JOIN tblsubjectlist AS subjectlist ON subjectlist.subjectlistID = sched.subjectID
                                                                        INNER JOIN tblsubjects AS subjects ON subjects.subjectID = subjectlist.subjectID
                                                                        WHERE subjectlist.strandID = '".$x['strand']."' 
                                                                        AND subjectlist.gradelevelID = '".$x['grade']."' 
                                                                        AND subjectlist.schoolyearID = '".$this->getCurrentSY($conn)."' 
                                                                        AND sched.classProfile = '".$this->getClassName($conn, $x['section'])."'
                                                                        AND subjectlist.isActive = 1";
                                        
                                                                        $result = $conn->query($sqlselect);
                                                                        if ($result->num_rows > 0) {
                                                                            while($row = $result->fetch_assoc()){
                                                                                $resultquarter = $conn->query("SELECT * FROM tblquarter WHERE semesterID = '".$x['semester']."' AND isActive = 1");
                                                                                if ($resultquarter->num_rows > 0) {
                                                                                    while($quarter = $resultquarter->fetch_assoc()){
                                                                                        $sqlinsertgradesheet = "INSERT INTO tblgradesheet (schoolyearID, gradelevelID, semesterID, strandID, subjectID, facultyID, studentID, quarterID)
                                                                                        VALUES ('".$this->getCurrentSY($conn)."', '".$x['grade']."', '".$x['semester']."', '".$x['strand']."', '".$row['subjectID']."', '".$row['facultyProfile']."', '".$profileID."', '".$quarter['quarterID']."')";
                                                                                        $conn->query($sqlinsertgradesheet);
                                                                                    }
                                                                                }
                                                        
                                                                            } 
                                                                        }
                                                                    }
                                                                }
                        
                                                                if($conn->query($sql) === TRUE &&
                                                                $conn->query($sqlLogin) === TRUE &&
                                                                $conn->query($sqlLogin2) === TRUE &&
                                                                $conn->query($sqlinsertprofile) === TRUE){ 
                                                                    $object->status = 1;//success
                                                                    if($isMax) {
                                                                        $object->err = "Student was imported but was not enrolled. Max student for class was attained.";
                                                                    } else {
                                                                        $object->err = "Student enrolled.";
                                                                    }
                                                                    $this->sendOutwardMail($x["fname"], $x["lname"], $x["email"], $this->createUserName($conn, $x['fname'],$x['mname'],$x['lname']), $randPassword);
                                                                } else { 
                                                                    $object->status = 0;//failed
                                                                    $object->err = "Enrollment failed. Please try again.";
                                                                } 
                                                                                                
                                                            } else {
                                                                $object->status = 0;//failed
                                                                $object->err = "Wrong data format";
                                                            }
                                                        }
                                                    }
                                                }                                               
                                            }
                                        }

                                    } else {
                                        $object->status = 0;//failed
                                        $object->err = "Class does not exist";
                                    }
                                } else {
                                    $object->status = 0;//failed
                                    $object->err = "Grade/Level does not exist"; 
                                }

                            } else {
                                $object->status = 0;//failed
                                $object->err = "Semester does not exist"; 
                            }

                        } else {
                            $object->status = 0;//failed
                            $object->err = "StrandID does not exist.";
                        }   
                        $arr[] = $object;  
                    }     
                }
                echo json_encode($arr);
            }
            catch (Exception $err) {
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }            
        }
        #endregion

        #region mailer
        public function sendOutwardMail($fn, $ln, $email, $username, $password){
            try {
                require '../../../vendor/autoload.php';
                $mail = new PHPMailer(true);
                
                // SMTP server configuration
                $mail->isSMTP();
                $mail->SMTPDebug = 0; // Set to 0 to disable debug output in production
                $mail->Host = 'smtp.hostinger.com';
                $mail->Port = 587;
                $mail->SMTPSecure = 'tls'; // TLS for port 587
                $mail->SMTPAuth = true;
                $mail->Username = 'paash@paash.online'; // Your Hostinger email
                $mail->Password = 'D3f@ultAdm1n!!!';    // Your Hostinger password
            
                // Sender and recipient settings
                $mail->setFrom('paash@paash.online', 'PAASH');
                $mail->addReplyTo('no-reply@paash.online', 'No-Reply');
                $mail->addAddress($email, 'Receiver Name');
            
                // Email content
                $mail->isHTML(true); // Set to true if sending HTML
                $mail->Subject = 'PAASH Registration Update';
                $mail->Body    = '<b>Welcome to PAASH '. $fn . ' ' . $ln .'!</b><br/><br/>Your have been successfully registered to paash.online . </br>Below is you login credentials;<br/><b>Username</b>: '. $username .'<br/><b>Password</b>: ' . $password . ' . <br/></br> Click here to <a href="paash.online" target="_blank"> here </a> to login.';
            
                // Send the message
                $mail->send();
                return true;
            } catch (Exception $err){
                if(isset($profileID)){
                    $err->profileID = $profileID;
                } else {
                    $err->profileID = "paash.system.admin.function:".__FUNCTION__;
                }
                header ('HTTP/1.1 500 Internal Server Error');
                echo $this->errorMessage($conn, $err);
            }
        }
        #endregion
        
       
    }
?>