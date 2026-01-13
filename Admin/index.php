<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Project X | Dashboard</title>
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
                    <h5 class="card-title">School Year</h5>
                    <h5 class="font-weight-light pb-2 mb-1 border-bottom" id="currSY">2024 - 2025</h5>
                    <a href="schoolyear.php" class="tx-12 text-danger">Show list</a>
                    <div class="card-icon-wrapper">
                      <i class="material-icons">school</i>
                    </div>
                  </div>
                </div>
              </div>              
              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-3-desktop prox-layout-grid__cell--span-4-tablet">
                <div class="prox-card info-card info-card--success">
                  <div class="card-inner">
                    <h5 class="card-title">Students</h5>
                    <h5 class="font-weight-light pb-2 mb-1 border-bottom">0</h5>
                    <a href="" class="tx-12 text-success">Show list</a>
                    <div class="card-icon-wrapper">
                      <i class="material-icons">supervised_user_circle</i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-3-desktop prox-layout-grid__cell--span-4-tablet">
                <div class="prox-card info-card info-card--primary">
                  <div class="card-inner">
                    <h5 class="card-title">Open Tickets</h5>
                    <h5 class="font-weight-light pb-2 mb-1 border-bottom">1</h5>
                    <a href="" class="tx-12 text-primary">Show list</a>
                    <div class="card-icon-wrapper">
                      <i class="material-icons">assignment</i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-3-desktop prox-layout-grid__cell--span-4-tablet">
                <div class="prox-card info-card info-card--info">
                  <div class="card-inner">
                    <h5 class="card-title">Latest Database</h5>
                    <h5 class="font-weight-light pb-2 mb-1 border-bottom">15 minutes ago</h5>
                    <a href="" class="tx-12 text-info">Show list</a>
                    <div class="card-icon-wrapper">
                      <i class="material-icons">cloud_sync</i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-8">
                <div class="prox-card">
                  <div class="d-flex justify-content-between">
                    <h4 class="card-title mb-0">Strands</h4>
                  </div>
                  <div class="d-block d-sm-flex justify-content-between align-items-center">
                      <h5 class="card-sub-title mb-2 mb-sm-0">List of active strands for the current school year</h5>
                      <h5 class="font-weight-light pb-2 mb-1 border-bottom"></h5>
                      <div class="menu-button-container">
                        <div class="prox-menu prox-menu-surface" tabindex="-1">
                          <ul class="prox-list" role="menu" aria-hidden="true" aria-orientation="vertical">
                            <li class="prox-list-item" role="menuitem">
                              <h6 class="item-subject font-weight-normal">Back</h6>
                            </li>
                            <li class="prox-list-item" role="menuitem">
                              <h6 class="item-subject font-weight-normal">Forward</h6>
                            </li>
                            <li class="prox-list-item" role="menuitem">
                              <h6 class="item-subject font-weight-normal">Reload</h6>
                            </li>
                            <li class="prox-list-divider"></li>
                            <li class="prox-list-item" role="menuitem">
                              <h6 class="item-subject font-weight-normal">Save As..</h6>
                            </li>
                          </ul>
                        </div>
                      </div>
                  </div>
                  <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-12">
                    <div class="prox-card p-0">
                      <h6 class="card-title card-padding pb-0"></h6>
                        <div class="table-responsive">
                          <table class="table table-hoverable" id="tblStrandInDashboard">
                            <thead>
                              <tr>
                                <th class="text-left">Strand ID</th>
                                <th>Strand Name</th>
                                <th>Active Classes</th>
                                <th>Active Students</th>
                              </tr>
                            </thead>
                          </table>
                        </div>
                    </div>
                  </div>
                </div> 
              </div>              
              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-4" style="height: 300px; max-height:500px; overflow-y: auto;">
                <div class="prox-card">
                  <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-12">
                    <div class="prox-card p-0">
                      <h6 class="card-title pb-0"></h6>
                        <div class="table-responsive">
                          <table class="table table-hoverable" id="tblNewsInDashboard">
                            <thead>
                              <tr>
                                <th class="text-center">NEWS (WIP)</th>
                              </tr>
                            </thead>
                          </table>
                        </div>
                    </div>
                  </div>
                </div> 
              </div>
              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-4">
                <div class="prox-card">
                  <div class="d-flex justify-content-between">
                    <h4 class="card-title mb-0">Quick Mail (WIP)</h4>
                    <div>
                        <i class="material-icons refresh-icon">refresh</i>
                        <i class="material-icons options-icon ml-2">more_vert</i>
                    </div>
                  </div>
                  <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-12">
                    <div class="prox-card p-0">
                      <h6 class="card-title pb-0"></h6>
                        <div class="table-responsive">
                          <table class="table table-hoverable" id="tblQuickMail">
                            <thead>
                              <tr>
                                <th class="text-center">Sender</th>
                                <th class="text-center">Title</th>
                              </tr>
                            </thead>
                          </table>
                        </div>
                    </div>
                  </div>                  
                </div> 
              </div>              
              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-8">
                <div class="prox-card">
                  <div class="d-flex justify-content-between align-items-center"> 
                    <h4 class="card-title mb-2 mb-sm-0">Tickets (WIP)</h4>
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
        </main>
        <!-- partial:partials/_footer.html -->
        <?php require("footer.php"); ?>
        <!-- partial -->
      </div>
    </div>
  </div>
  <?php include("core.php"); ?>
  <!-- <script src="../assets/js/material.js"></script> -->
  <script src="../assets/js/misc.js"></script>
  <script src="web/dashboard.js"></script>
</body>
</html> 