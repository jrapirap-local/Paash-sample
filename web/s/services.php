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


        public function errorMessage(PDO $conn, Throwable $err, string $profileID = null): string
        {
            try {
                // Build clean error message
                $errorMsg = sprintf(
                    "Error on line %d in %s: %s",
                    $err->getLine(),
                    $err->getFile(),
                    $err->getMessage()
                );

                $trace = $err->getTraceAsString();

                $sql = "
                    INSERT INTO tblsystemlogs
                        (logTitle, log, trace, dateLog, loggedBy)
                    VALUES
                        (:logTitle, :log, :trace, GETDATE(), :loggedBy)
                ";

                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':logTitle' => (string)$err->getCode(),
                    ':log'      => $errorMsg,
                    ':trace'    => $trace,
                    ':loggedBy' => $profileID
                ]);

            } catch (Throwable $loggingError) {
                // Never let logging crash the application
                error_log($loggingError->getMessage());
            }

            // Generic message for users (no sensitive data)
            return "A <b class='text-danger'>System Error</b> has occurred. Please try again later. If the error persists, please contact the system administrator.";
        }

        
        public function connectToDB(){
            $serverName = "localhost\\SQLEXPRESS"; // double backslash
            $dbName = "projectDB";
            $username = "project";
            $password = "2woWayP023r!";

            try {
                $conn = new PDO("sqlsrv:Server=$serverName;Database=$dbName", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo json_encode([
                    "status" => "success",
                    "message" => "Database connected"
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Database connection failed" . $e
                ]);
            }       
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
                    "status" => "ERROR",
                    "message" => $err->getMessage()
                ]);
            }         
        }        

        #region Login
        public function getLoginInformation($conn, $profileID, $uname, $pass, $ip){
            try {

                $hash = password_hash($pass, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("EXEC sp_getLoginInformation_uname :uname");
                $stmt->bindParam(':uname', $uname, PDO::PARAM_STR); // bind the value
                $stmt->execute();

                if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    // Verify password (IMPORTANT)
                    if (password_verify($pass, $user['password'])) {
                        try {
                            $upd = $conn->prepare("EXEC sp_getLoginInformation_update_ip :ip, :profileID");
                            $upd->bindParam(':ip', $ip, PDO::PARAM_STR);
                            $upd->bindParam(':profileID', $user['profileID'], PDO::PARAM_STR);
                            $upd->execute();

                            // Remove password before returning user
                            unset($user['passWord']); 

                            http_response_code(200);
                            echo json_encode([
                                "status" => "SUCCESS",
                                "user" => $user
                            ]);

                        } catch (PDOException $e) {
                            http_response_code(500);
                            echo json_encode([
                                "status" => "ERROR",
                                "message" => $e->getMessage()
                            ]);
                        }

                    } else {
                        http_response_code(401);
                        echo json_encode([
                            "status" => "INVALID_CREDENTIALS"
                        ]);
                    }

                } else {
                    http_response_code(401);
                    echo json_encode([
                        "status" => "INVALID_CREDENTIALS"
                    ]);
                }
            }
            catch (Exception $err) {
                http_response_code(500);
                echo json_encode([
                    "status" => "ERROR",
                    "message" => $err
                ]);
            }            
        }
        #endregion  
    }
?>