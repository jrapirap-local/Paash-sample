<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Project X | Schoolyear</title>
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
              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-12">
                <div class="prox-card">
                  <div class="d-flex justify-content-between align-items-center"> 
                    <h4 class="card-title mb-2 mb-sm-0">Schoolyear (WIP)</h4>
                  </div>
                  <div class="d-block d-sm-flex justify-content-between align-items-center">
                    <h6 class="card-sub-title mb-0"></h6>
                    <div class="prox-tab-wrapper revenue-tab prox-tab--secondary"> 
                      <div class="prox-tab-bar" role="tablist">
                        <div class="prox-tab-scroller">
                          <div class="prox-tab-scroller__scroll-area">
                            <div class="prox-tab-scroller__scroll-content">
                              <a onclick="console.log('Create New SY');" class="prox-tab prox-tab" role="tab" aria-selected="true" tabindex="0">
                                <span class="prox-tab__content">
                                  <span class="prox-tab__text-label">NEW</span>
                                </span>
                                <span class="prox-tab-indicator prox-tab-indicator">
                                  <span class="prox-tab-indicator__content prox-tab-indicator__content--underline"></span>
                                </span>
                                <span class="prox-tab__ripple"></span>
                              </a>
                              <button class="prox-tab prox-tab" role="tab" aria-selected="true" tabindex="0">
                                <span class="prox-tab__content">
                                  <span class="prox-tab__text-label">CLOSE</span>
                                </span>
                                <span class="prox-tab-indicator prox-tab-indicator">
                                  <span class="prox-tab-indicator__content prox-tab-indicator__content--underline"></span>
                                </span>
                                <span class="prox-tab__ripple"></span>
                              </button>
                              <button class="prox-tab prox-tab" role="tab" aria-selected="true" tabindex="0">
                                <span class="prox-tab__content">
                                  <span class="prox-tab__text-label">EDIT</span>
                                </span>
                                <span class="prox-tab-indicator prox-tab-indicator">
                                  <span class="prox-tab-indicator__content prox-tab-indicator__content--underline"></span>
                                </span>
                                <span class="prox-tab__ripple"></span>
                              </button>
                              <button class="prox-tab prox-tab" role="tab" aria-selected="true" tabindex="0">
                                <span class="prox-tab__content">
                                  <span class="prox-tab__text-label">DELETE</span>
                                </span>
                                <span class="prox-tab-indicator prox-tab-indicator">
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
                        <table class="table table-hoverable" id="tblschoolyear">
                          <thead>
                            <tr>
                              <th class="text-center">SCHOOLYEAR</th>
                              <th class="text-right">Start Date</th>
                              <th class="text-left">End Date</th>
                              <th>Status</th>
                            </tr>
                          </thead>                          
                          <tbody>
                            <tr>
                              <td class="text-center">2024 - 2025</td>
                              <td class="text-right">12/18/2024</td>
                              <td class="text-muted text-left">12/18/2025</td>
                              <td><i class="text-success">CURRENT - ACTIVE</i></td>
                            </tr>
                            <tr>
                              <td class="text-center">2024 - 2025</td>
                              <td class="text-right">12/18/2024</td>
                              <td class="text-muted text-left">12/18/2025</td>
                              <td><i class="text-danger">CLOSED</i></td>
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
  <script src="../assets/js/material.js"></script>
  <script src="../assets/js/misc.js"></script>
  <script src="web/dashboard.js"></script>
</body>
</html> 