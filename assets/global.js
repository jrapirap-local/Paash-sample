var errorGlobal = [
    { errCode: 'INVALID_ACCESS', errType: 'Login', title: 'Access Denied', errSevererity: 'danger', errMsg: 'Your account does not have an access to this module.'},
    { errCode: 'INCORRECT_LOGIN', errType: 'Login', title: 'Access Denied', errSevererity: 'warning', errMsg: 'Incorrect Username and/or Password. Please try again.'},
    { errCode: 'ACCOUNT_LOGGED', errType: 'Login', title: 'Access Denied', errSevererity: 'warning', errMsg: 'This account is already logged in. If you are not aware of this, please report to admin.'},
    { errCode: 'INFO_REQUIRED', errType: 'set', title: 'Required fields for [object] cannot be Empty', errSevererity: 'warning', errMsg: 'Creating [object] required all necessary field(s) to be filled. Please check form and try again.'},
    { errCode: 'ERR_DUPLICATE', errType: 'set', title: '[object] already Exists', errSevererity: 'warning', errMsg: 'A Duplicate Entry for [object] is not allowed!'},
    { errCode: 'ERR_DUPLICATE_EMAIL', errType: 'set', title: '[object]\'s Email already Exists', errSevererity: 'warning', errMsg: 'Duplicate Entry for [object]\'s Email not allowed!'},
    { errCode: 'ERR_DUPLICATE_USERNAME', errType: 'set', title: '[object]\'s Username already Exists', errSevererity: 'warning', errMsg: 'Duplicate Entry for [object]\'s Username not allowed!'},
    { errCode: 'ERR_DUPLICATE_LRN', errType: 'set', title: '[object]\'s LRN already Exists', errSevererity: 'warning', errMsg: 'Duplicate Entry for [object]\'s LRN not allowed!'},
    { errCode: 'INFO_NOT_MATCH', errType: 'check', title: '[object] did not match', errSevererity: 'warning', errMsg: '[object] did not macth. Please try again.'},
    { errCode: 'INFO_WRONG_FORMAT_LEN', errType: 'check', title: '[object] format not valid', errSevererity: 'warning', errMsg: '[object] minimum length are not met. Please try again.'},
    { errCode: 'INFO_WRONG_FORMAT_REGEX', errType: 'check', title: '[object] format not valid', errSevererity: 'warning', errMsg: '[object] format is not valid, Check input then try again.'},
    { errCode: 'INFO_WRONG_FORMAT_REGEX_EMAIL', errType: 'check', title: '[object] format not valid', errSevererity: 'warning', errMsg: '[object] format is not valid, proper email format is as follows \'[username]@[server].[domain]\'.'},
    { errCode: 'INFO_WRONG_FORMAT_REGEX_NUMBER', errType: 'check', title: '[object] format not valid', errSevererity: 'warning', errMsg: '[object] format is not valid, [object] format must be 10 character\'s in lenght and must contain numbers only.'},
    { errCode: 'INFO_NOLIST', errType: 'get', title: 'No Active [object].', errSevererity: 'info', errMsg: 'The are currently no active [object] to show.'},
    { errCode: 'INFO_NOLIST_FOR_TRACK', errType: 'get', title: '[object] has no listed Strand(s)', errSevererity: 'info', errMsg: 'Selected [object] has no Strand to list.'},
    { errCode: 'INFO_NOLIST_FOR_SUBJECT', errType: 'get', title: '[object] has no listed Subjects(s)', errSevererity: 'info', errMsg: 'Selected [object] has no Subject to list.'},
    { errCode: 'ERR_LIST', errType: 'get', title: 'NO [object] Loaded', errSevererity: 'error', errMsg: 'Loading [object] failed. Please try again later. If error persist, contact your web developer.'},
    { errCode: 'ERR_CREATE', errType: 'new', title: 'Creation of new [object], Failed', errSevererity: 'error', errMsg: 'Unable to create [object]. Please try again later. if error persist, contact your web developer.'},
    { errCode: 'ERR_DEL', errType: 'set', title: 'Deletion of [object], Failed', errSevererity: 'error', errMsg: 'Unable to delete [object]. Please try again later. if error persist, contact your web developer.'},
    { errCode: 'INFO_NOENROLLED', errType: 'get', title: 'NO [object] Assigned to this Profile', errSevererity: 'info', errMsg: 'There are no [object] assigned to this profile. You may Assign a [object] by pressing the \'Add\' button for [object] below.'},
    { errCode: 'ERR_UPDATE', errType: 'set', title: 'Updating [object], Failed', errSevererity: 'error', errMsg: 'Unable to Update [object]. Please try again later. if error persist, contact your web developer.'},
    { errCode: 'ERR_CREATE_BULK', errType: 'new', title: 'Creation of multiple [object], Failed', errSevererity: 'error', errMsg: 'A createion of [object] has terminated unexpectedly. Please try again later. If error persist, contact your web developer.'},
    { errCode: 'ERR_EMPTY_FIELD', errType: 'new', title: '[object] cannot be empty', errSevererity: 'warning    ', errMsg: '[object] cannot be empty. Please ensure [object] has value then try again. If error persist, contact your web developer.'},
    { errCode: 'ERR_FIELD_LIMIT_ATC', errType: 'new', title: '[object] file attached exceeds its limit', errSevererity: 'warning', errMsg: '[object] file limit is [limit]. If error persist, contact your web developer.'},
    { errCode: 'ERR_EMAIL_RECIPIENT', errType: 'new', title: '[object] recipient is empty/invalid', errSevererity: 'warning', errMsg: '[object] recipient may not exist on the server or is empty. To ensure a valid recipient address, please refer to autocomplete list.'},
    { errCode: 'ERR_EMAIL_MSG_CONTENT', errType: 'new', title: '[object] message body is empty', errSevererity: 'warning', errMsg: '[object] message body cannot be empty! Maximum of 500 characters only! Attachments such as; documents and/or images does not count. html tags and a like is included.'},
    { errCode: 'VERIFICATION_FAILED', errType: 'new', title: 'Password Verification', errSevererity: 'error', errMsg: 'Account verification failed! Please try again.'},
    { errCode: 'MASTER_ADMIN', errType: 'new', title: 'Master Admin Task', errSevererity: 'error', errMsg: 'You do not have access to this section. Please use a Master Admin account.'},
    { errCode: 'MASTER_ADMIN_DB_RESET', errType: 'new', title: 'Master Admin Task', errSevererity: 'error', errMsg: 'You do not have access to this section. Please use a Master Admin account.'},
    { errCode: 'ERR_DB_BACKUP', errType: 'new', title: 'DB Backup', errSevererity: 'error', errMsg: 'DB Backup error. Please seek developers help.'},
    { errCode: 'ERR_DB_RESTORE', errType: 'new', title: 'DB Rstore', errSevererity: 'error', errMsg: 'DB Restore error. Please seek developers help.'},
    { errCode: 'ERR_UPLOAD', errType: 'new', title: 'Image Upload', errSevererity: 'error', errMsg: 'Error in uploading image, please check file and try again. If issue persist, contact developer.'},
    { errCode: 'ERR_USERNOTFOUND', errType: 'new', title: 'User Not Found!', errSevererity: 'warning', errMsg: 'User with Email [object] was not found. Please ensure email was spelled correctly and then try again.'},
    { errCode: 'ERR_REQUEST', errType: 'new', title: 'Request could not be sent!', errSevererity: 'warning', errMsg: 'We could not process your request right now, please try again later. If issue persist, please contact the Developers.'},
    { errCode: 'ERR_HPS', errType: 'new', title: 'Gradesheet HPS not set!', errSevererity: 'warning', errMsg: 'HPS or the Highest Score Possible is not set. Please provide an HPS first! Any modificatin on grades will not be saved.'},
    { errCode: 'ERR_NODAYS', errType: 'new', title: 'No Set day for Subject Schedule!', errSevererity: 'warning', errMsg: 'Schedule was not saved.'},
    { errCode: 'ERR_SCHEDTAKEN', errType: 'new', title: 'Invalid Schedule', errSevererity: 'warning', errMsg: 'Schedule Start and/or End Time overlap on an existing schedule for this class. Please provide a different Start and/or End time for this subject.'},
    { errCode: 'ERR_SCHEDTAKEN_FACULTY', errType: 'new', title: 'Invalid Schedule', errSevererity: 'warning', errMsg: 'The Faculty selected for this schedule is already with a class, change the scheduled time or select a different Faculty.'},
    { errCode: 'ERR_DUPLICATE_FACULTY', errType: 'set', title: 'Cannot add [object] advisor.', errSevererity: 'warning', errMsg: 'Faculty already has an advisory for this class type. Please check details or select a different faculty member and try again.'},
    { errCode: 'ERR_NO_SCHED_STRAND', errType: 'set', title: 'Cannot generate schedule.', errSevererity: 'warning', errMsg: 'There are no active strand subjects for this semester.'},
];

$(window).on('beforeunload', function () {
    showOverLay();
});

// Disable the table
function disableTable() {
$('#table-overlay').show();
}

// Enable the table
function enableTable() {
$('#table-overlay').hide();
}

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function deleteCookie(name) {
    setCookie(name, '', -1);
}

const Toast = Swal.mixin({
toast: true,
position: "top-end",
showConfirmButton: false,
timer: 3000,
timerProgressBar: true,
didOpen: (toast) => {
    toast.onmouseenter = Swal.stopTimer;
    toast.onmouseleave = Swal.resumeTimer;
}
});

function swalMsg(title, icon, msg, target){
    Swal.fire({
        icon: icon,
        title: title,
        html: msg,
        showDenyButton: false,
        showCancelButton: false,
        allowOutsideClick: false,
        }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = target;
        }
    });  
}

function swalMsg2(title, icon, msg, target){
    Swal.fire({
        icon: icon,
        title: title,
        html: msg,
        showDenyButton: false,
        showCancelButton: false,
        allowOutsideClick: false,
        }).then((result) => {
        if (result.isConfirmed) {
            target();
        }
    });  
}

function swalSimple(title, icon, msg){
    Swal.fire({
        icon: icon,
        title: title,
        html: msg,
        showDenyButton: false,
        showCancelButton: false,
        });  
}

function swalTimer(title, icon, msg, timer, target){
    let timerInterval;
    Swal.fire({
    title: title,
    html: "I will close in <b></b> milliseconds.",
    timer: timer,
    timerProgressBar: true,
    didOpen: () => {
        Swal.showLoading();
        const timer = Swal.getPopup().querySelector("b");
        timerInterval = setInterval(() => {
        timer.textContent = `${Swal.getTimerLeft()}`;
        }, 100);
    },
    willClose: () => {
        clearInterval(timerInterval);
    }
    }).then((result) => {
    /* Read more about handling dismissals below */
    if (result.dismiss === Swal.DismissReason.timer) {
        console.log("I was closed by the timer");
    }
    });
}

function logOut(){
    Swal.fire({
        icon: "info",
        title: "You are about to Logout.",
        text: "Press 'OK' to confirm.",
        showDenyButton: false,
        showCancelButton: true,
        }).then((result) => {
        if (result.isConfirmed) {
            setCookie("isLoginValid",false,"1");
            window.location.href = "logout.php";
        }
    });      
}

function showOverLay(){
    $('#loadingOverlay').removeAttr("hidden");
}

function hideOverlay(){
    $('#loadingOverlay').attr("hidden","hidden");
}

function toLocalStorage(localName, localValue){
    localStorage.setItem(localName, btoa(localValue));
}

function getFromLocalStorage(localName){
    return atob(localStorage.getItem(localName));
}

function removeFromLocalStorage(localName){
    localName.removeItem(localName);
}

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};

function invalidLogin(){
    swalMsg("Invalid Login","error","Your Login Session is invalid. Please Login again.","../index.php");
}

function inputOnChangeValidationStr(e,str){
    errHandler = errorGlobal.find(x => x.errCode === validateInputStr(e));
    if(errHandler){
        $(e).addClass("border border-3 border-danger");
        //swalSimple(errHandler.title.replaceAll("[object]",str),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]",str));
        $(e).val("");
    } else {
        $(e).removeClass("border border-3 border-danger");
        $("#err"+$(e).attr("id")).html("");
    }
}

function validateInputStr(e){
    //Accepts alpha numeric only
    if(!/^[A-Za-z0-9\s\.\-\,]+$/.test($(e).val())){
        return 'INFO_WRONG_FORMAT_REGEX';
    }
}

function inputOnChangeValidationNum(e,str){
    console.log($(e).attr("id"));
    errHandler = errorGlobal.find(x => x.errCode === validateInputNum(e));
    if(errHandler){
        $(e).addClass("border border-3 border-danger");
        //swalSimple(errHandler.title.replaceAll("[object]",str),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]",str));
        $(e).val("");
    } else {
        $(e).removeClass("border border-3 border-danger");
        $("#err"+$(e).attr("id")).html("");
    }
}

function validateInputNum(e){
    //Accepts alpha numeric only
    if(!/^\d{1,10}$/.test($(e).val())){
        return 'INFO_WRONG_FORMAT_REGEX_NUMBER';
    }
}

function inputOnChangeValidationStrRes(e,str){
    errHandler = errorGlobal.find(x => x.errCode === validateInputStrRes(e));
    if(errHandler){
        $(e).addClass("border border-3 border-danger");
        //swalSimple(errHandler.title.replaceAll("[object]",str),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]",str));
        $(e).val("");
    } else {
        $(e).removeClass("border border-3 border-danger");
        $("#err"+$(e).attr("id")).html("");
    }
}

function validateInputStrRes(e){
    //Accepts alpha numeric only
    if(!/^[A-Za-z0-9]+$/.test($(e).val())){
        return 'INFO_WRONG_FORMAT_REGEX';
    }
}

function inputOnChangeValidationEmail(e,str){
    errHandler = errorGlobal.find(x => x.errCode === validateInputEmail(e));
    if(errHandler){
        $(e).addClass("border border-3 border-danger");
        //swalSimple(errHandler.title.replaceAll("[object]",str),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]",str));
        $(e).val("");
    } else {
        $(e).removeClass("border border-3 border-danger");
        $("#err"+$(e).attr("id")).html("");
    }
}

function validateInputEmail(obj){
    if(!/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test($(obj).val())){
        return 'INFO_WRONG_FORMAT_REGEX_EMAIL';
    }
}

function gotolink(link, val){
    if(val!=null){
        setCookie("syid", btoa(val), 1);
    }
    
    window.location.href = link;
}

function convertTo12Hour(timeString) {
    const [hours, minutes, seconds] = timeString.split(':');
    let hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    hour = hour % 12 || 12; // convert 0 to 12 for midnight
    return `${hour.toString().padStart(2, '0')}:${minutes} ${ampm}`;
}

$(function() {
    let arr2=["controlpanel","strands","faculty","subjects","schoolyear", "class","studentlist", "database", "myProfile"];
    $.each(arr2, function(index, value) {
        // Operation on each element
        if($(location).attr('href').includes(value+".php")){
            $('#nav'+value).addClass("current");
        } else {
            $('#nav'+value).removeClass("current");
        }
    });
});

