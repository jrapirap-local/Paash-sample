<html>
    <head>
        <meta charset="utf-8" />
        <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/icons/department.png">
        <link rel="icon" type="image/png" href="../assets/img/icons/department.png">
        <title>Pedro Allegre Aure Senior High School Management System | MY PROFILE</title>
        <link rel="stylesheet" href="../core.css" />
        <?php include 'core.php';?>
        <?php include 'imageUploader.php';?>
    </head>
    <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
        <div class="app-wrapper">
            <?php include 'navigation.php';?>
            <?php include 'sidebar.php';?>
            <main class="app-main">
                <div class="app-content-header">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-start">
                                <li class="breadcrumb-item"><a href="controlpanel.php"><span class="link-success bi bi-house-fill"></span></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><b>My Profile</b></li>
                                <li class="breadcrumb-item active" aria-current="page" ><span id="adminUName"></span></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="app-content">
                    <div class="container-fluid">
                        <div class="card flex">
                            <!-- <div class="card-header">
                                <div class="">
                                    <span class="bg-dark">
                                        <i class="bi bi-gear-fill float-end"></i>
                                        <img id="imgProfile" height="250px" width="250px" class="rounded-circle shadow">
                                    </span>
                                </div>
                            </div> -->
                            <div class="card-body flex row">
                                <div class="col-lg-12 p-3 row">
                                    <div class="col-lg-3" id="imgProfile"></div>
                                    <div class="col-lg-9 col-lg row">
                                        <div class="col-lg-12 p-3">
                                            <h2>BASIC INFORMATION</h2>
                                            <div class="row">
                                                <div class="alert" role="alert" id="adminNotif">
                                                    Master Administrator
                                                </div>
                                                <div class="col-md-4 p-1">
                                                    <label class="font-weight-bold form-label">First Name <span class="text-danger text-small">*</span> : </label>
                                                    <input type="text" class="form-control" id="txtFirstName" readonly>
                                                </div>
                                                <div class="col-md-4 p-1">
                                                    <label class="font-weight-bold form-label">Middle Name : </label>
                                                    <input type="text" class="form-control" id="txtMiddleName" readonly>
                                                </div>
                                                <div class="col-md-4 p-1">
                                                    <label class="font-weight-bold form-label">Last Name <span class="text-danger text-small">*</span> : </label>
                                                    <input type="text" class="form-control" id="txtLastName" readonly>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-lg-12 p-3">
                                            <h2>SECURITY SETTINGS</h2>
                                            <div class="row">
                                                <div class="col-md-3 col-md-4" onclick="openUploader()">
                                                    <div class="small-box btn-alter btn-outline-success btn-sm">

                                                        <image src="../assets/img/icons/upload.png" class="small-box-icon object-fit"/>
                                                        <span class="small-box-footer link-underline-opacity-0 link-underline-opacity-50-hover">Upload Image</span>
                                                    </div>
                                                </div> 
                                                <div class="col-md-3 col-md-4" onclick="openProfileChanger()">
                                                    <div class="small-box btn-alter btn-outline-success btn-sm">

                                                        <image src="../assets/img/icons/user-details.png" class="small-box-icon "/>
                                                        <span class="small-box-footer link-underline-opacity-0 link-underline-opacity-50-hover">Change Profile</span>
                                                    </div>
                                                </div> 
                                                <div class="col-md-3 col-md-4" onclick="openPasswordChanger()">
                                                    <div class="small-box btn-alter btn-outline-success btn-sm">

                                                        <image src="../assets/img/icons/pin-code.png" class="small-box-icon "/>
                                                        <span class="small-box-footer link-underline-opacity-0 link-underline-opacity-50-hover">Update Password</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="app-footer">
                <div class="float-end d-none d-sm-inline">PAASHS '24</div>
                <strong>
                Copyright &copy; 2024-2025&nbsp; <a class="text-dark">Pedro Allegre Aure Senior High School</a>.
                </strong>
                All rights reserved.
            </footer> 
        </div>

        <!-- The Modal -->
        <div id="uploader" class="modal">
            <div class="modal-dialog">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post"  enctype="multipart/form-data">
                    <div class="modal-content p-3 card card-success card-outline">
                        <div class="modal-header">
                            <h4 class="text-dark font-weight-bold">UPLOAD PROFILE IMAGE</h4>
                        </div>
                        <div style="max-height: 50vh; overflow-y: auto;">
                        <div class="modal-body">
                                <div class="row" style="overflow-y: auto;">
                                    <div class="col-md-12">
                                        <div class="input-group mb-3">
                                            <input type="file" class="form-control" name="profileUpload" id="profileUpload" accept="image/*"/>
                                        </div>
                                    </div>
                                </div>
                            </div>               
                        </div>
                        <div class="modal-footer container-fluid">
                            <button type="submit" name="btnUploadProfileImg" class="btn btn-success input-group-text cursor-pointer" >Upload</button>
                            <a class="btn btn-danger" onclick="closeUploader()" class="btn btn-success text-white">Cancel</a>
                        </div>             
                    </div>
                </form>
            </div>
        </div>   
        <div id="profileChanger" class="modal">
            <div class="modal-dialog">
                <div class="modal-content p-3 card card-success card-outline">
                    <div class="modal-header">
                        <h4 class="text-dark font-weight-bold">UPDATE PROFILE INFO</h4>
                    </div>
                    <div style="max-height: 50vh; overflow-y: auto;">
                    <div class="modal-body">
                            <div class="row" style="overflow-y: auto;">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12 p-1">
                                            <label class="font-weight-bold form-label" for="txtFirstNameM">First Name <span class="text-danger text-small">*</span> :</label>
                                            <input type="text" class="form-control" id="txtFirstNameM" required>
                                        </div>
                                        <div class="col-md-12 p-1">
                                            <label class="font-weight-bold form-label" for="txtMiddleNameM">Middle Name :</label>
                                            <input type="text" class="form-control" id="txtMiddleNameM">
                                        </div>
                                        <div class="col-md-12 p-1">
                                            <label class="font-weight-bold form-label" for="txtLastNameM">Last Name <span class="text-danger text-small">*</span> :</label>
                                            <input type="text" class="form-control" id="txtLastNameM" required>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>               
                    </div>
                    <div class="modal-footer container-fluid">
                        <a name="btnUploadProfileImg" class="btn btn-success input-group-text cursor-pointer" onclick="updateAdminProfile()">Save</button>
                        <a class="btn btn-danger" onclick="closeProfileChanger()" class="btn btn-success text-white">Cancel</a>
                    </div>             
                </div>
            </div>
        </div> 
        <div id="updatePassword" class="modal">
            <div class="modal-dialog">
                <div class="modal-content p-3 card card-success card-outline">
                    <div class="modal-header">
                        <h4 class="text-dark font-weight-bold">UPDATE PASSWORD</h4>
                    </div>
                    <div style="max-height: 50vh; overflow-y: auto;">
                    <div class="modal-body">
                            <div class="row" style="overflow-y: auto;">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12 p-1">
                                            <label class="font-weight-bold form-label" for="txtpassold">Old Password <span class="text-danger text-small">*</span> :</label>
                                            <input type="password" class="form-control" id="txtpassold" name="txtpassold" autocomplete="false" aria-autocomplete="false" required>
                                        </div>
                                        <div class="col-md-12 p-1">
                                            <label class="font-weight-bold form-label" for="txtpassnew">New Password <span class="text-danger text-small">*</span> :</label>
                                            <input type="password" class="form-control" id="txtpassnew" name="txtpassnew" minlength="10" onchange="checkPassword()"  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{10,}" title="Must contain at least one number, one uppercase and lowercase letter, and at least 10 or more characters." autocomplete="false" aria-autocomplete="false" required>
                                        </div>
                                        <div class="col-md-12 p-1">
                                            <label class="font-weight-bold form-label" for="txtpassnew2">Re-type Password <span class="text-danger text-small">*</span> :</label>
                                            <input type="password" class="form-control" ID="txtpassnew2" name="txtpassnew2" onchange="checkIfMatch()" value="" required>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>               
                    </div>
                    <div class="modal-footer container-fluid">
                        <a class="btn btn-success input-group-text cursor-pointer" onclick="updatePass()">Save</button>
                        <a class="btn btn-danger" onclick="closePasswordChanger()" class="btn btn-success text-white">Cancel</a>
                    </div>             
                </div>
            </div>
        </div> 
    </body>
    <!-- <script src="web/myprofile.js"></script> -->
</html>