<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block"><a href="../Admin" class="nav-link">Home</a></li>
            <li class="nav-item d-none d-md-block"><a href="inbox" class="nav-link">Inbox <i id="notif" class="badge"></i></a></li>
        </ul>
        <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown user-menu ">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
              <span class="d-none d-md-inline link">Welcome! <img id="imgNavHolderMini" height="20px" width="20px" class="rounded-circle shadow"> <span id="navNameMini"></span></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <li class="user-header text-bg-success">
                <img id="imgNavHolder" height="20px" width="20px" class="rounded-circle shadow">
                <p>
                  <span id="navName"></span>
                  <small>Administrator</small>
                </p>
              </li>
              <li class="user-body">
                <div class="row">
                  <div class="col-4 text-center"><a href="myprofile.php" class="link link-primary link-underline-opacity-0 link-underline-opacity-50-hover">Profile</a></div>
                  <!-- <div class="col-4 text-center"><a href="#">Sales</a></div>
                  <div class="col-4 text-center"><a href="#">Friends</a></div> -->
                </div>
              </li>
              <li class="user-footer p-1">
                <!-- <a href="myProfile.php" class="btn link-primary link-underline-opacity-0 link-underline-opacity-50-hover">Profile</a> -->
                <!-- <a href="inbox.php" class="btn link-primary link-underline-opacity-0 link-underline-opacity-50-hover">Inbox</a> -->
                <a href="#" class="btn btn-outline-danger float-end" onclick="logOut()">Sign out</a>
              </li>
            </ul>
        </li>         
        </ul>
    </div>
</nav> 
<script src="web/navigation.js"></script>