var tblSubjectList = $('#tblsubjects').DataTable();
var modalAdd = document.getElementById("addSubject"); 
var modalEdit = document.getElementById("editSubject");   

$(function(){
    if(checkLoginIsValid()){
        
            getSubjectList();
            // getTrackforSubjectStrand('ddTrack');
            // getStrandsForSubject('ddStrands');
            // getTrackforSubjectStrand('mddTrack');
            // getStrandsForSubject('mddStrands');
    }

});

function checkLoginIsValid(){
    const category = "checkLoginIsValid";
    const profileID = getCookie("profileID");
    let ip = "";
    $.getJSON("https://api.ipify.org/?format=json", function(e) {
        ip = e.ip;
    });  

    const params = new URLSearchParams({
        category: category,
        profileID: profileID,
        ip: ip
    }); 

    return fetch(`web/c/controller.php?${params.toString()}`, {
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

function getSubjectList(){
    const schoolyearID = getCookie("syid")!=null? atob(getCookie("syid")) : 0;
    const category = "getSubjectList";

    const params = new URLSearchParams({
        schoolyearID: schoolyearID,
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
                let gradelevel = "";
                let fstSem = "";
                let sndSem = "";
                let opt = "";
                if(fndata[key].gradelevelID == 11){
                    gradelevel = '11';
                } else if (fndata[key].gradelevelID == 12) {
                    gradelevel = '12';
                } else {
                    gradelevel = '11 and 12';
                }
                if(fndata[key].firstSem == 1){
                    fstSem = '<i class="text-success material-icons">check</i>';
                } else {
                    fstSem = '<i class="text-danger material-icons">close</i>';
                }
                if(fndata[key].secondSem == 1){
                    sndSem = '<i class="text-success material-icons">check</i>';
                } else {
                    sndSem = '<i class="text-danger material-icons">close</i>';
                }
                if(fndata[key].isActive == 0){
                    opt = '<i class="text-danger material-icons">close</i>';
                } else {
                    opt = '<i class="text-success material-icons">check</i>';
                }
                var newRow = '<tr class="cursor-pointer" onclick="showEditModal('+fndata[key].subjectID+')"><td class="text-right">'+fndata[key].strand+'</td><td class="text-left">'+fndata[key].subject+'</td><td>'+gradelevel+'</td><td>'+fstSem+'</td><td>'+sndSem+'</td><td>'+opt+'</td></tr>';
                tblSubjectList.row
                .add($(newRow)).draw();
            });            
        }
    })
    .catch(err => handleError(err)); 
}

function showEditModal(id){;
    console.log(id);
    setCookie("subjectID",id, "1");
    $('#mgl11').prop('checked', false);
    $('#mgl12').prop('checked', false);
    //getSubjectInfo(id);
    modalEdit.style.display = "block";
}

function getTrackforSubjectStrand(obj){
    $('#'+obj).html("");
    $('#'+obj).prop("disabled", false);
    $.ajax({
        url: "web/controller/adminController.php", 
        type: "GET",
        dataType: 'json',
        data: {
            type: "getTrackforSubjectStrand"
        },
        success: function(fndata){
            $.each(fndata, function(key) { 
                $('#'+obj).append(new Option(fndata[key].trackName, fndata[key].trackID));   
            });   
        },
        error: function (err){
            errHandler = errorGlobal.find(x => x.errCode === err.responseText);
            if(errHandler){
                swalSimple(errHandler.title.replaceAll("[object]","Strand(s)"),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]","Strand(s)"));
                $('#'+obj).append(new Option("NO STRAND AVAILABLE"),'INFO_NOLIST'); 
                $('#'+obj).prop("disabled", true);
            } else {
                console.log(err);
                if(err.statusText == "error"){
                    swalSimple("Error 503 : Service Unavailable","error", "A server-side error has occurred. Please try again later. If issue persist please report to Admin.");                 
                } else {
                   swalSimple(err.status +" "+ err.statusText,"error",err.responseText);      
                }
                $('#'+obj).append(new Option("ERROR","INFO_NOLIST"));   
                $('#'+obj).prop("disabled", true);
            }   
        }
    });
}

function getStrandsForSubject(obj){
    $('#'+obj).html("");
    $('#'+obj).prop("disabled", false);
    $.ajax({
        url: "web/controller/adminController.php", 
        type: "GET",
        dataType: 'json',
        data: {
            type: "getStrandsForSubject"

        },
        success: function(fndata){
            $('#'+obj).append(new Option('CORE', '1001')); 
            $('#'+obj).append(new Option('APPLIED/SELECTIVE', '1002')); 
            $.each(fndata, function(key) { 
                $('#'+obj).append(new Option(fndata[key].strand, fndata[key].strandID));   
            });     
        },
        error: function (err){
            console.log(err);
            errHandler = errorGlobal.find(x => x.errCode === err.responseText);
            if(errHandler){
                swalSimple(errHandler.title.replaceAll("[object]",$('#ddTrack option:selected').text()),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]",$('#ddTrack option:selected').text()));
                $('#'+obj).append(new Option("NO STRAND AVAILABLE"),'INFO_NOLIST'); 
                $('#'+obj).prop("disabled", true);
            } else {
                console.log(err);
                if(err.statusText == "error"){
                    swalSimple("Error 503 : Service Unavailable","error", "A server-side error has occurred. Please try again later. If issue persist please report to Admin.");                 
                } else {
                   swalSimple(err.status +" "+ err.statusText,"error",err.responseText);      
                }
                $('#'+obj).append(new Option("ERROR","INFO_NOLIST"));   
                $('#'+obj).prop("disabled", true);
            }   
        }
    });    
}

function getSubjectInfo(id){ 
    $('#mgl11').prop('checked', false);
    $('#mgl12').prop('checked', false);
    $('#mcb1').prop('checked', false);
    $('#mcb2').prop('checked', false);
    $.ajax({
        url: "web/controller/adminController.php", 
        type: "GET",
        dataType: 'json',
        data: {
            type: "getSubjectInfo",
            subjectID: id
        },
        success: function(fndata){
            $('#delSub').html("");
            if(fndata[0].isActive == "1"){
                $('#btnModifySubjects').removeClass("disabled");
                $('#mEd').attr("disabled",false);
                $('#delSub').removeClass("btn btn-success");
                $('#delSub').addClass("btn btn-danger");
                $('#delSub').html("Delete");
                $('#delSub').attr("onclick","deactivate('"+id+"')");
            } else {
                $('#btnModifySubjects').addClass("disabled");
                $('#mEd').attr("disabled",true);
                $('#delSub').removeClass("btn btn-danger");
                $('#delSub').addClass("btn btn-success");
                $('#delSub').html("Activate");
                $('#delSub').attr("onclick","activate('"+id+"')");
            }
            $('#mtxtSubjectName').val(fndata[0].subject);
            $('#mddStrands').val(fndata[0].strandID); 
            if(fndata[0].gradelevelID == '11, 12'){
                $('#mgl11').prop('checked', true);
                $('#mgl12').prop('checked', true);
            } else if (fndata[0].gradelevelID == '11'){ 
                $('#mgl11').prop('checked', true);
                $('#mgl12').prop('checked', false);
            } else if (fndata[0].gradelevelID == '12'){
                $('#mgl11').prop('checked', false);
                $('#mgl12').prop('checked', true);
            } else {
                $('#mgl11').prop('checked', false);
                $('#mgl12').prop('checked', false);
            }
            if(fndata[0].firstSem == 1){
                $('#mcb1').prop('checked', true);
            }
            if(fndata[0].secondSem == 1){
                $('#mcb2').prop('checked', true);
            }
            $('#mtaDescription').val(fndata[0].description);
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

function updateSubjects(){
    console.log($('#mcb1').is(':checked'));
    console.log($('#mcb2').is(':checked'));
    Swal.fire({
        icon: 'question',
        title: 'Update Subject details?',
        text: 'Saving will update entry from database, this does not include if subject is already been used. You may have to manually update Strand, Faculty, Student and Class module.',
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: 'Update',
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "web/controller/adminController.php", 
                type: "GET",
                dataType: 'json',
                data: {
                    type: "updateSubjects",
                    object: {
                        strandID: $('#mddStrands').val(),
                        subjectID: getCookie("subjectID"),
                        subjectName: $('#mtxtSubjectName').val(),
                        grade11: $('#mgl11').is(':checked'),
                        grade12: $('#mgl12').is(':checked'),
                        firstSem: $('#mcb1').is(':checked'),
                        secondSem: $('#mcb2').is(':checked'),
                        description: $('#mtaDescription').val()
                    }
                },
                success: function(fndata){
                    console.log(fndata);
                    errHandler = errorGlobal.find(x => x.errCode === fndata.responseText);
                    if(errHandler){
                        swalSimple(errHandler.title.replaceAll("[object]",'Subject Name'),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]",'Subject Name'));
                    } else {
                        swalSimple("Subject Updated","success","");
                        closeEditModal();
                        showSubjectList();
                    }
                    
                },
                error: function (err){
                    errHandler = errorGlobal.find(x => x.errCode === err.responseText);
                    if(errHandler){
                        swalSimple(errHandler.title.replaceAll("[object]","Subject Name"),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]","Subject Name"));
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

function deactivate(id){
    Swal.fire({
        icon: 'question',
        title: 'Are you sure you want to deactivate this Subject?',
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: 'Deactivate',
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "web/controller/adminController.php", 
                type: "GET",
                dataType: 'json',
                data: {
                    type: "deactivateSubjects",
                    subjectID: id,

                },
                success: function(fndata){
                    errHandler = errorGlobal.find(x => x.errCode === fndata.responseText);
                    if(errHandler){
                        swalSimple(errHandler.title.replaceAll("[object]",'Subject Name'),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]",'Subject Name'));
                    } else {
                        swalSimple("Subject Deactivated","success","");
                        showSubjectList();
                        closeEditModal();
                    }
                    
                },
                error: function (err){
                    errHandler = errorGlobal.find(x => x.errCode === err.responseText);
                    if(errHandler){
                        swalSimple(errHandler.title.replaceAll("[object]","Subject Name"),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]","Subject Name"));
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

function activate(id){
    Swal.fire({
        icon: 'question',
        title: 'Are you sure you want to activate this Subject?',
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: 'Activate',
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "web/controller/adminController.php", 
                type: "GET",
                dataType: 'json',
                data: {
                    type: "activateSubjects",
                    subjectID: id
                },
                success: function(fndata){
                    errHandler = errorGlobal.find(x => x.errCode === fndata.responseText);
                    if(errHandler){
                        swalSimple(errHandler.title.replaceAll("[object]",'Subject Name'),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]",'Subject Name'));
                    } else {
                        swalSimple("Subject Activated","success","");
                        showSubjectList();
                        closeEditModal();
                    }
                    
                },
                error: function (err){
                    errHandler = errorGlobal.find(x => x.errCode === err.responseText);
                    if(errHandler){
                        swalSimple(errHandler.title.replaceAll("[object]","Subject Name"),errHandler.errSevererity,errHandler.errMsg.replaceAll("[object]","Subject Name"));
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

function closeEditModal(){
    modalEdit.style.display = "none";
}

function addSubject(){   
    modalAdd.style.display = "block";
}

function validateSubject(){
    var ret = true;
    if($('#txtSubjectName').val()==""){
        $('#txtSubjectName').addClass("border-1 border-danger");
        ret = false;
    }
    if(!$('#gl11').is(":checked") && !$('#gl12').is(":checked")){
        $('#gl11').addClass("border-1 border-danger");
        $('#gl12').addClass("border-1 border-danger");
        ret = false;
    }
    if(!$('#cb1').is(":checked") && !$('#cb2').is(":checked")){
        $('#cb1').addClass("border-1 border-danger");
        $('#cb2').addClass("border-1 border-danger");
        ret = false;
    }
    return ret;
}

function createNewSubject() {
    if (validateSubject()) {
        // Use a cleaner way to check checkboxes (Boolean → Number)
        const gl11 = +$("#gl11").is(":checked");
        const gl12 = +$("#gl12").is(":checked");
        const cb1  = +$("#cb1").is(":checked");
        const cb2  = +$("#cb2").is(":checked");
        $.ajax({
            url: "web/controller/adminController.php", 
            type: "GET",
            dataType: "json",
            data: {
                type: "createNewSubject",
                object: {
                    strandID:    $('#ddStrands').val(),
                    subjectName: $('#txtSubjectName').val(),
                    grade11:     gl11,
                    grade12:     gl12,
                    firstSem:    cb1,
                    secondSem:   cb2,
                    description: $('#taDescription').val()
                }
            },
            success: function(fndata) {
                const errHandler = errorGlobal.find(x => x.errCode === fndata.responseText);
                if (errHandler) {
                    swalSimple(
                        errHandler.title.replaceAll("[object]", "Subject Name"),
                        errHandler.errSevererity,
                        errHandler.errMsg.replaceAll("[object]", "Subject Name")
                    );
                } else {
                    swalSimple("Subject Added", "success", "");
                    closeAddModal();
                    showSubjectList();
                }
            },
            error: function(err) {
                const errHandler = errorGlobal.find(x => x.errCode === err.responseText);
                if (errHandler) {
                    swalSimple(
                        errHandler.title.replaceAll("[object]", "Subject Name"),
                        errHandler.errSevererity,
                        errHandler.errMsg.replaceAll("[object]", "Subject Name")
                    );
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
        swalSimple("Please check form", "error", "Please ensure all required fields have been filled.");
    }
}

function closeAddModal(){
    modalAdd.style.display = "none";
}
