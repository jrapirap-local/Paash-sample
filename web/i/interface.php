<?php
    interface i {     
        public function connectToDB(); 
        public function checkLoginIsValid($conn, $profileID, $ip);
        #region Login
        public function getLoginInformation($conn, $profileID, $uname, $pass, $ip);

    }
?>