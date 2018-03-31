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
                    
                    <ul class="page-sidebar-menu  <?php if(basename($_SERVER['PHP_SELF']) == 'schedule-orders.php') echo "page-sidebar-menu-closed" ?> page-header-fixed page-sidebar-menu-hover-submenu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
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
                        <!--<li class="nav-item start <?php if($url == 'index.php') { $open_dashboard = 'open'; echo 'active open'; } ?> ">
                            <a href="index.php" class="nav-link nav-toggle">
                                <i class="icon-home"></i>
                                <span class="title">Dashboard</span>
                                <span class="selected"></span>
                                <span class="arrow <?php echo $open_dashboard; ?>"></span>
                            </a>
                        </li>-->
                        <li class="nav-item  <?php if($url == 'all-orders.php' || $newurl_page[0] == 'all-orders.php' || $url == 'payment-reports.php' || $url == 'manage-orders.php' || $url == 'phone-orders.php'|| $url == 'zipcode-pricing.php' || $url == 'coverage-area-cities.php' || $url == 'merge-orders.php' || $url == 'schedule-orders.php' || $url == 'bug-report.php' || $url == 'surge-pricing.php' || $newurl_page[0] == 'schedule-orders.php' || $url == 'reply-message.php' || $url == 'command-center.php' || $url == 'index.php' || $url == 'db-backup.php' || $url == 'manage-user.php' || $url == 'company_dashboard.php' || $newurl_page[0] == 'edit-order.php' || $newurl_page[0] == 'add-user.php' || $url == 'vehicles-packages.php' || $url == 'vehicle-pricing.php' || $url == 'vehicle-addons-pricing.php' || $url == 'manage-promotions.php' || $url == 'promo-popups.php' || $newurl[1] == 'add-coupon.php' || $url == 'opening-hours.php' || $url == 'site-settings.php' || $url == 'messagess.php' || $url == 'push-messages.php' || $newurl_page[0] == 'edit-message.php' || $newurl[1] == 'add-message.php' || $url == 'notifications.php' || $url == 'cms.php' || $newurl_page[0] == 'edit-cms.php' || $newurl_page[0] == 'manage-orders.php' || $newurl_page[0] == 'cms.php' || $newurl_page[0] == 'manage-user.php' || $newurl_page[0] == 'manage-promotions.php' || $newurl_page[0] == 'messagess.php' || $newurl_page[0] == 'app-settings.php' || $url == 'reminder-client.php' || $url == 'reminder-washer.php' || $newurl_page[0] == 'reminder-washer.php' || $url == 'newsletter-subscribers.php' || $newurl_page[0] == 'newsletter-subscribers.php' || $url == 'add-newsletter.php' || $url == 'discount-settings.php' || $newurl_page[0] == 'add-newsletter.php' || $url == 'newsletters.php' || $newurl_page[0] == 'coverage-area-zipcodes.php' || $url == 'coverage-area-zipcodes.php' || $newurl_page[0] == 'newsletters.php'|| $newurl_page[0] == 'reminder-client.php' || $url == 'show-calendar.php' || $url == 'list-review.php' || $url == 'add-edit-review.php') { $open_company = 'open'; echo 'active open'; } ?>" style="display: <?php echo $company_module_show; ?>">
                            <a href="/admin-new/" class="nav-link nav-toggle">
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
                                <li class="nav-item  <?php if($url == 'payment-reports.php' || $newurl_page[0] == 'payment-reports.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $checked_manage_display; ?>">
                                    <a href="payment-reports.php?filter=&limit=400" class="nav-link ">
                                    <span class="title">Payment Reports</span>
                                    </a>
                                </li>
                               
						
							
							<?php /*
								<li class="nav-item  <?php if($url == 'merge-orders.php' || $newurl_page[0] == 'merge-orders.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $checked_manage_display; ?>">
                                    <a href="merge-orders.php" class="nav-link ">
                                        <span class="title">All Orders</span>
                                    </a>
                                </li>
 */ ?>
                                <li class="nav-item <?php if($url == 'vehicles-packages.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $checked_vehicles_display; ?>">
                                    <a href="vehicles-packages.php" class="nav-link ">
                                        <span class="title">Vehicles Packages</span>
                                    </a>
                                </li>
 <li class="nav-item <?php if($url == 'vehicle-pricing.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $checked_vehicles_display; ?>">
                                    <a href="vehicle-pricing.php" class="nav-link ">
                                        <span class="title">Vehicle Pricing</span>
                                    </a>
                                </li>
<li class="nav-item <?php if($url == 'vehicle-addons-pricing.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $checked_vehicles_display; ?>">
                                    <a href="vehicle-addons-pricing.php" class="nav-link ">
                                        <span class="title">Vehicle Addons Pricing</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php if($url == 'surge-pricing.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $checked_vehicles_display; ?>">
                                    <a href="surge-pricing.php" class="nav-link ">
                                        <span class="title">Surge Pricing</span>
                                    </a>
                                </li>
				<li class="nav-item <?php if($url == 'zipcode-pricing.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $checked_vehicles_display; ?>">
                                    <a href="zipcode-pricing.php" class="nav-link ">
                                        <span class="title">Zipcode Pricing</span>
                                    </a>
                                </li>
								<li class="nav-item <?php if($url == 'add-vehicle.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $checked_vehicles_display; ?>">
                                    <a href="add-vehicle.php" class="nav-link ">
                                        <span class="title">Add New Vehicle</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php if($url == 'manage-promotions.php' || $newurl_page[0] == 'manage-promotions.php' || $newurl[1] == 'add-coupon.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $checked_promotions_display; ?>">
                                    <a href="manage-promotions.php" class="nav-link ">
                                        <span class="title">Manage Promotions</span>
                                    </a>
                                </li>
  <li class="nav-item <?php if($url == 'promo-popups.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $open_agent; ?>">
                                    <a href="promo-popups.php" class="nav-link ">
                                        <span class="title">Promo Popups</span>
                                    </a>
                                </li>
 <li class="nav-item <?php if($url == 'discount-settings.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $open_agent; ?>">
                                    <a href="discount-settings.php" class="nav-link ">
                                        <span class="title">Discount Settings</span>
                                    </a>
                                </li>
                                <li style="display: <?php echo $checked_opening_display; ?>" class="nav-item  <?php if($url == 'hours-of-operation.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="hours-of-operation.php" class="nav-link ">
                                        <span class="title">Hours of Operation</span>
                                    </a>
                                </li>
<li style="display: <?php echo $checked_opening_display; ?>" class="nav-item  <?php if($url == 'schedule-times.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="schedule-times.php" class="nav-link ">
                                        <span class="title">Schedule Times</span>
                                    </a>
                                </li>
                                <li class="nav-item  <?php if($url == 'site-settings.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $checked_site_display; ?>">
                                    <a href="site-settings.php" class="nav-link ">
                                        <span class="title">Site Settings</span>
                                    </a>
                                </li>
                                 <li class="nav-item  <?php if($url == 'app-settings.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $checked_site_display; ?>">
                                    <a href="app-settings.php" class="nav-link ">
                                        <span class="title">App Settings</span>
                                    </a>
                                </li>
                                <li style="display: <?php echo $checked_messages_display; ?>" class="nav-item  <?php if($url == 'messagess.php' || $newurl_page[0] == 'messagess.php' || $newurl_page[0] == 'edit-message.php' || $newurl[1] == 'add-message.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="messagess.php" class="nav-link ">
                                        <span class="title">Messages</span>
                                    </a>
                                </li>
								<li style="display: <?php echo $checked_messages_display; ?>" class="nav-item  <?php if($url == 'push-messages.php' || $newurl_page[0] == 'push-messages.php' || $newurl_page[0] == 'edit-push-message.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="push-messages.php" class="nav-link ">
                                        <span class="title">Push Messages</span>
                                    </a>
                                </li>
                                <li style="display: <?php echo $checked_notifications_display; ?>" class="nav-item  <?php if($url == 'notifications.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="notifications.php" class="nav-link ">
                                        <span class="title">Notifications</span>
                                    </a>
                                </li>
                                <li style="display: <?php echo $checked_cms_display; ?>" class="nav-item  <?php if($url == 'cms.php' || $newurl_page[0] == 'cms.php' || $newurl_page[0] == 'edit-cms.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="cms.php" class="nav-link ">
                                        <span class="title">CMS</span>
                                    </a>
                                </li>
                                <li style="display: <?php echo $checked_user_display; ?>" class="nav-item  <?php if($url == 'users.php' || $newurl_page[0] == 'users.php' || $newurl_page[0] == 'add-user.php.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="users.php" class="nav-link ">
                                        <span class="title">Users</span>
                                    </a>
                                </li>
                                <li style="display: <?php echo $checked_reminderwasher_display; ?>" class="nav-item  <?php if($url == 'reminder-washer.php' || $newurl_page[0] == 'reminder-washer.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="reminder-washer.php" class="nav-link ">
                                        <span class="title">Reminder Washer</span>
                                    </a>
                                </li>
                                <li style="display: <?php echo $checked_reminderclient_display; ?>" class="nav-item  <?php if($url == 'reminder-client.php' || $newurl_page[0] == 'reminder-client.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="reminder-client.php" class="nav-link ">
                                        <span class="title">Reminder Client</span>
                                    </a>
                                </li>
                                <li class="nav-item  <?php if($url == 'reply-message.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="reply-message.php" class="nav-link ">
                                        <span class="title">Manage Reply Message</span>
                                    </a>
                                </li>
                                <li style="display: <?php echo $checked_backup_db_display; ?>" class="nav-item  <?php if($url == 'db-backup.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="db-backup.php" class="nav-link ">
                                        <span class="title">Backup DB</span>
                                    </a>
                                </li>
								<li style="display: <?php echo $checked_backup_db_display; ?>" class="nav-item  <?php if($url == 'newsletters.php' || $url == 'newsletters.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="newsletters.php" class="nav-link ">
                                        <span class="title">Newsletters</span>
                                    </a>
									<ul class="sub-menu">
										<li style="display: <?php echo $checked_backup_db_display; ?>" class="nav-item  <?php if($url == 'newsletter-subscribers.php') { $open_agent = 'open'; echo 'active open'; } ?>">
											<a href="newsletter-subscribers.php" class="nav-link ">
												<span class="title">Newsletter Subscribers</span>
											</a>
										</li>
										<li style="display: <?php echo $checked_backup_db_display; ?>" class="nav-item  <?php if($url == 'add-newsletter.php') { $open_agent = 'open'; echo 'active open'; } ?>">
																			<a href="add-newsletter.php" class="nav-link ">
																				<span class="title">Add Newsletter</span>
																			</a>
										</li>
									</ul>
                                </li>
								<li style="display: <?php echo $checked_backup_db_display; ?>" class="nav-item  <?php if($url == 'coverage-area-zipcodes.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="coverage-area-zipcodes.php" class="nav-link ">
                                        <span class="title">Coverage Area Zipcodes</span>
                                    </a>
                                </li>
                                <li style="display: <?php echo $checked_backup_db_display; ?>" class="nav-item  <?php if($url == 'coverage-area-cities.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="coverage-area-cities.php" class="nav-link ">
                                        <span class="title">Coverage Area Cities</span>
                                    </a>
                                </li>
                                <li style="display: <?php echo $checked_command_center_display; ?>" class="nav-item  <?php if($url == 'command-center.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="http://www.getmobilewash.com/admin-new/command-center.php" target="_blank" class="nav-link ">
                                        <span class="title">Command Center</span>
                                    </a>
                                </li>
								<li style="display: <?php echo $checked_show_calendar_display; ?>" class="nav-item  <?php if($url == 'show-calendar.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="show-calendar.php" class="nav-link ">
                                        <span class="title">Order Calendar</span>
                                    </a>
                                </li>
								<li class="nav-item <?php if($url == 'list-review.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $checked_show_review_display; ?>">
                                    <a href="list-review.php" class="nav-link ">
                                        <span class="title">Review</span>
                                    </a>
									<ul class="sub-menu">
										<li class="nav-item <?php if($url == 'add-review.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $checked_show_review_display; ?>">
											<a href="add-edit-review.php?action=add" class="nav-link ">
												<span class="title">Add/Edit Review</span>
											</a>
										</li>
										
									</ul>
                                </li>
                                <li style="display: <?php echo $checked_show_calendar_display; ?>" class="nav-item  <?php if($url == 'bug-report.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="bug-report.php" class="nav-link ">
                                        <span class="title">Bug Report</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="nav-item  <?php if($url == 'manage-pre-clients.php' || $newurl_page[0] == 'manage-customers.php' || $url == 'non-return-customers.php' || $newurl_page[0] == 'non-return-customers.php' || $url == 'trash-pre-clients.php' || $url == 'client_dashboard.php' || $newurl_page[0] == 'pre-clients-details.php' || $url == 'manage-customers.php' || $newurl_page[0] == 'edit-customer.php' || $url == 'feedbacks.php' || $url == 'customer-notifications.php' || $newurl_page[0] == 'manage-pre-clients.php') { $open_client = 'open'; echo 'active open'; } ?>" style="display: <?php echo $client_module_show; ?>;">
                            <a href="client_dashboard.php" class="nav-link nav-toggle">
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
                                    <a href="manage-customers.php?limit=400" class="nav-link ">
                                        <span class="title">Manage Customers</span>
                                    </a>
                                </li>
                                <li class="nav-item  <?php if($url == 'non-return-customers.php' || $newurl_page[0] == 'non-return-customers.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="non-return-customers.php" class="nav-link ">
                                        <span class="title">Non Returning Customers</span>
                                    </a>
                                </li>
                                <li class="nav-item  <?php if($url == 'feedbacks.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="feedbacks.php" class="nav-link ">
                                        <span class="title">Feedbacks</span>
                                    </a>
                                </li>
                                 <li class="nav-item  <?php if($url == 'mobilewasher-service-feedbacks.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="mobilewasher-service-feedbacks.php" class="nav-link ">
                                        <span class="title">MobileWasher Service Feedbacks</span>
                                    </a>
                                </li>
                                <li class="nav-item  <?php if($url == 'customer-notifications.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="customer-notifications.php" class="nav-link ">
                                        <span class="title">Customer Push Notifications</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="nav-item  <?php if($url == 'manage-pre-washers.php' || $newurl_page[0] == 'manage-pre-washers.php' || $url == 'add-new-washer.php' || $url == 'washer-notifications.php' || $url == 'top-washers.php' || $newurl_page[0] == 'add-new-washer.php' || $newurl_page[0] == 'manage-agents.php' || $url == 'trash-pre-washers.php' || $url == 'washer_dashboard.php' || $newurl_page[0] == 'pre-washer-details.php' || $url == 'manage-agents.php' || $newurl_page[0] == 'edit-agent.php' || $url == 'act-washer-details.php' || $newurl_page[0] == 'act-washer-details.php' || $url == 'active-washers.php' || $newurl_page[0] == 'active-washers.php' || $newurl[1] == 'add-agent.php') { $open_agent = 'open'; echo 'active open'; } ?>" style="display: <?php echo $washer_module_show; ?>;">
                            <a href="washer_dashboard.php">
                                <i class="icon-layers"></i>
                                <span class="title">Washer</span>
                                <span class="arrow nav-link nav-toggle <?php echo $open_agent; ?>"></span></a>
                            
                            <ul class="sub-menu">
                                <li class="nav-item  <?php if($url == 'manage-pre-washers.php' || $newurl_page[0] == 'manage-pre-washers.php' || $url == 'trash-pre-washers.php' || $newurl_page[0] == 'pre-washer-details.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="manage-pre-washers.php" class="nav-link ">
                                        <span class="title">Pre-Registered Washers</span>
                                    </a>
                                </li>
                              
                                <!--<li class="nav-item  <?php if($url == 'add-new-washer.php' || $newurl_page[0] == 'add-new-washer.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="add-new-washer.php" class="nav-link ">
                                        <span class="title">Add New Washer</span>
                                    </a>
                                </li>-->
                                <li class="nav-item  <?php if($url == 'manage-agents.php' || $newurl_page[0] == 'manage-agents.php' || $newurl_page[0] == 'edit-agent.php' || $newurl[1] == 'add-agent.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="manage-agents.php?type=demo" class="nav-link ">
                                        <span class="title">Manage Agents</span>
                                    </a>
                                </li>
                                <li class="nav-item  <?php if($url == 'washer-notifications.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="washer-notifications.php" class="nav-link ">
                                        <span class="title">Washer Push Notifications</span>
                                    </a>
                                </li>
				<li class="nav-item  <?php if($url == 'top-washers.php') { $open_agent = 'open'; echo 'active open'; } ?>">
                                    <a href="top-washers.php" class="nav-link ">
                                        <span class="title">Top Washers</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                                                
                        
                                                
                        
                                                
                        
                    </ul>
                    <!-- END SIDEBAR MENU -->
                    <!-- END SIDEBAR MENU -->
<a class="add-bug-btn" href="add-new-bug.php" style="display: block;margin: 0 auto;color: #fff;text-align: center;padding: 10px;margin: 20px 45px;box-sizing: border-box;border-radius: 50px;text-decoration: none;border: 2px solid #b4bcc8;">Report Bug</a>
 <form class="sidebar-search  " action="search.php" method="GET">
                                
                               
                                    <input style="background: #fff; padding: 6px; width: 100%; display: block; margin-bottom: 10px;" type="text" class="form-control" name="q" placeholder="Search..." required>
                                
                               
                                    <select name="search_area" style="background: #fff; padding: 6px; width: 100%; display: block; margin-bottom: 10px;" required>
                                        <option value="">-- Select Search Area --</option>
                                        <option value="Order Number">Order Number</option>
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
</div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->