<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Project X | My Profile</title>
  <link rel="stylesheet" href="../core.css" /> 
  <link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>
<body>

  <div class="body-wrapper">
    <!-- partial:partials/_sidebar.html -->
    <?php include("sidebar.php"); ?>
    <!-- partial -->
    <div class="main-wrapper prox-drawer-app-content">
      <!-- partial:partials/_navbar.html -->
      <?php include("navbar.php"); ?>
      <!-- partial -->
      <div class="page-wrapper prox-toolbar-fixed-adjust">
        <main class="content-wrapper">
          <div class="prox-layout-grid">
            <div class="prox-layout-grid__inner">
              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-3-desktop prox-layout-grid__cell--span-4-tablet">
                <div class="prox-card info-card info-card--danger">
                  <div class="card-inner">
                    <h5 class="card-title">Position</h5>
                    <h5 class="font-weight-light pb-2 mb-1 border-bottom">Master Administrator</h5>
                    <!-- <a href="schoolyear.php" class="tx-12 text-danger">Show list</a> -->
                    <div class="card-icon-wrapper">
                      <i class="material-icons">account_tree</i>
                    </div>
                  </div>
                </div>
              </div>              
              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-3-desktop prox-layout-grid__cell--span-4-tablet">
                <div class="prox-card info-card info-card--success">
                  <div class="card-inner">
                    <h5 class="card-title">Date Joined</h5>
                    <h5 class="font-weight-light pb-2 mb-1 border-bottom">01 - 27 - 2015</h5>
                    <!-- <a href="" class="tx-12 text-success">Show list</a> -->
                    <div class="card-icon-wrapper">
                      <i class="material-icons">groups</i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-3-desktop prox-layout-grid__cell--span-4-tablet">
                <div class="prox-card info-card info-card--primary">
                  <div class="card-inner">
                    <h5 class="card-title">App Mail</h5>
                    <h5 class="font-weight-light pb-2 mb-1 border-bottom">admin@prox.com</h5>
                    <!-- <a href="" class="tx-12 text-primary">Show list</a> -->
                    <div class="card-icon-wrapper">
                      <i class="material-icons">alternate_email</i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-3-desktop prox-layout-grid__cell--span-4-tablet">
                <div class="prox-card info-card info-card--info">
                  <div class="card-inner">
                    <h5 class="card-title">Last Login</h5>
                    <h5 class="font-weight-light pb-2 mb-1 border-bottom">15 minutes ago</h5>
                    <a href="" class="tx-12 text-muted">49.144.67.242</a>
                    <div class="card-icon-wrapper">
                      <i class="material-icons">history</i>
                    </div>
                  </div>
                </div>
              </div>   
              <div class="prox-layout-grid__cell prox-layout-grid__cell--span-3" style="max-height: 700px; overflow: hidden;">
                <div class="prox-card">
                  <div class="prox-layout-grid__inner p-2">
                    <div class="prox-layout-grid__cell prox-layout-grid__cell--span-12 prox-layout-grid__cell--span-12-tablet text-center">
                      <a href="" class="prox-button float-sm-end"><i class="material-icons prox-button__icon">upload</i> <span class="prox-typography--caption tx-10">Upload</span></a>
                        <div class="prox-layout-grid__inner d-flex justify-content-between align-items-center text-left">
                            <div class="prox-layout-grid__cell prox-layout-grid__cell--span-12">    
                                <img src="../assets/images/faces/face.png" class="" style="max-width:300px; overflow-y: auto;"> 
                            </div>
                        </div>
                    </div>
                    <div class="prox-layout-grid__cell prox-layout-grid__cell--span-12 prox-layout-grid__cell--span-12-tablet text-center"> 
                        <div class="prox-layout-grid__cell prox-layout-grid__cell--span-12-desktop text-right">
                            <div class="prox-layout-grid__inner">
                                <div class="prox-layout-grid__cell prox-layout-grid__cell--span-12 p-0 m-0">
                                    <span class="prox-typography--overline">Mr. Jeffrey Rapirap</span><br/>
                                    <span class="prox-typography--caption">Male</span><br/>
                                    <span class="prox-typography--caption text-danger">Jan 07, 1991</span><br/>
                                    <span class="prox-typography--caption">jrapirap27@gmail.com</span><br/>
                                    <span class="prox-typography--caption">(0915) 607 6895</span><br/>
                                </div>
                            </div>
                        </div> 
                    </div>
                  </div>
                </div> 
              </div>   
              <div class="prox-layout-grid__cell prox-layout-grid__cell--span-9 ">
                <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-12 pt-0 pb-3">
                  <div class="prox-text-field prox-text-field--fullwidth">
                    <div class="prox-card p-3 " style="border-radius:10px;">
                      <div id="editor" class="">
                        <p>Hello World!</p>
                        <p>Some initial <strong>bold</strong> text</p>
                        <p><br /></p>
                      </div>  
                      <div class="prox-card-footer pt-1"><button class="prox-button prox-button--raised filled-button--default float-sm-end">Post</button> </div>
                                   
                    </div>
                  </div>

                </div>                          
                <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-12">
                  <div  class="prox-card p-3" style="border-radius:10px;">
                    <div class="d-flex justify-content-between align-items-center"> 
                      <h4 class="card-title mb-2 mb-sm-0">My Tickets (WIP)</h4>
                    </div>
                    <div class="d-block d-sm-flex justify-content-between align-items-center">
                      <h6 class="card-sub-title mb-0">Open request and report tickets</h6>
                      <div class="prox-tab-wrapper revenue-tab prox-tab--secondary"> 
                        <div class="prox-tab-bar" role="tablist">
                          <div class="prox-tab-scroller">
                            <div class="prox-tab-scroller__scroll-area">
                              <div class="prox-tab-scroller__scroll-content">
                                <button class="prox-tab prox-tab" role="tab" aria-selected="true" tabindex="0">
                                  <span class="prox-tab__content">
                                    <span class="prox-tab__text-label">Open</span>
                                  </span>
                                  <span class="prox-tab-indicator prox-tab-indicator">
                                    <span class="prox-tab-indicator__content prox-tab-indicator__content--underline"></span>
                                  </span>
                                  <span class="prox-tab__ripple"></span>
                                </button>
                                <button class="prox-tab prox-tab" role="tab" aria-selected="true" tabindex="0">
                                  <span class="prox-tab__content">
                                    <span class="prox-tab__text-label">Closed</span>
                                  </span>
                                  <span class="prox-tab-indicator prox-tab-indicator">
                                    <span class="prox-tab-indicator__content prox-tab-indicator__content--underline"></span>
                                  </span>
                                  <span class="prox-tab__ripple"></span>
                                </button>
                                <button class="prox-tab prox-tab" role="tab" aria-selected="true" tabindex="0">
                                  <span class="prox-tab__content">
                                    <span class="prox-tab__text-label">Pending</span>
                                  </span>
                                  <span class="prox-tab-indicator prox-tab-indicator">
                                    <span class="prox-tab-indicator__content prox-tab-indicator__content--underline"></span>
                                  </span>
                                  <span class="prox-tab__ripple"></span>
                                </button>
                                <button class="prox-tab prox-tab" role="tab" aria-selected="true" tabindex="0">
                                  <span class="prox-tab__content">
                                    <span class="prox-tab__text-label">Cancelled</span>
                                  </span>
                                  <span class="prox-tab-indicator prox-tab-indicator">
                                    <span class="prox-tab-indicator__content prox-tab-indicator__content--underline"></span>
                                  </span>
                                  <span class="prox-tab__ripple"></span>
                                </button>
                                <button class="prox-tab prox-tab--active" role="tab" aria-selected="true" tabindex="0">
                                  <span class="prox-tab__content">
                                    <span class="prox-tab__text-label">ALL</span>
                                  </span>
                                  <span class="prox-tab-indicator prox-tab-indicator--active">
                                    <span class="prox-tab-indicator__content prox-tab-indicator__content--underline"></span>
                                  </span>
                                  <span class="prox-tab__ripple"></span>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="content content--active">    
                        </div>
                        <div class="content">
                        </div>
                        <div class="content">    
                        </div>
                        <div class="content">
                        </div>
                        <div class="content">
                        </div>
                      </div>
                    </div>
                    <div class="chart-container mt-4">
                        <div class="table-responsive">
                          <table class="table table-hoverable" id="tblTicketsInDashboard">
                            <thead>
                              <tr>
                                <th class="text-right">SENDER</th>
                                <th class="text-left">TITLE</th>
                                <th>Date</th>
                                <th>Status</th>
                              </tr>
                            </thead>                          
                            <tbody>
                              <tr>
                                <td class="text-right">admin@projectx.com</td>
                                <td class="text-left">THIS IS A CLOSED THREAD</td>
                                <td class="text-muted">12/18/2025</td>
                                <td><i class="text-muted">CLOSED</i></td>
                              </tr>
                              <tr>
                                <td class="text-right">admin@projectx.com</td>
                                <td class="text-left">THIS IS AN OPEN THREAD</td>
                                <td class="text-muted">12/18/2025</td>
                                <td><i class="text-success">OPEN</i></td>
                              </tr>
                              <tr>
                                <td class="text-right">admin@projectx.com</td>
                                <td class="text-left">THIS THREAD IS PENDING</td>
                                <td class="text-muted">12/18/2025</td>
                                <td><i class="text-warning">PENDING</i></td>
                              </tr>
                              <tr>
                                <td class="text-right">admin@projectx.com</td>
                                <td class="text-left">THIS THREAD HAS BEEN CANCELLED</td>
                                <td class="text-muted">12/18/2025</td>
                                <td><i class="text-danger">CANCELLED</i></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                    </div>
                  </div>
                </div>  
              </div>                      

            </div>
          </div>
        </main>
        <!-- partial:partials/_footer.html -->
        <?php require("footer.php"); ?>
        <!-- partial -->
      </div>
    </div>
  </div>
  <?php include("core.php"); ?>
  <script src="../assets/js/material.js"></script>
  <script src="../assets/js/misc.js"></script>
  <script src="web/dashboard.js"></script>
</body>
</html> 