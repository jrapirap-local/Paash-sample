var tblStrandInDashboard = $('#tblStrandInDashboard').DataTable({paging: false, info: false, sort: false, "lengthChange" : true, searching: false});
var tblNewsInDashboard = $('#tblNewsInDashboard').DataTable({paging: false, info: false, sort: false, "lengthChange" : true, searching: false});
var tblQuickMail = $('#tblQuickMail').DataTable({paging: false, info: false, sort: false, "lengthChange" : true, searching: false});
$('#tblTicketsInDashboard').DataTable({paging: false, info: false, sort: false, "lengthChange" : true, searching: false});

//   const quill = new Quill('#editor', {
//     theme: 'snow'
//   });
  
$(function(){
    if(checkLoginIsValid()){
        getCurrentActiveSchoolyear();
        getCurrentSYActiveStrandAndDetails();
        getNewsForDashboard();
        getUnreadMailInDashboard();
    }

});

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
        if (data.status=="SUCCESS") {
            setCookie("profileID", data.user.profileID, "1");
            setCookie("accntLevel", data.user.accntLevel, "1");
            return true;           
        } else {
            deleteCookie("profileID"); 
            swalMsg('NOTICE','danger','Profile validation has failed! Your session may have been expired or has an invalid token. Try logging in again.','../index.php');  
            return false;        
        }
    })
    .catch(err => handleError(err));
}

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
    .then(fndata => {
        console.log(fndata);
        if (fndata && fndata.length > 0) {
            const sy = fndata[0];
            $("#currSY").html(`${sy.startYear} - ${sy.endYear}`);
            $("#currSY").attr("syid", sy.schoolyearID);
            $("#activeSYbtn").attr("disabled", false);
        } else {
            $("#currSY").html("NO ACTIVE SY");
        }
    })
    .catch(err => handleError(err));
   
}

function getCurrentSYActiveStrandAndDetails(){
    const schoolyearId = getCookie("syid")!=null? atob(getCookie("syid")) : 0;
    const category = "getCurrentSYActiveStrandAndDetails";

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
    .then(fndata => {
        if (fndata && fndata.length > 0) {
            $.each(fndata, function(key){
                var newRow = '<tr class="cursor-pointer"><td>'+fndata[key].strandID+'</td><td>'+fndata[key].strand+'</td><td>0</td><td>0</td></tr>';
                tblStrandInDashboard.row
                .add($(newRow)).draw();
                });            
        }
    })
    .catch(err => handleError(err)); 
}

function getNewsForDashboard(){
    const category = "getNewsForDashboard";

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
    .then(fndata => {
        if (fndata && fndata.length > 0) {
            // $.each(fndata, function(key){
            //     var newRow = '<tr class="cursor-pointer"><td>'+fndata[key].strandID+'</td><td>'+fndata[key].strand+'</td><td>0</td><td>0</td></tr>';
            //     tblNewsInDashboard.row
            //     .add($(newRow)).draw();
            //     });            
        }
    })
    .catch(err => handleError(err)); 
}

function getUnreadMailInDashboard(){
    const category = "getUnreadMailForDashboard";
    const profileID = getCookie("profileID");

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
    .then(fndata => {
        if (fndata && fndata.length > 0) {
            // $.each(fndata, function(key){
            //     var newRow = '<tr class="cursor-pointer"><td>'+fndata[key].strandID+'</td><td>'+fndata[key].strand+'</td><td>0</td><td>0</td></tr>';
            //     tblQuickMail.row
            //     .add($(newRow)).draw();
            //     });            
        }
    })
    .catch(err => handleError(err)); 
}