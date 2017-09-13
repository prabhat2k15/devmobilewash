 <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <!-- BEGIN SIDEBAR -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse collapse">
                    <!-- BEGIN SIDEBAR MENU -->
                    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <ul class="page-sidebar-menu  <?php if(basename($_SERVER['PHP_SELF']) == 'schedule-orders.php') echo "page-sidebar-menu-closed" ?> page-header-fixed page-sidebar-menu-hover-submenu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                        <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                        <li class="sidebar-toggler-wrapper hide">
                            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                            <div class="sidebar-toggler"> </div>
                            <!-- END SIDEBAR TOGGLER BUTTON -->
                        </li>

                        <!--<li class="nav-item start <?php if($url == 'index.php') { $open_dashboard = 'open'; echo 'active open'; } ?> ">
                            <a href="index.php" class="nav-link nav-toggle">
                                <i class="icon-home"></i>
                                <span class="title">Dashboard</span>
                                <span class="selected"></span>
                                <span class="arrow <?php echo $open_dashboard; ?>"></span>
                            </a>
                        </li>-->
                        <li class="sidebar-search-wrapper">
                            <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                            <!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
                            <!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
                            <form class="sidebar-search  " action="search.php" method="GET">
                                <a href="javascript:;" class="remove">
                                    <i class="icon-close"></i>
                                </a>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="q" placeholder="Search...">
                                    <span class="input-group-btn">
                                        <a href="javascript:;" class="btn submit">
                                            <i class="icon-magnifier"></i>
                                        </a>
                                    </span>
                                </div>
                            </form>
                            <!-- END RESPONSIVE QUICK SEARCH FORM -->
                        </li>
                        <li class="nav-item  <?php if($url == 'all-orders.php' || $newurl_page[0] == 'all-orders.php' || $url == 'manage-orders.php' || $url == 'phone-orders.php' || $url == 'schedule-orders.php' || $newurl_page[0] == 'schedule-orders.php' || $url == 'reply-message.php' || $url == 'command-center.php' || $url == 'index.php' || $url == 'db-backup.php' || $url == 'manage-user.php' || $url == 'company_dashboard.php' || $newurl_page[0] == 'edit-order.php' || $newurl_page[0] == 'add-user.php' || $url == 'vehicles-packages.php' || $url == 'manage-promotions.php' || $newurl[1] == 'add-coupon.php' || $url == 'opening-hours.php' || $url == 'site-settings.php' || $url == 'messagess.php' || $newurl_page[0] == 'edit-message.php' || $newurl[1] == 'add-message.php' || $url == 'notifications.php' || $url == 'cms.php' || $newurl_page[0] == 'edit-cms.php' || $newurl_page[0] == 'manage-orders.php' || $newurl_page[0] == 'cms.php' || $newurl_page[0] == 'manage-user.php' || $newurl_page[0] == 'manage-promotions.php' || $newurl_page[0] == 'messagess.php' || $url == 'reminder-client.php' || $url == 'reminder-washer.php' || $newurl_page[0] == 'reminder-washer.php' || $newurl_page[0] == 'reminder-client.php'  || $newurl_page[0] == 'order_calendar.php') { $open_company = 'open'; echo 'active open'; } ?>" style="display: <?php echo $company_module_show; ?>">
                            <a href="index.php" class="nav-link nav-toggle">
                                <i class="icon-layers"></i>
                                <span class="title">Company</span>
                                <span class="arrow <?php echo $open_company; ?>"></span>
                            </a>
                            <ul class="sub-menu">
	<li class="nav-item  <?php if($url == 'all-orders.php' || $newurl_page[0] == 'all-orders.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $checked_manage_display; ?>">
                                    <a href="all-orders.php?filter=&limit=400" class="nav-link ">
                                        <span class="title">App Orders</span>
                                    </a>
                                  
                                </li>
<?php /*
								<li class="nav-item  <?php if($url == 'phone-orders.php' || $newurl_page[0] == 'phone-orders.php' || $newurl_page[0] == 'add-phone-order.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $checked_manage_display; ?>">
                                    <a href="phone-orders.php" class="nav-link ">
                                        <span class="title">Call-In Orders</span>
                                    </a>
                                </li> */ ?>

                                <li class="nav-item <?php if($url == 'vehicles-packages.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $checked_vehicles_display; ?>">
                                    <a href="vehicles-packages.php" class="nav-link ">
                                        <span class="title">Vehicles Packages</span>
                                    </a>
                                </li>
								<li class="nav-item  <?php if($url == 'order_calendar.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $checked_order_calendar_display; ?>">
                                    <a href="order_calendar.php" class="nav-link ">
                                        <span class="title">Order Calendar</span>
                                    </a>
                                </li>


                            </ul>
                        </li>

                        <li class="nav-item  <?php if($url == 'manage-pre-clients.php' || $newurl_page[0] == 'manage-customers.php' || $url == 'trash-pre-clients.php' || $url == 'client_dashboard.php' || $newurl_page[0] == 'pre-clients-details.php' || $url == 'manage-customers.php' || $newurl_page[0] == 'edit-customer.php' || $url == 'feedbacks.php' || $newurl_page[0] == 'manage-pre-clients.php') { $open_client = 'open'; echo 'active open'; } ?>" style="display: <?php echo $client_module_show; ?>;">
                            <a class="nav-link nav-toggle">
                                <i class="icon-layers"></i>
                                <span class="title">Client</span>
                                <span class="arrow <?php echo $open_client; ?>"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item  <?php if($url == 'manage-pre-clients.php' || $url == 'trash-pre-clients.php'  || $newurl_page[0] == 'manage-pre-clients.php' || $newurl_page[0] == 'pre-clients-details.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="manage-pre-clients.php" class="nav-link ">
                                        <span class="title">Pre-Registered Clients</span>
                                    </a>
                                </li>
 <li class="nav-item  <?php if($url == 'manage-customers.php' || $newurl_page[0] == 'manage-customers.php' || $newurl_page[0] == 'edit-customer.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="manage-customers.php" class="nav-link ">
                                        <span class="title">Manage Customers</span>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li class="nav-item  <?php if($url == 'manage-pre-washers.php' || $newurl_page[0] == 'manage-pre-washers.php' || $newurl_page[0] == 'manage-agents.php' || $url == 'trash-pre-washers.php' || $url == 'washer_dashboard.php' || $newurl_page[0] == 'pre-washer-details.php' || $url == 'manage-agents.php' || $newurl_page[0] == 'edit-agent.php' || $newurl[1] == 'add-agent.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $washer_module_show; ?>;">
                            <a>
                                <i class="icon-layers"></i>
                                <span class="title">Washer</span>
                                <span class="arrow nav-link nav-toggle <?php echo $open_agent; ?>"></span></a>

                            <ul class="sub-menu">
                                <li class="nav-item  <?php if($url == 'manage-pre-washers.php' || $newurl_page[0] == 'manage-pre-washers.php' || $url == 'trash-pre-washers.php' || $newurl_page[0] == 'pre-washer-details.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="manage-pre-washers.php" class="nav-link ">
                                        <span class="title">Pre-Registered Washers</span>
                                    </a>
                                </li>
                                <!--li class="nav-item  <?php //if($url == 'active-washers.php' || $newurl_page[0] == 'active-washers.php' || $newurl_page[0] == 'act-washer-details.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="active-washers.php" class="nav-link ">
                                        <span class="title">Active Washers</span>
                                    </a>
                                </li-->

                            </ul>
                        </li>







                    </ul>
                    <!-- END SIDEBAR MENU -->
                    <!-- END SIDEBAR MENU -->
                    <a class="add-bug-btn" href="add-new-bug.php" style="display: block;margin: 0 auto;color: #fff;text-align: center;padding: 10px;margin: 20px 45px;box-sizing: border-box;border-radius: 50px;text-decoration: none;border: 2px solid #b4bcc8;">Report Bug</a>

                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->