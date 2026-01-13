var ip;
$(function() {
    
    $.getJSON("https://api.ipify.org/?format=json", function(e) {
        ip = e.ip;
    })    
    
    if (/Mobi|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        console.log("User is on a mobile device");
        swalSimple("Mobile Device Detected!","info","This webpage is built for widescreen devices. Some features, UI and modules may not work as intended! It is adviced to open this page via Desktop Computer and/or a Laptop.");
        // You can also trigger mobile-specific functions here
    } else {
        console.log("User is on a desktop or non-mobile device");
    }
    
    getNavProfile();
    if(!$(location).attr('href').includes("controlpanel")) {
        //getSchoolyearDetailsnav();
    }
    
    
    intervalId = setInterval(function() {
        
        //checkAccountStat(intervalId);
        //getNotification(intervalId);
    }, 1000); // 1000 milliseconds = 1 second 
    
});

function checkAccountStat(intervalId){
        $.ajax({
        url: "web/controller/adminController.php", 
        type: "GET",
        data: {
            type: "checkAccountStat",
            profileID: getCookie("profileID")
        },
        success: function(fndata){
            if(fndata === false){
                deleteCookie("profileID"); 
                setCookie("isLoginValid", false, "1");
                swalSimple("You have been logged out..","info", "Your account cannot be validated and was forced to logout.");  
                clearInterval(intervalId);
            }
        },
        error: function (err){
            clearInterval(intervalId);
            errHandler = errorGlobal.find(x => x.errCode === err.responseText);
            if(!errHandler){
                console.log(err);
                swalSimple(err.status +" "+ err.statusText,"error",err.responseText);
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

function getSchoolyearDetailsnav(){
    $.ajax({
        url: "web/controller/adminController.php", 
        type: "GET",
        dataType: 'json',
        data: {
            type: "getSchoolyearDetails",
            schoolyearID: atob(getCookie("syid"))
        },
        success: function(fndata){
            if(fndata.length > 0){
                var errHandler = errorGlobal.find(x => x.errCode === fndata);
                if (errHandler){
                    swalSimple(errHandler.title.replaceAll("[object]","School Year(s)"),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]","School Year(s)"));
                } else {
                    $('#currSY2').html(fndata[0].startYear + " - " + fndata[0].endYear);
                    $('#currSY3').html(fndata[0].startYear + " - " + fndata[0].endYear);
                    const startDate = new Date(fndata[0].startDate);
                    const endDate = new Date(fndata[0].endDate);
                    $('#systartdate').html(startDate.toDateString());
                    $('#syenddate').html(endDate.toDateString());
                }              
            }

        },
        error: function (err){
            errHandler = errorGlobal.find(x => x.errCode === err.responseText);
            if(!errHandler){
                console.log(err);
                swalSimple(err.status +" "+ err.statusText,"error",err.responseText);
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

function getNavProfile(){
    const profileID = getCookie("profileID");
    const accntLevel = getCookie("accntLevel");
    const category = "getNavProfile";

    const params = new URLSearchParams({
        profileID: profileID,
        accntLevel: accntLevel,
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
        if (data && data.length > 0) {
            $("#navName").html(data[0].firstName + " " + data[0].lastName);
            $("#navNameMini").html(data[0].firstName + " " + data[0].lastName);
            if(data[0].img != null && data[0].img != ""){
                $("#imgNavHolder").attr("src", "data:image/jpeg;base64," + data[0].img);
                $("#imgNavHolderMini").attr("src", "data:image/jpeg;base64," + data[0].img);
            } else {
                $("#imgNavHolder").attr("src", "../assets/img/default.png");
                $("#imgNavHolderMini").attr("src", "../assets/img/default.png");
            }
            $("#navUserName").html(data[0].firstName + " " + data[0].lastName);
        } else {
            $("#currSY").html("");
        }
    })
    .catch(err => handleError(err));   
}

function getNotification(intervalId){
    $.ajax({
        url: "web/controller/adminController.php", 
        type: "GET",
        dataType: 'json',
        data: {
            type: "getNotification",
            profileID : getCookie("profileID")
        },
        success: function(fndata){
            var count = 0;
            $.each(fndata, function(key) {
                var x = JSON.parse(fndata[key].mailRecieverID);
                $.each(x, function(r){
                    if(x[r].profileID === getCookie("profileID") && x[r].isRead == 0){
                        count++;
                        $("#notif").addClass("border-radius-xl");
                        $("#notif").removeAttr("hidden");
                        $("#notifSide").addClass("border-radius-xl");
                        $("#notifSide").removeAttr("hidden");
                    } else {
                        $("#notif").attr("hidden","hidden");
                        $("#notifSide").attr("hidden","hidden");
                    }
                });
            
            });
            $("#notif").html(count);
            $("#notifSide").html(count);
        },
        error: function (err){
            console.log(err);
            clearInterval(intervalId);
            if(err.statusText == "error"){
                swalSimple("Error 503 : Service Unavailable","error", "Connection to server has been lost, please check internet connection.");                 
            } else {
               swalSimple(err.status +" "+ err.statusText,"error",err.responseText);      
            }
        }
    });
}
