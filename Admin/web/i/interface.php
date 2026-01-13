<?php
    interface i {     
        public function checkLoginIsValid($conn, $profileID, $ip);
        public function getCurrentActiveSchoolyear($conn, $schoolyearID);
        public function getCurrentSYActiveStrandAndDetails($conn, $schoolyearId);
        public function getNewsForDashboard($conn); 
        public function getUnreadMailForDashboard($conn, $profileID); 
        public function getSubjectList($conn, $schoolyearID);
    }
?>