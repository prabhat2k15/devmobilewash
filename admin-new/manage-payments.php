<?php include('header.php') ?>

<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js"></script>
        <!-- END PAGE LEVEL PLUGINS -->


<?php include('right-sidebar.php') ?>


<style>


</style>
<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->

                    <!-- END PAGE HEADER-->
                    <!-- BEGIN DASHBOARD STATS 1-->
                    <div class="row">
                        <div class="col-md-12">

                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-settings font-dark"></i>
                                        <span class="caption-subject bold uppercase"> Manage Payments </span>
                                    </div>
                                    <div class="caption font-dark">

                                        <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </div>

                                   <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>

                                </div>
                                <div class="portlet-body">
                                  
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
						<th> Order ID </th>
                                                <th> Transaction ID </th>
                                                <th> Submitted for Settlement </th>
                                                <th> Transaction Status </th>
						<th> Amount Submitted </th>
						<th> Washer Payment </th>
						<th> Service Fee </th>
						<th> Real Washer ID </th>
						<th> Merchant Account </th>
                                            </tr>
                                        </thead>
                                        <tbody>

             
                                      </tbody>
                                    </table>
                                 
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>
                    </div>
                    <div class="clearfix"></div>

                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->

            <style>
            .fullwidth{
                width: 14% !important;
            }
            </style>
            <?php include('footer.php') ?>

            <!-- BEGIN PAGE LEVEL SCRIPTS -->
             <!--<script src='js/jquery.voicerss-tts.min.js'></script>-->
      
        <script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
            