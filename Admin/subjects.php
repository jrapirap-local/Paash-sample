<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Project X | Subjects</title>
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
                    <h4 class="card-title mb-2 mb-sm-0">Subjects</h4>
                  </div>
                  <!-- <div class="d-block d-sm-flex justify-content-between align-items-center">
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
                  </div> -->

                  <div class="chart-container mt-4">
                      <div class="table-responsive">
                        <table class="table table-hoverable" id="tblsubjects">
                          <thead>
                            <tr>
                              <th class="text-left">STRAND</th>
                              <th class="text-center">Subject</th>
                              <th>Gradelevel</th>
                              <th>First Sem</th>
                              <th>Second Sem</th>
                              <th>Active</th>
                            </tr>
                          </thead>                          
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

        <div id="editSubject" class="prox-modal">
            <div class="prox-modal-dialog">
                <div class="prox-modal-content p-3 card card-success card-outline">
                    <div class="prox-modal-body">
                      <div class="prox-card">
                        <form>
                          <div class="prox-layout-grid">
                            <div class="prox-layout-grid__inner">
                              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-12">
                                <h1 class="mb-0 offcanvas-title">EDIT SUBJECT</h1>
                              </div>                        
                              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-12">
                                <div class="prox-select w-100" data-prox-auto-init="MDCSelect">
                                  <input type="hidden" name="enhanced-select">
                                  <i class="prox-select__dropdown-icon"></i>
                                  <div class="prox-select__selected-text"></div>
                                  <div class="prox-select__menu prox-menu-surface">
                                    <ul class="prox-list">
                                      <li class="prox-list-item prox-list-item--selected" data-value="" aria-selected="true">
                                      </li>
                                      <li class="prox-list-item" data-value="grains">
                                        Bread, Cereal, Rice, and Pasta
                                      </li>
                                      <li class="prox-list-item" data-value="vegetables">
                                        Vegetables
                                      </li>
                                      <li class="prox-list-item" data-value="fruit">
                                        Fruit
                                      </li>
                                    </ul>
                                  </div>
                                  <span class="prox-floating-label">Strand</span>
                                  <div class="prox-line-ripple"></div>
                                </div>
                              </div>
                              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-12">
                                <div class="prox-text-field w-100">
                                  <input class="prox-text-field__input" type="text" id="txtpass">
                                  <div class="prox-line-ripple"></div>
                                  <label for="text-field-hero-input" class="prox-floating-label">Subject Name</label>
                                </div>
                              </div>
                              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-6-desktop align-items-center justify-content-start">
                                <h6 class="offcanvas-title">Gradelevel</h6>
                              </div>                              
                              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-6-desktop align-items-center justify-content-end">
                                <h6 class="offcanvas-title">Semester</h6>
                              </div>
                              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-6-desktop align-items-center justify-content-start">
                                <div class="prox-form-field">
                                  <div class="prox-checkbox">
                                    <input type="checkbox"
                                            class="prox-checkbox__native-control"
                                            id="checkbox-1"/>
                                    <div class="prox-checkbox__background">
                                      <svg class="prox-checkbox__checkmark"
                                            viewBox="0 0 24 24">
                                        <path class="prox-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                      </svg>
                                      <div class="prox-checkbox__mixedmark"></div>
                                    </div>
                                  </div>
                                  <label for="checkbox-1">Grade 11</label>                              
                                </div>
                              </div>
                              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-6-desktop align-items-center justify-content-end">
                                <div class="prox-form-field">  
                                  <div class="prox-checkbox">
                                    <input type="checkbox"
                                            class="prox-checkbox__native-control"
                                            id="checkbox-2"/>
                                    <div class="prox-checkbox__background">
                                      <svg class="prox-checkbox__checkmark"
                                            viewBox="0 0 24 24">
                                        <path class="prox-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                      </svg>
                                      <div class="prox-checkbox__mixedmark"></div>
                                    </div>
                                  </div>
                                  <label for="checkbox-2">Semester 1</label>                                  
                                </div>
                              </div>
                              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-6-desktop align-items-center justify-content-start">
                                <div class="prox-form-field">
                                  <div class="prox-checkbox">
                                    <input type="checkbox"
                                            class="prox-checkbox__native-control"
                                            id="checkbox-1"/>
                                    <div class="prox-checkbox__background">
                                      <svg class="prox-checkbox__checkmark"
                                            viewBox="0 0 24 24">
                                        <path class="prox-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                      </svg>
                                      <div class="prox-checkbox__mixedmark"></div>
                                    </div>
                                  </div>
                                  <label for="checkbox-1">Grade 12</label>                              
                                </div>
                              </div>
                              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-6-desktop align-items-center justify-content-end">
                                <div class="prox-form-field">  
                                  <div class="prox-checkbox">
                                    <input type="checkbox"
                                            class="prox-checkbox__native-control"
                                            id="checkbox-2"/>
                                    <div class="prox-checkbox__background">
                                      <svg class="prox-checkbox__checkmark"
                                            viewBox="0 0 24 24">
                                        <path class="prox-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                      </svg>
                                      <div class="prox-checkbox__mixedmark"></div>
                                    </div>
                                  </div>
                                  <label for="checkbox-2">Semester 2</label>                                  
                                </div>
                              </div>                              
                            </div>
                          </div>
                        </form>
                      </div>       
                    </div>
                    <div class="prox-modal-footer container-fluid">
                        <a class="prox-button prox-button--raised" id="btnModifySubjects" onclick="updateSubjects()">Update</a>
                        <a class="prox-button prox-button--raised filled-button--danger" id="delSub">Delete</a>
                        <a class="prox-button prox-button--raised filled-button--danger" onclick="closeEditModal()">Cancel</a>
                    </div>
                </div>                
            </div>
        </div>  

  <?php include("core.php"); ?>
  <script src="../assets/js/material.js"></script>
  <script src="../assets/js/misc.js"></script>
  <script src="web/subject.js"></script>
</body>
</html> 