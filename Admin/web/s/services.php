<?php
    class client implements i {
        #region Global


        function validate($data) {
            return htmlspecialchars(stripslashes(trim($data)));
        }

        public function generateRandomString(int $length = 20): string
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $maxIndex = strlen($characters) - 1;

            $result = '';
            for ($i = 0; $i < $length; $i++) {
                $result .= $characters[random_int(0, $maxIndex)];
            }

            return $result;
        }

        public function errorMessage($conn, $err) {
            //error message
            // $errorMsg = str_replace("'","","Error on line ".$err->getLine()." in ".$err->getFile()
            // .": ".$err->getMessage().".");
            // $trace = str_replace("'","",$err->getTraceAsString());
            // $logs = "INSERT INTO tblsystemlogs (logTitle, log, trace, dateLog, loggedBy)
            // VALUES ('".$err->getCode()."', '".$errorMsg."', '".$trace."', (SELECT NOW()), '".$err->profileID."')";
            // if($conn->query($logs)===TRUE){
            //     return "A <b class='text-danger'>System Error</b> has occurred. Please try again later. If error still persist, please report to Web Developers.";
            // }
            return $err;
        } 
    
        
        public function checkLoginIsValid($conn, $profileID, $ip){
            try {
                // SQL Server uses TOP instead of LIMIT
                $stmt = $conn->prepare("EXEC sp_verifyLoginStatus :profileID");
                $stmt->bindParam(':profileID', $profileID, PDO::PARAM_STR);
                $stmt->execute();

                if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    http_response_code(200);
                    echo json_encode([
                        "status" => "SUCCESS",
                        "message" => "Valid login",
                        "user" => $user
                    ]);
                } else {
                    http_response_code(401);
                    echo json_encode([
                        "status" => "VERIFICATION_FAILED",
                        "message" => "Unable to verify login, please login again."
                    ]);
                }

            } catch (PDOException $err) {
                http_response_code(500);
                echo json_encode([
                    "status" => "HTTP/1.1 500 Internal Server Error",
                    "message" => $err->getMessage()
                ]);
            }         
        }        

        #region Dashboard
        public function getCurrentActiveSchoolyear($conn, $schoolyearID){
            try {
                $output = array();
                $sql = "SELECT * FROM tbl_schoolyear WHERE isCurrentSY = 1";

                $stmt = $conn->prepare($sql);
                $stmt->execute();

                $output = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode($output);
            }
            catch (Exception $err) {
                http_response_code(500);
                echo json_encode([
                    "status" => "HTTP/1.1 500 Internal Server Error",
                    "message" => $err->getMessage()
                ]);
            }            
        }

        public function getCurrentSYActiveStrandAndDetails($conn, $schoolyearId){
            try {
                $output = array();
                $sql = "SELECT * FROM tbl_strands ss
                INNER JOIN tbl_strandlist sl ON ss.strandID = sl.strandID
                WHERE ss.isActive = 1";
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                $output = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode($output);
            }
            catch (Exception $err) {
                http_response_code(500);
                echo json_encode([
                    "status" => "HTTP/1.1 500 Internal Server Error",
                    "message" => $err->getMessage()
                ]);
            }            
        }

        public function getNewsForDashboard($conn){
            try {
                $output = array();
                $sql = "SELECT * FROM tbl_mail WHERE isAnnouncement = 1";
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                $output = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode($output);
            }
            catch (Exception $err) {
                http_response_code(500);
                echo json_encode([
                    "status" => "HTTP/1.1 500 Internal Server Error",
                    "message" => $err->getMessage()
                ]);
            }            
        }   
        
        public function getUnreadMailForDashboard($conn, $profileID){
            try {
                $output = [];
                $profileCache = [];

                // 1. Fetch mails where the current user is a receiver
                $sql = "
                    SELECT m.*
                    FROM tbl_mail m
                    CROSS APPLY OPENJSON(m.mailRecieverID)
                    WITH (
                        profileID NVARCHAR(50) '$.profileID',
                        isRead BIT '$.isRead'
                    ) AS receivers
                    WHERE m.isReply = 0
                    AND m.isSent = 1
                    AND m.isAnnouncement = 0
                    AND m.isActive = 1
                    AND receivers.profileID = :profileID
                ";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':profileID', $profileID, PDO::PARAM_INT);
                $stmt->execute();

                $mails = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (empty($mails)) {
                    echo json_encode([]);
                    return;
                }

                // 2. Cache all unique profiles involved in these mails
                $profileIDs = [];
                foreach ($mails as $row) {
                    $profileIDs[] = $row['mailSenderID'];
                    $receivers = json_decode($row['mailRecieverID'], true);
                    foreach ($receivers as $r) {
                        $profileIDs[] = $r['profileID'];
                    }
                }
                $profileIDs = array_unique($profileIDs);

                // Fetch all profiles at once (replace this with your real method)
                foreach ($profileIDs as $pid) {
                    $profileCache[$pid] = json_decode($this->getProfileDetail($conn, $conn, $pid, 0));
                }

                // 3. Build the output
                foreach ($mails as $row) {
                    $conversationID = $row['conversationID'];

                    if (!isset($output[$conversationID])) {
                        $output[$conversationID] = (object)[
                            'conversationID' => $conversationID,
                            'isAnnouncement' => $row['isAnnouncement'],
                            'subDetails' => []
                        ];
                    }

                    // Receiver profiles from cached profiles
                    $receivers = json_decode($row['mailRecieverID'], true);
                    $receiverProfiles = [];
                    foreach ($receivers as $r) {
                        if (isset($profileCache[$r['profileID']])) {
                            foreach ($profileCache[$r['profileID']] as $d) {
                                $receiverProfiles[] = (object)[
                                    'userName' => $d->userName,
                                    'displayName' => $d->displayName,
                                    'localAdd' => $d->localAdd,
                                    'profileID' => $d->profileID,
                                    'isRead' => $r['isRead'] // use the isRead from JSON
                                ];
                            }
                        }
                    }

                    $output[$conversationID]->subDetails[] = (object)[
                        'mailID' => $row['mailID'],
                        'conversationID' => $conversationID,
                        'mailTitle' => $row['mailTitle'],
                        'mailContent' => $row['mailContent'],
                        'mailCreatorID' => $row['mailCreatorID'],
                        'mailSenderID' => $profileCache[$row['mailSenderID']],
                        'senderID' => $row['mailSenderID'],
                        'mailRecieverID' => $receiverProfiles,
                        'dateCreated' => $row['dateCreated'],
                        'dateSent' => $row['dateSent'],
                        'isReply' => $row['isReply'],
                        'isRead' => $row['isRead'],
                        'isActive' => $row['isActive'],
                        'isSent' => $row['isSent']
                    ];
                }

                echo json_encode(array_values($output));

            } catch (PDOException $err) {
                http_response_code(500);
                echo json_encode([
                    "status" => "ERROR",
                    "message" => $err->getMessage()
                ]);
            }
        }
        #endregion  
    
        #region Subjects
        public function getSubjectList($conn, $schoolyearID){
            try {
                $output = array();
                $sql = "SELECT * FROM tbl_subjects ss
                INNER JOIN tbl_strands sl ON ss.strandID = sl.strandID
                WHERE ss.isActive = 1";
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                $output = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode($output);
            }
            catch (Exception $err) {
                http_response_code(500);
                echo json_encode([
                    "status" => "HTTP/1.1 500 Internal Server Error",
                    "message" => $err->getMessage()
                ]);
            }            
        }
        #endregion 
    }
?>