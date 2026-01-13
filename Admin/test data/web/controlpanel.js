var announce = document.getElementById("writeAnnouncement"); 
var modalAdd = document.getElementById("addSchoolYear"); 
let faccount = 0;


var tblNews = $('#tblNews').DataTable({paging: false, info: false, sort: false, "lengthChange" : true, searching: false});

$(function() {
    showOverLay();
    if($(location).attr('href').includes("logout.php")){
        logout();
    } else {
        checkLoginIsValid();
    }
    
});

function handleError(err) {
    console.log(err);
    const errHandler = errorGlobal.find(x => x.errCode === JSON.parse(err.responseText).status);
    console.log(errHandler);
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
                err.responseText
            );
        }
    }
}

function checkLoginIsValid(){
    const category = "checkLoginIsValid";
    const profileID = getCookie("profileID");
    var ip;
    $.getJSON("https://api.ipify.org/?format=json", function(e) {
        ip = e.ip;
    });  

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
        console.log(data);
        if (data.status=="success") {
            setCookie("profileID", profileID, "1"); 
            $("#dashboard").addClass("active");
            if($(location).attr('href').includes("browser.php")) {
                getActiveFacultyCount(); 
                getSchoolyearDetails();
                getActiveStudentCount();
                getActiveStrandCount();
                
            } else { 
                getCurrentActiveSchoolyear(); 
                                
            }
            hideOverlay();
            getLatestNews();            
        } else {
            deleteCookie("profileID");
            invalidLogin();           
        }
    })
    .catch(err => handleError(err));
}

function getLatestNews(){
    const category = "getNews";

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
            return JSON.parse(text);     // parse only if valid JSON
        } catch (e) {
            throw {
                status: res.status,
                statusText: "Invalid JSON response",
                responseText: text
            };
        }        
    })
    .then(response => {
        if (response.data?.length > 0) {
            document.getElementById("newsContainer").innerHTML =
                `<h4>${response.data[0].mailTitle}</h4>
                <span>${response.data[0].mailContent}</span>`;
        }
    })
    .catch(err => handleError(err));
}

function getActiveStrandCount(){
    $.ajax({
        url: "web/controller/adminController.php", 
        type: "GET",
        data: {
            type: "getActiveStrandCount",
            schoolyearID: atob(getCookie("syid"))
        },
        success: function(fndata){
            $("#activeStrandCount").html(fndata);
            if(fndata>0){
                $("#btnstudent").attr("disabled",false);
            }
        },
        error: function (err){
            errHandler = errorGlobal.find(x => x.errCode === err.responseText);
            if(errHandler){
                swalSimple(errHandler.title.replaceAll("[object]","Username"),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]","Username")); 
            } else {
                console.log(err);
                if(err.statusText == "error"){
                    swalSimple("Error 503 : Service Unavailable","error", "A server-side error has occurred. Please try again later. If issue persist please report to Admin.");                 
                } else {
                   swalSimple(err.status +" "+ err.statusText,"error",err.responseText);      
                }
            }  
        }
    });    
}
function getActiveStudentCount(){
    $.ajax({
        url: "web/controller/adminController.php", 
        type: "GET",
        data: {
            type: "getActiveStudentCount",
            schoolyearID: atob(getCookie("syid"))
        },
        success: function(fndata){
            $("#activeStudentCount").html(fndata);
        },
        error: function (err){
            errHandler = errorGlobal.find(x => x.errCode === err.responseText);
            if(errHandler){
                swalSimple(errHandler.title.replaceAll("[object]","Username"),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]","Username")); 
            } else {
                console.log(err);
                if(err.statusText == "error"){
                    swalSimple("Error 503 : Service Unavailable","error", "A server-side error has occurred. Please try again later. If issue persist please report to Admin.");                 
                } else {
                   swalSimple(err.status +" "+ err.statusText,"error",err.responseText);      
                }
            }  
        }
    });    
}

function getActiveFacultyCount(){
    $.ajax({
        url: "web/controller/adminController.php", 
        type: "GET",
        data: {
            type: "getActiveFacultyCount"
        },
        success: function(fndata){
            $("#activeFacultyCount").html(fndata);
            faccount = fndata;
            if(fndata>0){
                $("#btnstrand").attr("disabled",false);
            }
        },
        error: function (err){
            errHandler = errorGlobal.find(x => x.errCode === err.responseText);
            if(errHandler){
                swalSimple(errHandler.title.replaceAll("[object]","Username"),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]","Username")); 
            } else {
                console.log(err);
                if(err.statusText == "error"){
                    swalSimple("Error 503 : Service Unavailable","error", "A server-side error has occurred. Please try again later. If issue persist please report to Admin.");                 
                } else {
                   swalSimple(err.status +" "+ err.statusText,"error",err.responseText);      
                }
            }  
        }
    });    
}

function getCurrentActiveSchoolyear(){
    const schoolyearId = getCookie("syid")!=null? atob(getCookie("syid")) : 0;
    const category = "getCurrentActiveSchoolyear";

    const params = new URLSearchParams({
        schoolyearId: schoolyearId,
        category: category
    });

    fetch(`web/c/controller.php?${params.toString()}`, {
        method: "GET",
        headers: {
            "Accept": "application/json"
        }
    })
    .then(async res => {
        const text = await res.text(); // SAFE: handles HTML or JSON

        if (!res.ok) {
            throw {
                status: res.status,
                statusText: res.statusText,
                responseText: text
            };
        }

        try {
            return JSON.parse(text);
        } catch {
            throw {
                status: res.status,
                statusText: "Invalid JSON",
                responseText: text
            };
        }
    })
    .then(data => {
        if (data.data && data.data.length > 0) {
            const sy = data.data[0];
            $("#currSY").html(`${sy.startYear} - ${sy.endYear}`);
            $("#currSY").attr("syid", sy.schoolyearID);
            $("#activeSYbtn").attr("disabled", false);
        } else {
            $("#currSY").html("");
        }
    })
    .catch(err => handleError(err));
   
}

function getSchoolyearDetails(){
    $.ajax({
        url: "web/controller/adminController.php", 
        type: "GET",
        dataType: 'json',
        data: {
            type: "getSchoolyearDetails",
            schoolyearID: atob(getCookie("syid"))
        },
        success: function(fndata){
            var errHandler = errorGlobal.find(x => x.errCode === fndata);
            if (errHandler){
                swalSimple(errHandler.title.replaceAll("[object]","School Year(s)"),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]","School Year(s)"));
            } else {
                $('#currSY2').html(fndata[0].startYear + " - " + fndata[0].endYear);
                $('#currSY3').html(fndata[0].startYear + " - " + fndata[0].endYear);
            }
        },
        error: function (err){
            errHandler = errorGlobal.find(x => x.errCode === err.responseText);
            if(!errHandler){
                console.log(err);
                if(err.statusText == "error"){
                    swalSimple("Error 503 : Service Unavailable","error", "A server-side error has occurred. Please try again later. If issue persist please report to Admin.");                 
                } else {
                   swalSimple(err.status +" "+ err.statusText,"error",err.responseText);      
                }
            }   
        }
    });
}

function openModalA(){   
    announce.style.display = "block";
}

function closeModalA(){
    announce.style.display = "none";
}

function addSchoolYear(){   
    modalAdd.style.display = "block";
}

function createSchoolyear(){
    const startDate = new Date($('#txtStartDate').val());
    const endDate = new Date($('#txtEndDate').val());
    if(startDate.getFullYear() < endDate.getFullYear()){
        $.ajax({
            url: "web/controller/adminController.php", 
            type: "GET",
            data: {
                type: "addSchoolYear",
                startYear: startDate.getFullYear(),
                endYear: endDate.getFullYear(),
                startDate: $('#txtStartDate').val(),
                endDate: $('#txtEndDate').val(),
                chkActive: $('#chkActive').is(":checked")
            },
            success: function(fndata){
                var errHandler = errorGlobal.find(x => x.errCode === fndata);
                if(errHandler){
                    swalSimple(errHandler.title.replaceAll("[object]","School Year(s)"),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]","School Year(s)"));
                } else {
                    swalSimple(fndata,"success","");
                    getCurrentActiveSchoolyear();
                }
                closeAddSchoolYear();
                
            },
            error: function (err){
                console.log(err.responseText);
                errHandler = errorGlobal.find(x => x.errCode === err.responseText);
                if(errHandler){
                    swalSimple(errHandler.title.replaceAll("[object]","School Year(s)"),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]","School Year(s)"));
                } else {
                    console.log(err);
                    if(err.statusText == "error"){
                        swalSimple("Error 503 : Service Unavailable","error", "A server-side error has occurred. Please try again later. If issue persist please report to Admin.");                 
                    } else {
                       swalSimple(err.status +" "+ err.statusText,"error",err.responseText);      
                    }
                }  
            }
        });  
    } else {
        swalSimple("Invalid Schoolyear","warning","End year must be bigger than Start year");
    }

}
$('#chkActive').on("change", function () {
    if($(this).prop("checked")){
        swalSimple("Set Schoolyear as Active?","warning","Setting this to Active will make it as selectable in all Schoolyear selection.");
    }
});

function closeAddSchoolYear(){
    $('#txtStartDate').val("");
    $('#txtEndDate').val("");
    modalAdd.style.display = "none";
}

function closeSY(){
    Swal.fire({
        icon: 'question',
        title: 'Are you sure you want to Close this Schoolyear?',
        text: 'Closing a Schoolyear will mean that no further changes can be made to all Classes under this schoolyear. Ensure all task has been completed before doing so. Schoolyear can be re-activated manully by going to Schoolyear module.',
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: 'Close',
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "web/controller/adminController.php", 
                type: "GET",
                data: {
                    type: "closeSY",
                    schoolyearID: atob(getCookie("syid"))
                },
                success: function(fndata){
                    deleteCookie("syid");
                    swalMsg(fndata,"success","","controlpanel.php");         
                },
                error: function (err){
                    errHandler = errorGlobal.find(x => x.errCode === err.responseText);
                    if(errHandler){
                        swalSimple(errHandler.title.replaceAll("[object]","Strand(s)"),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]","Strand(s)"));
                    } else {
                        console.log(err);
                        if(err.statusText == "error"){
                            swalSimple("Error 503 : Service Unavailable","error", "A server-side error has occurred. Please try again later. If issue persist please report to Admin.");                 
                        } else {
                           swalSimple(err.status +" "+ err.statusText,"error",err.responseText);      
                        }
                    }  
                }
            }); 
        }
    });    
}

function logout(){
    const profileID = getCookie("profileID");
    const category = "logout";

    const params = new URLSearchParams({
        profileID: profileID,
        category: category
    });

    fetch(`web/c/controller.php?${params.toString()}`, {
        method: "GET",
        headers: {
            "Accept": "application/json"
        }
    })
    .then(async res => {
        const text = await res.text(); // SAFE: handles HTML or JSON

        if (!res.ok) {
            throw {
                status: res.status,
                statusText: res.statusText,
                responseText: text
            };
        }

        try {
            return JSON.parse(text);
        } catch {
            throw {
                status: res.status,
                statusText: "Invalid JSON",
                responseText: text
            };
        }
    })
    .then(data => {
        if (data==true) {
            deleteCookie("profileID"); 
        }
    })
    .catch(err => handleError(err));    
}