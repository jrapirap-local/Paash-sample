<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Project X</title>
  <?php require("core.php"); ?>
  <link rel="shortcut icon" href="assets/images/favicon.png" />
</head>
<body>
<script src="assets/js/preloader.js"></script>
  <div class="body-wrapper">
    <div class="main-wrapper">
      <div class="page-wrapper full-page-wrapper d-flex align-items-center justify-content-center">
        <main class="auth-page">
          <div class="prox-layout-grid">
            <div class="prox-layout-grid__inner">
              <div class="stretch-card prox-layout-grid__cell--span-4-desktop prox-layout-grid__cell--span-1-tablet"></div>
              <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-4-desktop prox-layout-grid__cell--span-6-tablet">
                <div class="prox-card">
                  <form>
                    <div class="prox-layout-grid">
                      <div class="prox-layout-grid__inner">
                        <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-12">
                          <h1 class="mb-0 offcanvas-title">SIGN IN</h1>
                        </div>                        
                        <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-12">
                          <div class="prox-text-field w-100">
                            <input class="prox-text-field__input" id="txtuname">
                            <div class="prox-line-ripple"></div>
                            <label for="text-field-hero-input" class="prox-floating-label">Username</label>
                          </div>
                        </div>
                        <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-12">
                          <div class="prox-text-field w-100">
                            <input class="prox-text-field__input" type="password" id="txtpass">
                            <div class="prox-line-ripple"></div>
                            <label for="text-field-hero-input" class="prox-floating-label">Password</label>
                          </div>
                        </div>
                        <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-6-desktop">
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
                            <label for="checkbox-1">Remember me</label>
                          </div>
                        </div>
                        <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-6-desktop d-flex align-items-center justify-content-end">
                          <a href="#">Forgot Password</a>
                        </div>
                        <div class="prox-layout-grid__cell stretch-card prox-layout-grid__cell--span-12">
                          <a onclick="getLoginInformation()" class="prox-button prox-button--raised w-100">
                            Login
                          </a>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="stretch-card prox-layout-grid__cell--span-4-desktop prox-layout-grid__cell--span-1-tablet"></div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="assets/js/material.js"></script>
  <script src="assets/js/misc.js"></script>
  <script src='assets/global.js'></script>
  <script src="web/index.js"></script>
</body>
</html>