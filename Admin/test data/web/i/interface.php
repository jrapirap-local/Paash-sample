<?php
    interface i {
        public function checkLoginIsValid($conn, $profileID, $ip);
        public function logout($conn, $profileID);


        public function getNotification($conn, $conn2, $profileID);
        public function checkAccountStat($conn, $profileID);

        public function getActiveStudentCount($conn, $schoolyearID);
        public function getActiveFacultyCount($conn);
        public function getActiveStrandCount($conn, $schoolyearID);
        public function getCurrentActiveSchoolyear($conn, $schoolyearID);
        
        public function closeSY($conn, $schoolyearID);

        public function getStrandsList($conn, $schoolyearID);
        public function getSubjectList($conn, $schoolyearID, $strandID, $semesterID, $gradelevelID);
        public function generateColapsePanel($strandlistID, $strandID, $schoolyearID, $gradelevelID, $description);
        public function getAcademicTrack($conn);
        public function getStrands($conn, $trackID);
        public function getSchoolyear($conn);
        public function getSemester($conn);
        public function getGradelevel($conn);
        public function createStrand($conn, $trackID, $strandID, $schoolyearID, $semesterID, $gradelevelID, $desc);
        public function deleteStrand($conn, $strandID, $schoolyearID, $gradelevelID);
        public function getStrandName($conn, $strandID);
        public function getSubjects($conn, $semesterID, $gradelevelID, $schoolyearID, $strandID);
        public function addNewsubject($conn, $object);
        public function removeSubjects($conn, $subjectlistID);
        public function clearSemester($conn, $strandID, $schoolyearID, $semesterID, $gradelevelID);

        public function getFaculty($conn, $classID);
        public function getFacultyAdmin($conn);
        public function getStrandsAndStrandList($conn, $trackID);
        public function registerFaculty($conn, $object);
        public function validateEmail($conn, $eMail);
        public function validateUsername($conn, $username);
        public function getFacultyInfo($conn, $profileID);
        public function getStrandPlus($conn);
        public function getSubjectPerStrand($conn, $strandID, $profileID);
        public function getRegisteredSubjectPerFaculty($conn, $profileID);
        public function addSubjectToFaculty($conn, $subjectID, $strandID, $profileID);
        public function removeSubjectFromFaculty($conn, $subjectID, $profileID);
        public function activateFaculty($conn, $profileID);
        public function deactivateFaculty($conn, $profileID);
        // public function resetPassword($conn, $profileID);
        public function saveFacultyUpdate($conn, $object);
        public function addsubSubject($conn, $object);
        public function updateLineSchedule($conn, $object);

        public function getCurrentSchoolyear($conn);
        public function addSchoolYear($conn, $startYear, $endYear, $startDate, $endDate, $chkActive);
        public function getSchoolyearDetails($conn, $schoolyearID);
        public function updateSchoolYear($conn, $startYear, $endYear, $startDate, $endDate, $chkActive, $schoolyearID);

        public function showSubjectList($conn);
        public function getStrandsForSubject($conn);
        public function getSubjectInfo($conn, $subjectID);
        public function getTrackforSubjectStrand($conn);
        public function updateSubjects($conn, $object);
        public function deactivateSubjects($conn, $subjectID);
        public function activateSubjects($conn, $subjectID);
        public function createNewSubject($conn, $object);

        public function getClassList($conn, $strandID, $schoolyearID, $gradelevelID);
        public function createClass($conn, $conn2, $object);
        public function deleteClass($conn, $conn2, $classProfile);

        public function getClassInfo($conn, $classProfile);
        public function getAvailableSubjects($conn, $object);
        public function getFacultyForClass($conn);
        public function getFacultyForSelectedSubject($conn, $subjectID);
        public function generateSchedule($conn, $object);
        public function saveSchedule($conn, $object);
        public function getSchedforEdit($conn, $object);
        public function clearSchedules($conn, $classID, $semesterID);
        public function removeShedule($conn, $schedID);
        public function removeMember($conn, $profileID, $classID);
        public function getStudentListForEnroll($conn, $classID);
        public function enrollStudentToClass($conn, $classID, $profileIDs);
        public function removeStudentAccount($conn, $classID, $studentID);
        public function getProfileEmail($conn, $profileID);

        public function getScheduleListStudent($conn, $classProfile);
        public function getScheduleList($conn, $classProfile, $semesterID);
        public function saveModification($conn, $conn2, $object);
        public function getMembers($conn, $classProfile);

        public function getStudentList($conn);
        public function getActiveStrandsPerTrack($conn, $object);
        public function getFilteredClassList($conn, $object);
        public function saveStudent($conn, $object);
        public function getStudentInfo($conn, $studentID);
        public function updateStudent($conn, $object);

        public function createBackup($conn, $conn2, $conn3, $profileID);
        public function getBackupList($conn3, $profileID);
        public function importDB($conn, $conn3, $backupID, $profileID);

        public function getAdminList($conn);
        public function getProfile($conn, $profileID);
        public function createNewAdminAccnt($conn, $firstName, $middleName, $lastName, $email, $userName);
        public function getSelectedProfile($conn, $ID);
        public function deleteAdminAccnt($conn, $profileID);
        public function updatePassword($conn, $profileID, $oldPassword, $newPassword);
        public function updateAdminProfile($conn, $profileID, $firstName, $middleName, $lastName);
        public function resetPassword($conn, $myProfileID, $profileID, $password);

        public function getMessageList($conn, $conn2, $profileID);
        public function getMessageSentList($conn, $conn2, $profileID);
        public function getMessageDeletedList($conn, $conn2, $profileID);

        public function getMessageContent($conn, $conn2, $mailID, $profileID);
        public function getSentMessageDetails($conn, $conn2, $mailID, $profileID);
        public function getDeletedMessageDetails($conn, $conn2, $mailID, $profileID);
        public function getRecipientInfo($conn, $conn2, $conversationID, $profileID, $isReply);
        public function getAllContacts($conn, $conn2, $profileID);

        public function deleteUploadedImage($conn, $src);
        public function deleteUploadedFile($conn, $src);
        public function composeNewMail($conn, $conn2, $isAnnouncement, $to, $from, $title, $msg);
        public function replyToMail($conn, $conn2, $title, $conversationID, $to, $from, $msg);
        public function replyToAllMail($conn, $conn2, $conversationID, $from, $msg, $profileID);
        public function getReplyDetails($conn, $conn2, $conversationID, $profileID);
        public function getReplyRecipientInfo($conn, $conn2, $mailID, $profileID, $isReply);

        public function deleteMail($conn2, $mailID);
        public function restoreMail($conn2, $mailID);
        public function deleteReply($conn2, $mailID);

        public function uploadFileToDB($conn, $form, $data);

        public function getAcademicTrackm($conn);
        public function getActiveStrandsPerTrackm($conn, $object, $profileID);
        public function transferStudent($conn, $classProfile, $studentProfile);
        public function activateStudent($conn, $profileID);
        public function deactivateStudent($conn, $profileID);
        
        public function getContactList($conn, $conn2, $profileID);
        public function getGroupList($conn, $conn2, $profileID);
        public function addNewContacts($conn, $conn2, $name, $mail, $profileID);
        public function addNewGroups($conn, $conn2, $name, $mail, $profileID);
        public function getGroupDetails($conn, $conn2, $groupProfile, $profileID);
        public function getGroupMembers($conn, $conn2, $groupProfile, $profileID);
        public function addContactsToGroup($conn, $conn2, $name, $mail, $groupProfile, $profileID);
        public function removeFromGroup($conn, $conn2, $mail, $groupProfile, $profileID);
        public function updateGroupDetails($conn, $conn2, $name, $mail, $groupProfile, $profileID);
        public function deleteGroup($conn, $conn2, $groupProfile, $profileID);
        public function getContactDetails($conn, $conn2, $linkID, $profileID);
        public function updateContactDetails($conn, $conn2, $linkID, $name, $mail, $profileID);
        public function deleteContacts($conn, $conn2, $linkID, $profileID);
        
        public function getStrandNameForPromo($conn, $object);
        public function getAllStudentListForPromoPerStrand($conn, $object);
        public function promotionPreparation($conn, $conn2, $object);
        public function step3PromoteStudent($conn, $conn2, $object);
        
        public function getNavProfile($conn, $profileID, $accntLevel);
        public function uploadProfileImg($conn, $profileID, $file);

        public function resetDatabase($conn, $conn2, $profileID, $password);
        public function getSystemLogs($conn);

        public function getLatestNews($conn, $conn2);

        public function getRequestList($conn, $profileID);
        public function approveRequest($conn, $requestID, $profileID);
        public function denyRequest($conn, $requestID, $profileID, $reason);
    }
?>