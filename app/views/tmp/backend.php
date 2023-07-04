<!DOCTYPE html>
<!--
Template Name: NobleUI - HTML Bootstrap 5 Admin Dashboard Template
Author: NobleUI
Purchase: https://1.envato.market/nobleui_admin
Website: https://www.nobleui.com
Portfolio: https://themeforest.net/user/nobleui/portfolio
Contact: nobleui123@gmail.com
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="Covid PIMS">
    <meta name="author" content="NobleUI">
    <meta name="keywords" content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <title><?php

  use Services\UserService;
  load(['UserService'], SERVICES);
 echo $page_title ?? COMPANY_NAME?></title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
  <!-- End fonts -->

<!-- core:css -->
<link rel="stylesheet" href="<?php echo _path_tmp('assets/vendors/core/core.css')?>">
<!-- endinject -->

<!-- Plugin css for this page -->
  <link rel="stylesheet" href="<?php echo _path_tmp('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css')?>">
    <!-- End plugin css for this page -->

<!-- inject:css -->
<link rel="stylesheet" href="<?php echo _path_tmp('assets/fonts/feather-font/css/iconfont.css')?>">
<link rel="stylesheet" href="<?php echo _path_tmp('assets/vendors/flag-icon-css/css/flag-icon.min.css')?>">
<!-- endinject -->

<!-- Layout styles -->  
<link rel="stylesheet" href="<?php echo _path_tmp('assets/css/demo3/style.css')?>">
<!-- End layout styles -->
<link rel="shortcut icon" href="<?php echo _path_tmp('assets/images/favicon.png')?>" />
  <?php produce('styles')?>
</head>
<body>
    <?php $auth = auth()?>
    <?php
        $isVendorManagement = authPropCheck(UserService::ACCESS_VENDOR_MANAGEMENT);
        $isPlatformManagement = authPropCheck(UserService::ACCESS_PLATFORM_MANAGEMENT);
        $isPlatformStaff = authPropCheck(UserService::ACCESS_PLATFORM_STAFF);
        ?>
    <div class="main-wrapper">
        <!-- partial:../../partials/_navbar.html -->
        <div class="horizontal-menu">
            <nav class="navbar top-navbar">
                <div class="container">
                    <div class="navbar-content">
                        <?php if(!$isVendorManagement) :?>
                          <a href="#" class="navbar-brand">
                              <?php echo whoIs('parent_name')?>
                            </a>
                        <?php else:?>
                        <a href="#" class="navbar-brand">
                            <?php echo COMPANY_NAME?>
                        </a>
                        <?php endif?>
                        <?php if($auth) :?>
                            <?php $notifications = _notify_pull_items($auth->id)?>
                            <form class="search-form">
                                <div class="input-group">
                                    <div class="input-group-text">
                                      <i data-feather="search"></i>
                                    </div>
                                    <input type="text" class="form-control" id="navbarForm" placeholder="Search here...">
                                </div>
                            </form>
                            <ul class="navbar-nav">
                                  <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i data-feather="bell"></i>
                                      <?php if($notifications) :?>
                                      <div class="indicator">
                                        <div class="circle"></div>
                                      </div>
                                      <?php endif?>
                                    </a>
                                    <?php if($notifications) :?>
                                    <div class="dropdown-menu p-0" aria-labelledby="notificationDropdown">
                                      <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
                                        <p>(<?php echo count($notifications) ?>) Notification</p>
                                        <a href="javascript:;" class="text-muted">Clear all</a>
                                      </div>
                                      <div class="p-1">
                                        <?php foreach($notifications as $key => $row) :?>
                                          <a href="<?php echo $row->href ?>" class="dropdown-item d-flex align-items-center py-2">
                                          <div class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-danger rounded-circle me-3">
                                            <i class="icon-sm text-white" data-feather="alert-circle"></i>
                                          </div>
                                          <div class="flex-grow-1 me-2">
                                            <p><?php echo $row->message?></p>
                                            <p class="tx-12 text-muted">30 min ago</p>
                                          </div>    
                                        </a>
                                        <?php endforeach?>
                                      </div>
                                      <div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
                                        <a href="javascript:;">View all</a>
                                      </div>
                                    </div>
                                    <?php endif?>
                                  </li>
                              <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="<?php echo _route('user:show' , $auth->id)?>" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <img class="wd-30 ht-30 rounded-circle" src="https://via.placeholder.com/30x30" alt="profile">
                                </a>
                                <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                                  <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                                    <div class="mb-3">
                                      <img class="wd-80 ht-80 rounded-circle" src="https://via.placeholder.com/80x80" alt="">
                                    </div>
                                    <div class="text-center">
                                      <p class="tx-16 fw-bolder"><?php echo $auth->firstname . ' '?> <?php echo $auth->lastname ?? ''?></p>
                                      <p class="tx-12 text-muted"><?php echo $auth->user_type ?></p>
                                    </div>
                                  </div>
                                  <ul class="list-unstyled p-1">
                                    <li class="dropdown-item py-2">
                                      <?php
                                        $route = whoIs('user_type') == 'customer' ? _route('user:editCustomer', $auth->id) : _route('user:edit', $auth->id);
                                      ?>
                                      <a href="<?php echo $route?>" class="text-body ms-0">
                                        <i class="me-2 icon-md" data-feather="user"></i>
                                        <span>Profile</span>
                                      </a>
                                    </li>
                                    <li class="dropdown-item py-2">
                                      <a href="<?php echo _route('auth:logout')?>" class="text-body ms-0">
                                        <i class="me-2 icon-md" data-feather="log-out"></i>
                                        <span>Log Out</span>
                                      </a>
                                    </li>
                                  </ul>
                                </div>
                              </li>
                            </ul>
                            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
                                <i data-feather="menu"></i>                 
                            </button>
                        <?php endif?>
                    </div>
                </div>
            </nav>
            <?php if($auth) :?>
                <nav class="bottom-navbar">
                    <div class="container">
                        <ul class="nav page-navigation">
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo _route('dashboard:index')?>">
                                    <i class="link-icon" data-feather="box"></i>
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo _route('transaction:index')?>">
                                    <i class="link-icon" data-feather="box"></i>
                                    <span class="menu-title">Transaction</span>
                                </a>
                            </li>
                            <?php if($isVendorManagement) :?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo _route('platform:index')?>">
                                    <i class="link-icon" data-feather="box"></i>
                                    <span class="menu-title">Stations</span>
                                </a>
                            </li>
                            <?php endif?>

                            <?php if($isPlatformManagement || $isVendorManagement) :?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo _route('user:index')?>">
                                    <i class="link-icon" data-feather="box"></i>
                                    <span class="menu-title">Users</span>
                                </a>
                            </li>
                            <?php endif?>

                            <?php if($isPlatformManagement || $isVendorManagement || $isPlatformStaff) :?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo _route('user:customers')?>">
                                    <i class="link-icon" data-feather="box"></i>
                                    <span class="menu-title">Customers</span>
                                </a>
                            </li>
                            <?php endif?>

                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo _route('container:index')?>">
                                    <i class="link-icon" data-feather="box"></i>
                                    <span class="menu-title">Containers</span>
                                </a>
                            </li>

                            <?php if($isPlatformManagement || $isVendorManagement) :?>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="link-icon" data-feather="mail"></i>
                                    <span class="menu-title">Others</span>
                                    <i class="link-arrow"></i>
                                </a>
                                <div class="submenu">
                                    <ul class="submenu-item">
                                        <li class="nav-item"><a class="nav-link" 
                                        href="<?php echo _route('adrs-src:createOrEdit')?>">Address Source</a></li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a href="/ReportController/create" class="nav-link">
                                    <i class="link-icon" data-feather="hash"></i>
                                    <span class="menu-title">Reports</span></a>
                            </li>
                            <?php endif?>
                        </ul>
                    </div>
                </nav>
            <?php endif?>
        </div>
        <!-- partial -->
    
        <div class="page-wrapper">
            <div class="page-content">
                <?php echo produce('content')?>
            </div>
            <footer class="footer border-top">
        <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between py-3 small">
          <p class="text-muted mb-1 mb-md-0">Copyright © 2021 <?php echo COMPANY_NAME?>.</p>
        </div>
            </footer>
        </div>
    </div>

    <!-- core:js -->
    <script src="<?php echo _path_tmp('assets/vendors/core/core.js')?>"></script>
    <script src="<?php echo _path_public('js/core.js')?>"></script>
    <script src="<?php echo _path_public('js/global.js')?>"></script>
    <!-- endinject -->

    <!-- Plugin js for this page -->
    <script src="<?php echo _path_tmp('assets/vendors/chartjs/Chart.min.js')?>"></script>
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    <script src="<?php echo _path_tmp('assets/vendors/feather-icons/feather.min.js')?>"></script>
    <script src="<?php echo _path_tmp('assets/js/template.js')?>"></script>
    <!-- endinject -->

    <!-- Plugin js for this page -->
    <script src="<?php echo _path_tmp('assets/vendors/datatables.net/jquery.dataTables.js')?>"></script>
    <script src="<?php echo _path_tmp('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js')?>"></script>

    <script type="text/javascript" defer>
        $(function() {
          'use strict';

          $(function() {
            $('.dataTable').DataTable({
              "aLengthMenu": [
                [10, 30, 50, -1],
                [10, 30, 50, "All"]
              ],
              "iDisplayLength": 10,
              "language": {
                search: ""
              }
            });
            $('.dataTable').each(function() {
              var datatable = $(this);
              // SEARCH - Add the placeholder for Search and Turn this into in-line form control
              var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
              search_input.attr('placeholder', 'Search');
              search_input.removeClass('form-control-sm');
              // LENGTH - Inline-Form control
              var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
              length_sel.removeClass('form-control-sm');
            });
          });

        });
    </script>
    <?php produce('scripts')?>
    <!-- Custom js for this page -->
  <!-- End custom js for this page -->
</body>
</html>