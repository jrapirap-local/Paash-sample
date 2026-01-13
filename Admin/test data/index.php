<?php session_start(); ?>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/icons/department.png">
        <link rel="icon" type="image/png" href="../assets/img/icons/department.png">
        <title>School Management System | Control Panel</title>
        <link rel="stylesheet" href="../core.css" />
        <?php include 'core.php';?>
    </head>
    <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
        <div class="app-wrapper">
            <?php include 'navigation.php';?>
            <?php include 'sidebar.php';?>
            <main class="app-main">
                <div class="app-content-header">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
                                <ol class="breadcrumb float-sm-start">
                                    <li class="breadcrumb-item"><a href="index.php"><span class="link-success bi bi-house-fill"></span></a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Control Panel</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="app-content">
                    <div class="container-fluid">
                        <!-- #region start -->
                        <div class="card">
                            <div class="card-body flex">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-xl-4 col-12">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 p-2 cursor-pointer" onclick="addSchoolYear()">
                                                    <div class="btn btn-outline-success p-0">
                                                        <img class="card-img img-thumbnail" src="../assets/img/newSY.jpeg" style="opacity: 90%;"> 
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 p-2" onclick="gotolink('browser.php',$('#currSY').attr('syid'))" id="activeSYbtn" disabled>
                                                    <div class="card btn btn-success">
                                                        <div class="card-content">
                                                            <div class="card-body">
                                                                <h2><i id="currSY">NO ACTIVE SCHOOLYEAR</i></h2>
                                                                <i id="syErrHandler" class="card-subtitle mb-2 text-muted">Current active Schoolyear. Click to see Strand, Student and Class list.</i>
                                                            </div>
                                                            <div class="card-footer link-underline-opacity-0 link-underline-opacity-50-hover">
                                                                <a href="schoolyear.php" class="text-decoration-none">
                                                                    See list of Schoolyear <i class="bi bi-link-45deg"></i>
                                                                </a>                                                                
                                                            </div>
                                                        </div>  
                                                    </div>
                                                </div>                                                                                 
                                                
                                                <!-- <div class="col-sm-6 col-12">
                                                    <div class="card card-danger card-outline mb-4">
                                                        <div class="card-header">
                                                            <div class="card-title">Announcement</div>
                                                            <div class="float-sm-end"><a href="compose.php" class="btn btn-primary">Write</a></div>
                                                        </div>
                                                        <div class="card-body">
                                                            <p class="muted">NOTHING TO DISPLAY</p>
                                                        </div>
                                                        <div class="card-footer">
                                                            <div class=""><a class="cursor-pointer" onclick="openModalA();">View All</a></div>
                                                        </div>
                                                    </div>
                                                </div> -->
                                            </div>
                                        </div>
                                        <div class="col-xl-8 col-12 p-2">
                                            <div class="card card-outline card-success">
                                                <div class="card-content" style="max-height: 900px; overflow: auto; whitespce: no-wrap; display: inline;">
                                                    <div class="table-responsive p-3">
                                                        <table class="table table-hover align-items-center mb-0" id="tblNews">
                                                            <thead class="bg-dark text-warning text-center">
                                                                <tr>
                                                                    <th>News</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="text-xs text-dark mb-0 text-center">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- #endregion  -->
                    </div>
                </div>
            </main>
            <?php include "footer.php"; ?>          
        </div>
        <!-- The Modal -->
        <div id="writeAnnouncement" class="modal modal-lg">
            <div class="modal-dialog">
                <div class="modal-content p-3 card card-success card-outline">
                    <div class="modal-header">
                        <h4 class="text-dark font-weight-bold">Send a pinned Message</h4>
                    </div>
                    <div style="max-height: 50vh; overflow-y: auto;">
                    <div class="modal-body">
                            <div class="row" style="overflow-y: auto;">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12 p-1">
                                            <label class="font-weight-bold form-label" for="txtATitle">Title :</label>
                                            <input type="text" class="form-control" id="txtATitle" required>
                                        </div>
                                        <div class="col-md-12 p-1">
                                            <label class="font-weight-bold form-label">End Date <span class="text-danger text-small">*</span> :</label>
                                            <textarea class="form-control" rows="3" id="taAMessage" placeholder="Description" style="resize: none;"></textarea>
                                        </div>
                                    </div>     
                                </div>
                            </div>
                        </div>               
                    </div>
                    <div class="modal-footer container-fluid">
                        <a type="button" onclick="postAnnouncement()" class="btn btn-success text-white">Post</a>
                        <a class="btn btn-danger" onclick="closeModalA()" class="btn btn-success text-white">Cancel</a>
                    </div>             
                </div>
            </div>
        </div> 
        <div id="addSchoolYear" class="modal">
            <div class="modal-dialog">
                <div class="modal-content p-3 card card-success card-outline">
                    <div class="modal-header">
                        <h4 class="text-dark font-weight-bold">CREATE NEW SCHOOL YEAR</h4>
                    </div>
                    <div style="max-height: 50vh; overflow-y: auto;">
                        <div class="modal-body">
                            <div class="row" style="overflow-y: auto;">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6 p-1">
                                            <label class="font-weight-bold form-label">Start Date <span class="text-danger text-small">*</span> :</label>
                                            <input type="date" class="form-control" id="txtStartDate" required>
                                        </div>
                                        <div class="col-md-6 p-1">
                                            <label class="font-weight-bold form-label">End Date <span class="text-danger text-small">*</span> :</label>
                                            <input type="date" class="form-control" id="txtEndDate" required>
                                        </div>
                                        <div class="col-md-6 p-1">
                                            <label class="font-weight-bold form-label" for="chkActive">Make active School Year :</label>
                                            <input type="checkbox" class="form-control-check" id="chkActive" required>
                                        </div>
                                    </div>     
                                </div>
                            </div>
                        </div>               
                    </div>
                    <div class="modal-footer container-fluid">
                        <a type="button" onclick="createSchoolyear()" class="btn btn-success text-white">Save</a>
                        <a class="btn btn-danger" onclick="closeAddSchoolYear()" class="btn btn-success text-white">Cancel</a>
                    </div>             
                </div>
            </div>
        </div> 
    </body>
    
    <script src="web/controlpanel.js"></script>
</html>