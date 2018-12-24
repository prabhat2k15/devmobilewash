<style>
    .page-sidebar-closed .sidebar-search{
        display: none;
    }

</style>
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

            <ul class="page-sidebar-menu  <?php if (basename($_SERVER['PHP_SELF']) == 'schedule-orders.php') echo "page-sidebar-menu-closed" ?> page-header-fixed page-sidebar-menu-hover-submenu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                <li class="sidebar-toggler-wrapper hide">
                    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                    <div class="sidebar-toggler"> </div>
                    <!-- END SIDEBAR TOGGLER BUTTON -->
                </li>
                <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
                <li class="sidebar-search-wrapper">
                    <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                    <!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
                    <!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
                    <form style="visibility: hidden; margin-top: 15px;" class="sidebar-search  " action="" method="GET">
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
                <!--<li class="nav-item start <?php
                if ($url == 'index.php') {
                    $open_dashboard = 'open';
                    echo 'active open';
                }
                ?> ">
                    <a href="index.php" class="nav-link nav-toggle">
                        <i class="icon-home"></i>
                        <span class="title">Dashboard</span>
                        <span class="selected"></span>
                        <span class="arrow <?php echo $open_dashboard; ?>"></span>
                    </a>
                </li>-->
                <li class="nav-item  <?php
                if ($url == 'downloads.php' || $newurl_page[0] == 'downloads.php' || $url == 'customer-expansion-rquest.php' || $newurl_page[0] == 'customer-expansion-rquest.php' || $url == 'flagged-issues.php' || $newurl_page[0] == 'flagged-issues.php' || $url == 'all-orders.php' || $newurl_page[0] == 'all-orders.php' || $url == 'payment-reports.php' || $url == 'manage-orders.php' || $url == 'phone-orders.php' || $url == 'zipcode-pricing.php' || $url == 'coverage-area-cities.php' || $url == 'merge-orders.php' || $url == 'schedule-orders.php' || $url == 'bug-report.php' || $url == 'surge-pricing.php' || $newurl_page[0] == 'schedule-orders.php' || $url == 'reply-message.php' || $url == 'command-center.php' || $url == 'heatmap.php' || $url == 'index.php' || $url == 'db-backup.php' || $url == 'manage-user.php' || $url == 'company_dashboard.php' || $newurl_page[0] == 'edit-order.php' || $newurl_page[0] == 'add-user.php' || $url == 'vehicles-packages.php' || $url == 'vehicle-pricing.php' || $url == 'vehicle-addons-pricing.php' || $url == 'manage-promotions.php' || $url == 'promo-popups.php' || $newurl[1] == 'add-coupon.php' || $url == 'opening-hours.php' || $url == 'site-settings.php' || $url == 'messagess.php' || $url == 'push-messages.php' || $newurl_page[0] == 'edit-message.php' || $newurl[1] == 'add-message.php' || $url == 'notifications.php' || $url == 'cms.php' || $newurl_page[0] == 'edit-cms.php' || $newurl_page[0] == 'manage-orders.php' || $newurl_page[0] == 'cms.php' || $newurl_page[0] == 'manage-user.php' || $newurl_page[0] == 'manage-promotions.php' || $newurl_page[0] == 'messagess.php' || $newurl_page[0] == 'app-settings.php' || $url == 'reminder-client.php' || $url == 'reminder-washer.php' || $newurl_page[0] == 'reminder-washer.php' || $url == 'newsletter-subscribers.php' || $newurl_page[0] == 'newsletter-subscribers.php' || $url == 'add-newsletter.php' || $url == 'discount-settings.php' || $newurl_page[0] == 'add-newsletter.php' || $url == 'newsletters.php' || $newurl_page[0] == 'coverage-area-zipcodes.php' || $url == 'coverage-area-zipcodes.php' || $newurl_page[0] == 'newsletters.php' || $newurl_page[0] == 'reminder-client.php' || $url == 'show-calendar.php' || $url == 'list-review.php' || $url == 'add-edit-review.php') {
                    $open_company = 'open';
                    echo 'active open';
                }
                ?>" style="display: <?php echo $company_module_show; ?>">
                    <a href="<?php echo ROOT_URL; ?>/admin-new/" class="nav-link nav-toggle">
                        <i class="icon-layers"></i>
                        <span class="title">Company</span>
                        <span class="arrow <?php echo $open_company; ?>"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item  <?php
                        if ($url == 'all-orders.php' || $newurl_page[0] == 'all-orders.php') {
                            $open_agent = 'open';
                            echo 'active open';
                        }
                        ?>" style="display: <?php echo $checked_manage_display; ?>">
                            <a href="<?php echo ROOT_URL; ?>/admin-new/all-orders.php?filter=&limit=400" class="nav-link ">
                                <span class="title">App Orders</span>
                            </a>                                  
                        </li>                                
                        <?php if ($jsondata_permission->users_type == 'admin'): ?> 
                            <li style="display: <?php echo $checked_show_calendar_display; ?>" class="nav-item  <?php
                            if ($url == 'show-calendar.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/show-calendar.php" class="nav-link ">
                                    <span class="title">Order Calendar</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($jsondata_permission->users_type == 'admin'): ?> 
                            <li style="display: <?php echo $checked_show_calendar_display; ?>" class="nav-item  <?php
                            if ($url == 'flagged-issues.php' || $newurl_page[0] == 'flagged-issues.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/flagged-issues.php?filter=flaggedIssues&&flaggedIssueStatus=1&limit=400" class="nav-link ">
                                    <span class="title">Flagged Orders</span>
                                </a>
                            </li>
                            
                        <?php endif; ?>
                        
                        <?php if (($jsondata_permission->users_type == 'recruiter') || ($jsondata_permission->users_type == 'scheduler')): ?> 
                            <li style="display: <?php echo $checked_show_calendar_display; ?>" class="nav-item  <?php
                            if ($url == 'order_calendar.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/order_calendar.php" class="nav-link ">
                                    <span class="title">Order Calendar</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (($jsondata_permission->users_type == 'admin') || ($jsondata_permission->users_type == 'scheduler')): ?>
                            <li style="display: <?php echo $checked_command_center_display; ?>" class="nav-item  <?php
                            if ($url == 'command-center.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/command-center.php" target="_blank" class="nav-link ">
                                    <span class="title">Command Center</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (($jsondata_permission->users_type == 'admin') || ($jsondata_permission->users_type == 'scheduler')): ?>
                            <li style="display: <?php echo $checked_notifications_display; ?>" class="nav-item  <?php
                            if ($url == 'notifications.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/notifications.php" class="nav-link ">
                                    <span class="title">Notifications</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (($jsondata_permission->users_type == 'admin') || ($jsondata_permission->users_type == 'scheduler')): ?>
                            <li class="nav-item <?php
                            if ($url == 'manage-promotions.php' || $newurl_page[0] == 'manage-promotions.php' || $newurl[1] == 'add-coupon.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>" style="display: <?php echo $checked_promotions_display; ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/manage-promotions.php" class="nav-link ">
                                    <span class="title">Manage Promotions</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item <?php
                        if ($url == 'vehicles-packages.php') {
                            $open_agent = 'open';
                            echo 'active open';
                        }
                        ?>" style="display: <?php echo $checked_vehicles_display; ?>">
                            <a href="<?php echo ROOT_URL; ?>/admin-new/vehicles-packages.php" class="nav-link ">
                                <span class="title">Vehicles Packages</span>
                            </a>
                        </li>
                        <?php if ($jsondata_permission->users_type == 'admin'): ?>
                            <li class="nav-item <?php
                            if ($url == 'vehicle-pricing.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>" style="display: <?php echo $checked_vehicles_display; ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/vehicle-pricing.php" class="nav-link ">
                                    <span class="title">Vehicle Pricing</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (($jsondata_permission->users_type == 'admin') || ($jsondata_permission->users_type == 'scheduler')): ?>
                            <li style="display: <?php echo $checked_opening_display; ?>" class="nav-item  <?php
                            if ($url == 'schedule-times.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/schedule-times.php" class="nav-link ">
                                    <span class="title">Schedule Times</span>
                                </a>
                            </li>
                            <li style="display: <?php echo $checked_opening_display; ?>" class="nav-item  <?php
                            if ($url == 'ondemand-surge-times.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/ondemand-surge-times.php" class="nav-link ">
                                    <span class="title">On-Demand Surge Times</span>
                                </a>
                            </li>
                            <li style="display: <?php echo $checked_show_calendar_display; ?>" class="nav-item  <?php
                            if ($url == 'customer-expansion-rquest.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/customer-expansion-rquest.php" class="nav-link ">
                                    <span class="title">Expansion Requests</span>
                                </a>
                            </li>
                            <li style="display: <?php echo $checked_show_calendar_display; ?>" class="nav-item  <?php
                            if ($url == 'downloads.php' || $newurl_page[0] == 'downloads.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/downloads.php" class="nav-link ">
                                    <span class="title">Downloads</span>
                                </a>
                            </li>

                            <li class="nav-item  <?php
                            if ($url == 'payment-reports.php' || $newurl_page[0] == 'payment-reports.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>" style="display: <?php echo $checked_manage_display; ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/payment-reports.php?filter=&limit=400" class="nav-link ">
                                    <span class="title">Payment Reports</span>
                                </a>
                            </li>
                            <li class="nav-item <?php
                            if ($url == 'vehicle-addons-pricing.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>" style="display: <?php echo $checked_vehicles_display; ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/vehicle-addons-pricing.php" class="nav-link ">
                                    <span class="title">Vehicle Addons Pricing</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($jsondata_permission->users_type == 'admin'): ?>
                            <li class="nav-item <?php
                            if ($url == 'surge-pricing.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>" style="display: <?php echo $checked_vehicles_display; ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/surge-pricing.php" class="nav-link ">
                                    <span class="title">Surge Pricing</span>
                                </a>
                            </li>
                            <li class="nav-item <?php
                            if ($url == 'zipcode-pricing.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>" style="display: <?php echo $checked_vehicles_display; ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/zipcode-pricing.php" class="nav-link ">
                                    <span class="title">Zipcode Pricing</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (($jsondata_permission->users_type == 'admin') || ($jsondata_permission->users_type == 'scheduler')): ?>
                            <li class="nav-item <?php
                            if ($url == 'add-vehicle.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>" style="display: <?php echo $checked_vehicles_display; ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/add-vehicle.php" class="nav-link ">
                                    <span class="title">Add New Vehicle</span>
                                </a>
                            </li>
                            <li class="nav-item <?php
                            if ($url == 'modern-vehicles.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>" style="display: <?php echo $checked_vehicles_display; ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/modern-vehicles.php" class="nav-link ">
                                    <span class="title">Modern Vehicles</span>
                                </a>
                            </li>
                            <li class="nav-item <?php
                            if ($url == 'classic-vehicles.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>" style="display: <?php echo $checked_vehicles_display; ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/classic-vehicles.php" class="nav-link ">
                                    <span class="title">Classic Vehicles</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($jsondata_permission->users_type == 'admin'): ?>       
                            <li class="nav-item <?php
                            if ($url == 'promo-popups.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>" style="display: <?php echo $open_agent; ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/promo-popups.php" class="nav-link ">
                                    <span class="title">Promo Popups</span>
                                </a>
                            </li>
                            <li class="nav-item <?php
                            if ($url == 'discount-settings.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>" style="display: <?php echo $open_agent; ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/discount-settings.php" class="nav-link ">
                                    <span class="title">Discount Settings</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (($jsondata_permission->users_type == 'admin') || ($jsondata_permission->users_type == 'scheduler')): ?>
                            <li style="display: <?php echo $checked_opening_display; ?>" class="nav-item  <?php
                            if ($url == 'hours-of-operation.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/hours-of-operation.php" class="nav-link ">
                                    <span class="title">Hours of Operation</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($jsondata_permission->users_type == 'admin'): ?>
                            <li class="nav-item  <?php
                            if ($url == 'site-settings.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>" style="display: <?php echo $checked_site_display; ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/site-settings.php" class="nav-link ">
                                    <span class="title">Site Settings</span>
                                </a>
                            </li>
                            <li class="nav-item  <?php
                            if ($url == 'app-settings.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>" style="display: <?php echo $checked_site_display; ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/app-settings.php" class="nav-link ">
                                    <span class="title">App Settings</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (($jsondata_permission->users_type == 'admin') || ($jsondata_permission->users_type == 'scheduler')): ?>
                            <li style="display: <?php echo $checked_messages_display; ?>" class="nav-item  <?php
                            if ($url == 'messagess.php' || $newurl_page[0] == 'messagess.php' || $newurl_page[0] == 'edit-message.php' || $newurl[1] == 'add-message.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/messagess.php" class="nav-link ">
                                    <span class="title">Messages</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($jsondata_permission->users_type == 'admin'): ?>
                            <li style="display: <?php echo $checked_messages_display; ?>" class="nav-item  <?php
                            if ($url == 'push-messages.php' || $newurl_page[0] == 'push-messages.php' || $newurl_page[0] == 'edit-push-message.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/push-messages.php" class="nav-link ">
                                    <span class="title">Push Messages</span>
                                </a>
                            </li>

                            <li style="display: <?php echo $checked_cms_display; ?>" class="nav-item  <?php
                            if ($url == 'cms.php' || $newurl_page[0] == 'cms.php' || $newurl_page[0] == 'edit-cms.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/cms.php" class="nav-link ">
                                    <span class="title">CMS</span>
                                </a>
                            </li>
                            <li style="display: <?php echo $checked_user_display; ?>" class="nav-item  <?php
                            if ($url == 'users.php' || $newurl_page[0] == 'users.php' || $newurl_page[0] == 'add-user.php.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/users.php" class="nav-link ">
                                    <span class="title">Users</span>
                                </a>
                            </li>
                            <li style="display: <?php echo $checked_reminderwasher_display; ?>" class="nav-item  <?php
                            if ($url == 'reminder-washer.php' || $newurl_page[0] == 'reminder-washer.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/reminder-washer.php" class="nav-link ">
                                    <span class="title">Reminder Washer</span>
                                </a>
                            </li>
                            <li style="display: <?php echo $checked_reminderclient_display; ?>" class="nav-item  <?php
                            if ($url == 'reminder-client.php' || $newurl_page[0] == 'reminder-client.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/reminder-client.php" class="nav-link ">
                                    <span class="title">Reminder Client</span>
                                </a>
                            </li>
                            <li class="nav-item  <?php
                            if ($url == 'reply-message.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/reply-message.php" class="nav-link ">
                                    <span class="title">Manage Reply Message</span>
                                </a>
                            </li>
                            <li style="display: <?php echo $checked_backup_db_display; ?>" class="nav-item  <?php
                            if ($url == 'db-backup.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/db-backup.php" class="nav-link ">
                                    <span class="title">Backup DB</span>
                                </a>
                            </li>
                            <li style="display: <?php echo $checked_backup_db_display; ?>" class="nav-item  <?php
                            if ($url == 'newsletters.php' || $url == 'newsletters.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/newsletters.php" class="nav-link ">
                                    <span class="title">Newsletters</span>
                                </a>
                                <ul class="sub-menu">
                                    <li style="display: <?php echo $checked_backup_db_display; ?>" class="nav-item  <?php
                                    if ($url == 'newsletter-subscribers.php') {
                                        $open_agent = 'open';
                                        echo 'active open';
                                    }
                                    ?>">
                                        <a href="<?php echo ROOT_URL; ?>/admin-new/newsletter-subscribers.php" class="nav-link ">
                                            <span class="title">Newsletter Subscribers</span>
                                        </a>
                                    </li>
                                    <li style="display: <?php echo $checked_backup_db_display; ?>" class="nav-item  <?php
                                    if ($url == 'add-newsletter.php') {
                                        $open_agent = 'open';
                                        echo 'active open';
                                    }
                                    ?>">
                                        <a href="<?php echo ROOT_URL; ?>/admin-new/add-newsletter.php" class="nav-link ">
                                            <span class="title">Add Newsletter</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li style="display: <?php echo $checked_backup_db_display; ?>" class="nav-item  <?php
                            if ($url == 'coverage-area-zipcodes.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/coverage-area-zipcodes.php" class="nav-link ">
                                    <span class="title">Coverage Area Zipcodes</span>
                                </a>
                            </li>
                            <li style="display: <?php echo $checked_backup_db_display; ?>" class="nav-item  <?php
                            if ($url == 'coverage-area-cities.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/coverage-area-cities.php" class="nav-link ">
                                    <span class="title">Coverage Area Cities</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (($jsondata_permission->users_type == 'admin') || ($jsondata_permission->users_type == 'scheduler')): ?>
                            <li style="display: <?php echo $checked_command_center_display; ?>" class="nav-item  <?php
                            if ($url == 'heatmap.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/heatmap.php" target="_blank" class="nav-link ">
                                    <span class="title">Heatmap</span>
                                </a>
                            </li>

                        <?php endif; ?>
                        <?php if ($jsondata_permission->users_type == 'admin'): ?>
                            <li class="nav-item <?php
                            if ($url == 'list-review.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>" style="display: <?php echo $checked_show_review_display; ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/list-review.php" class="nav-link ">
                                    <span class="title">Review</span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item <?php
                                    if ($url == 'add-review.php') {
                                        $open_agent = 'open';
                                        echo 'active open';
                                    }
                                    ?>" style="display: <?php echo $checked_show_review_display; ?>">
                                        <a href="<?php echo ROOT_URL; ?>/admin-new/add-edit-review.php?action=add" class="nav-link ">
                                            <span class="title">Add/Edit Review</span>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            <li style="display: <?php echo $checked_show_calendar_display; ?>" class="nav-item  <?php
                            if ($url == 'bug-report.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/bug-report.php" class="nav-link ">
                                    <span class="title">Bug Report</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>

                <li class="nav-item  <?php
                if ($url == 'manage-pre-clients.php' || $url == 'non-returning-customer-notifications.php' || $url == 'inactive-customer-notifications.php' || $newurl_page[0] == 'manage-customers.php' || $url == 'non-return-customers.php' || $newurl_page[0] == 'non-return-customers.php' || $url == 'trash-pre-clients.php' || $url == 'client_dashboard.php' || $newurl_page[0] == 'pre-clients-details.php' || $url == 'manage-customers.php' || $newurl_page[0] == 'edit-customer.php' || $url == 'feedbacks.php' || $url == 'customer-notifications.php' || $url == 'top-customers.php' || $newurl_page[0] == 'manage-pre-clients.php') {
                    $open_client = 'open';
                    echo 'active open';
                }
                ?>" style="display: <?php echo $client_module_show; ?>;">
                    <a href="<?php echo ROOT_URL; ?>/admin-new/client_dashboard.php" class="nav-link nav-toggle">
                        <i class="icon-layers"></i>
                        <span class="title">Customer</span>
                        <span class="arrow <?php echo $open_client; ?>"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item  <?php
                        if ($url == 'manage-pre-clients.php' || $url == 'trash-pre-clients.php' || $newurl_page[0] == 'manage-pre-clients.php' || $newurl_page[0] == 'pre-clients-details.php') {
                            $open_agent = 'open';
                            echo 'active open';
                        }
                        ?>">
                            <a href="<?php echo ROOT_URL; ?>/admin-new/manage-pre-clients.php" class="nav-link ">
                                <span class="title">Pre-Registered Customers</span>
                            </a>
                        </li>
                        <li class="nav-item  <?php
                        if ($url == 'manage-customers.php' || $newurl_page[0] == 'manage-customers.php' || $newurl_page[0] == 'edit-customer.php') {
                            $open_agent = 'open';
                            echo 'active open';
                        }
                        ?>">
                            <a href="<?php echo ROOT_URL; ?>/admin-new/manage-customers.php?limit=400" class="nav-link ">
                                <span class="title">Manage Customers</span>
                            </a>
                        </li>
                        <?php if (($jsondata_permission->users_type == 'admin') || ($jsondata_permission->users_type == 'scheduler')): ?>
                            <li class="nav-item  <?php
                            if ($url == 'non-return-customers.php' || $newurl_page[0] == 'non-return-customers.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/non-return-customers.php" class="nav-link ">
                                    <span class="title">Non Returning Customers</span>
                                </a>
                            </li>
                            <li class="nav-item  <?php
                            if ($url == 'non-returning-customer-notifications.php' || $newurl_page[0] == 'non-returning-customer-notifications.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/non-returning-customer-notifications.php" class="nav-link ">
                                    <span class="title">Non Returning Notifications</span>
                                </a>
                            </li>
                            <li class="nav-item  <?php
                            if ($url == 'inactive-customers.php' || $newurl_page[0] == 'inactive-customers.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/inactive-customers.php" class="nav-link ">
                                    <span class="title">Inactive Customers</span>
                                </a>
                            </li>
                            <li class="nav-item  <?php
                            if ($url == 'inactive-customer-notifications.php' || $newurl_page[0] == 'inactive-customer-notifications.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/inactive-customer-notifications.php" class="nav-link ">
                                    <span class="title">Inactive Notifications</span>
                                </a>
                            </li>
                            <li class="nav-item  <?php
                            if ($url == 'feedbacks.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/feedbacks.php" class="nav-link ">
                                    <span class="title">Feedbacks</span>
                                </a>
                            </li>
                            <li class="nav-item  <?php
                            if ($url == 'mobilewasher-service-feedbacks.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/mobilewasher-service-feedbacks.php" class="nav-link ">
                                    <span class="title">MobileWasher Service Feedbacks</span>
                                </a>
                            </li>
                            <!--<li class="nav-item  <?php //if($url == 'customer-notifications.php') { $open_agent = 'open'; echo 'active open'; }                    ?>">
                                <a href="customer-notifications.php" class="nav-link ">
                                    <span class="title">Customer Push Notifications</span>
                                </a>
                            </li>-->
                            <li class="nav-item  <?php
                            if ($url == 'top-customers.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/top-customers.php" class="nav-link ">
                                    <span class="title">Top Customers</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>

                <li class="nav-item  <?php
                if ($url == 'unlimited-schedule-range-washer.php' || $newurl_page[0] == 'unlimited-schedule-range-washer.php' || $url == 'manage-pre-washers.php' || $url == 'washer-feed.php' || $newurl_page[0] == 'manage-pre-washers.php' || $url == 'add-new-washer.php' || $url == 'washer-notifications.php' || $url == 'top-washers.php' || $newurl_page[0] == 'add-new-washer.php' || $newurl_page[0] == 'manage-agents.php' || $url == 'trash-pre-washers.php' || $url == 'washer_dashboard.php' || $newurl_page[0] == 'pre-washer-details.php' || $url == 'manage-agents.php' || $newurl_page[0] == 'edit-agent.php' || $url == 'act-washer-details.php' || $newurl_page[0] == 'act-washer-details.php' || $url == 'active-washers.php' || $newurl_page[0] == 'active-washers.php' || $newurl[1] == 'add-agent.php') {
                    $open_agent = 'open';
                    echo 'active open';
                }
                ?>" style="display: <?php echo $washer_module_show; ?>;">
                    <a href="<?php echo ROOT_URL; ?>/admin-new/washer_dashboard.php">
                        <i class="icon-layers"></i>
                        <span class="title">Washer</span>
                        <span class="arrow nav-link nav-toggle <?php echo $open_agent; ?>"></span></a>

                    <ul class="sub-menu">
                        <li class="nav-item  <?php
                        if ($url == 'manage-pre-washers.php' || $newurl_page[0] == 'manage-pre-washers.php' || $url == 'trash-pre-washers.php' || $newurl_page[0] == 'pre-washer-details.php') {
                            $open_agent = 'open';
                            echo 'active open';
                        }
                        ?>">
                            <a href="<?php echo ROOT_URL; ?>/admin-new/manage-pre-washers.php" class="nav-link ">
                                <span class="title">Pre-Registered Washers</span>
                            </a>
                        </li>

                        <?php if (($jsondata_permission->users_type == 'admin') || ($jsondata_permission->users_type == 'scheduler')): ?>
                            <li class="nav-item  <?php
                            if ($url == 'manage-agents.php' || $newurl_page[0] == 'manage-agents.php' || $newurl_page[0] == 'edit-agent.php' || $newurl[1] == 'add-agent.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/manage-agents.php?type=demo" class="nav-link ">
                                    <span class="title">Manage Washers</span>
                                </a>
                            </li>
                            <li class="nav-item  <?php
                            if ($url == 'unlimited-schedule-range-washer.php' || $newurl_page[0] == 'unlimited-schedule-range-washer.php') {
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/unlimited-schedule-range-washer.php?type=demo" class="nav-link ">
                                    <span class="title">Unlimited Scheduled Range</span>
                                </a>
                            </li>
                            <li class="nav-item  <?php
                            if ($url == 'washer-feed.php') {
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/washer-feed.php" class="nav-link ">
                                    <span class="title">Washer Feed</span>
                                </a>
                            </li>

                            <li class="nav-item  <?php
                            if ($url == 'top-washers.php') {
                                $open_agent = 'open';
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php echo ROOT_URL; ?>/admin-new/top-washers.php" class="nav-link ">
                                    <span class="title">Top Washers</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>







            </ul>
            <!-- END SIDEBAR MENU -->
            <!-- END SIDEBAR MENU -->
            <a class="add-bug-btn" href="add-new-bug.php" style="display: block;margin: 0 auto;color: #fff;text-align: center;padding: 10px;margin: 20px 45px;box-sizing: border-box;border-radius: 50px;text-decoration: none;border: 2px solid #b4bcc8;">Report Bug</a>
            <?php if (($jsondata_permission->users_type == 'admin') || ($jsondata_permission->users_type == 'scheduler')): ?>
                <form class="sidebar-search  " action="search.php" method="GET">


                    <input style="background: #fff; padding: 6px; width: 100%; display: block; margin-bottom: 10px;" type="text" class="form-control" name="q" placeholder="Search..." required>


                    <select name="search_area" style="background: #fff; padding: 6px; width: 100%; display: block; margin-bottom: 10px;" required>
                        <option value="">-- Select Search Area --</option>
                        <option value="Order Number">Order Number</option>
                        <option value="Washer Badge">Washer Badge</option>
                        <option value="Washer Name">Washer Name</option>
                        <option value="Washer Phone">Washer Phone</option>
                        <option value="Customer Name">Customer Name</option>
                        <option value="Customer Email">Customer Email</option>
                        <option value="Customer Phone">Customer Phone</option>
                        <option value="Created Date">Created Date</option>
                        <option value="Scheduled Date">Scheduled Date</option>
                        <option value="On-Demand">On-Demand</option>
                        <option value="Scheduled">Scheduled</option>

                    </select>

                    <input style="width: 100%;" type="submit" value='Search' />
                </form>
            <?php endif; ?>
        </div>
        <!-- END SIDEBAR -->
    </div>
    <!-- END SIDEBAR -->