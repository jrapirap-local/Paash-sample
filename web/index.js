let ip = null;
$(function() {
    checkDBConnection();
    

    $.getJSON("https://api.ipify.org?format=json")
    .done(function (res) {
        ip = res.ip;
        console.log("IP:", ip);
        // call your login / update function here
    })
    .fail(function () {
        console.error("Failed to fetch IP address");
    });
});

var viewUserPrivacy = document.getElementById("viewUserPrivacy"); 

function checkDBConnection(){
    const category = "connectToDB";

    const params = new URLSearchParams({
        category: category
    }); 

    fetch(`web/c/controller.php?${params.toString()}`, {
        method: "GET",
        headers: {
            "Accept": "application/json"
        }
    })
    .then(async res => {
        const text = await res.text();   // read as text FIRST
        if (!res.ok) {
            throw {
                status: res.status,
                statusText: res.statusText,
                responseText: text
            };
        }
        try {
            console.log(JSON.parse(text).message);
            if(JSON.parse(text).status=="success"){
                $("#ccLoginBox").attr("disabled",false);
                hideOverlay();
                if(getCookie("profileID")){
                    checkLoginIsValid();
                }
                
            } else {
                hideOverlay();
                throw {
                    status: res.status,
                    statusText: "No DB connection",
                    responseText: text
                };
            }
        } catch (e) {
            throw {
                status: res.status,
                statusText: "Invalid JSON response",
                responseText: text
            };
        }        
    })
    .catch(err => handleError(err));    
}



$('#txtUsername').on("keypress", function (e) {
    var key = e.which;
    if(key == 13)  // the enter key code
    {
        getLoginInformation();
    }
});

$('#txtPassword').on("keypress", function (e) {
    var key = e.which;
    if(key == 13)  // the enter key code
    {
        getLoginInformation();
    }
});

function handleError(err) {
    console.log(err);
    const errHandler = errorGlobal.find(x => x.errCode === err.responseText);
    if (errHandler) {
        swalSimple(
            errHandler.title,
            errHandler.errSevererity,
            errHandler.errMsg
        );
    } else {
        if (err.status === 503) {
            swalSimple(
                "Error 503 : Service Unavailable",
                "error",
                "A server-side error has occurred. Please try again later."
            );
        } else {
            swalSimple(
                `${err.status} ${err.statusText}`,
                "error",
                JSON.parse(err.responseText).message
            );
        }
    }
}

function checkLoginIsValid(){
    const category = "checkLoginIsValid";
    const profileID = getCookie("profileID");

    const params = new URLSearchParams({
        category: category,
        profileID: profileID,
        ip: ip
    }); 

    fetch(`web/c/controller.php?${params.toString()}`, {
        method: "GET",
        headers: {
            "Accept": "application/json"
        }
    })
    .then(async res => {
        const text = await res.text();   // read as text FIRST
        if (!res.ok) {
            throw {
                status: res.status,
                statusText: res.statusText,
                responseText: text
            };
        }
        try {
            return JSON.parse(text);     // parse only if valid JSON
        } catch (e) {
            throw {
                status: res.status,
                statusText: "Invalid JSON response",
                responseText: text
            };
        }        
    })
    .then(data => {
        if (data.status=="SUCCESS") {
            setCookie("profileID", data.user.profileID, "1");
            setCookie("accntLevel", data.user.accntLevel, "1");
            switch (parseInt(data.user.accntLevel)) {
                case 0:
                    window.location.href = "Admin"; 
                    break;
                case 1:
                    window.location.href = "Faculty"; 
                    break;
                case 2:
                    var dat= "Data privacy for Learner Reference Number (LRN) is crucial under the Data Privacy Act of 2012 (DPA) and DepEd Order 22, s. 2012. The LRN, a unique identifier for students, is considered personal information and must be handled with care. This includes protecting it from unauthorized access, ensuring its use is limited to legitimate purposes, and respecting the rights of data subjects (students and their parents/guardians).";
                    swalMsg('Data privacy','warning',dat,'Student');
                    break;
                case 3:
                    window.location.href = "Parent"; 
                    break;                          
                default:
                    
            }            
        } else {
            deleteCookie("profileID");           
        }
    })
    .catch(err => handleError(err));
}

function getLoginInformation(){
    //showOverLay();
    $("#txtuname").css("border-color", "");
    $("#txtpass").css("border-color", "");
    if($("#txtuname").val() =='' || $("#txtpass").val()==''){
        if($("#txtuname").val() ==''){
            $("#txtuname").css("border-color", "red");
        }
        if($("#txtpass").val() ==''){
            $("#txtpass").css("border-color", "red");
        }
        swalSimple("Required fields cannot be empty.","error","");
        hideOverlay();
    } else {
        const category = "getLoginInformation";
        const profileID = getCookie("profileID");
        const uname = $("#txtuname").val();
        const pass = $("#txtpass").val();

        const params = new URLSearchParams({
            category: category,
            profileID: profileID,
            uname: uname,
            pass: pass,
            ip: ip
        }); 

        fetch(`web/c/controller.php?${params.toString()}`, {
            method: "GET",
            headers: {
                "Accept": "application/json"
            }
        })
        .then(async res => {
            const text = await res.text();   // read as text FIRST
            if (!res.ok) {
                throw {
                    status: res.status,
                    statusText: res.statusText,
                    responseText: text
                };
            }
            try {
                return JSON.parse(text);     // parse only if valid JSON
            } catch (e) {
                throw {
                    status: res.status,
                    statusText: "Invalid JSON response",
                    responseText: text
                };
            }        
        })
        .then(data => {
            console.log(data);
            if (data) {
                setCookie("profileID", data.user.profileID, "1");
                setCookie("accntLevel", data.user.accntLevel, "1");
                switch (parseInt(data.user.accntLevel)) {
                    case 0:
                        window.location.href = "Admin"; 
                        break;
                    case 1:
                        window.location.href = "Faculty"; 
                        break;
                    case 2:
                        var dat= "Data privacy for Learner Reference Number (LRN) is crucial under the Data Privacy Act of 2012 (DPA) and DepEd Order 22, s. 2012. The LRN, a unique identifier for students, is considered personal information and must be handled with care. This includes protecting it from unauthorized access, ensuring its use is limited to legitimate purposes, and respecting the rights of data subjects (students and their parents/guardians).";
                        swalMsg('Data privacy','warning',dat,'Student');
                        break;
                    case 3:
                        window.location.href = "Parent"; 
                        break;                          
                    default:
                        swalMsg('Login Failed!','danger','','index.php');
                }  
            }
        })
        .catch(err => handleError(err));
    }

}

function userPolicy() {
    viewUserPrivacy.style.display = "block";
}

function closeUserPolicy() {
    viewUserPrivacy.style.display = "none";
}

function chkUserPrivacy(){
    var isChecked = $('#chkUserPolicy').is(':checked');
    if(isChecked){
        $('#btnLogin').removeClass('disabled');
    } else {
        $('#btnLogin').addClass('disabled');
    }
}