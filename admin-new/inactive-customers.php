<?php include('header.php') ?>
<?php
if (isset($_COOKIE['mw_admin_auth'])) {
$device_token = $_COOKIE["mw_admin_auth"];
}
$userdata = array("user_token"=>$device_token, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init(ROOT_URL."/api/index.php?r=users/getusertypebytoken");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result_permission = curl_exec($handle_data);
curl_close($handle_data);
$jsondata_permission = json_decode($result_permission);
?>
<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <script type="text/javascript">
var table;
        $(document).ready(function(){

           table = $('#example1, #example2, #example3').dataTable( {
  "pageLength": 20,
  "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]]
} );


        });
        </script>
 <?php if($jsondata_permission->users_type == 'admin' || $jsondata_permission->users_type == 'superadmin'): ?>
<?php include('right-sidebar.php') ?>
<?php else: ?>
<?php include('navigation-employee.php') ?>
<?php endif; ?>
<?php


    $url = ROOT_URL.'/api/index.php?r=site/getinactivecustomers';


        //echo $url;
        $handle = curl_init($url);
        $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$allcustomers = json_decode($result);

//$response = $jsondata->response;
//$result_code = $jsondata->result;
/*  echo "<pre>";
        print_r($result);
        print_r($jsondata);
        echo "<pre>";
        exit; */


?>
<style>
.label-busy {
    background-color: #FF8C00 !important;
}
.label-online {
    background-color: #16CE0C !important;
}
.label-offline {
    background-color: #FF0202 !important;
}
.pagination ul {
    display: inline-block;
    margin-bottom: 0;
    margin-left: 0;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    -moz-box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
      }
      .pagination li {
    display: inline;
}
li {
    line-height: 20px;
}
user agent stylesheetli {
    display: list-item;
    text-align: -webkit-match-parent;
}
.pagination li:first-child a, .pagination li:first-child span {
    border-left-width: 1px;
    -webkit-border-radius: 3px 0 0 3px;
    -moz-border-radius: 3px 0 0 3px;
    border-radius: 3px 0 0 3px;
}
.pagination a, .pagination span {
    float: left;
    padding: 0 14px;
    line-height: 38px;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #ddd;
    border-left-width: 0;
}
a {
    color: #08c;
    text-decoration: none;
}
.pagination a, .pagination span {
    float: left;
    padding: 0 14px;
    line-height: 38px;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #ddd;
    border-left-width: 0;
}
.pagination a, .pagination span {
    float: left;
    padding: 0 14px;
    line-height: 38px;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #ddd;
    border-left-width: 0;
}
.pagination{
    width: 100%;
}
.portlet-body form {
    padding-bottom: 10px;
}

.cust-search-box{
    margin-bottom: 20px;
display: none;
}

.cust-search-box h2{
    font-size: 26px;
    font-weight: 400;
}
</style>


<style>
.current_tab{
background-color: #5407e2 !important;
border-top: 5px solid #5407e2 !important;
height: 90px !important;
padding: 13px 0 0 10px !important;
cursor: pointer !important;
}
</style>
<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->



                    <div class="clear">&nbsp;</div>

                    <!-- END PAGE HEADER-->
                    <!-- BEGIN DASHBOARD STATS 1-->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light bordered">
                                
                                <div class="portlet-title tabbable-line">
                                                <div class="caption caption-md">
                                                    <i class="icon-globe theme-font hide"></i>
                                                    <span class="caption-subject bold uppercase" style="color: #000"> Inactive Customers</span> <a style="margin-left: 20px;" target="_blank" class="csv-link" href="<?php echo ROOT_URL; ?>/api/index.php?r=site/inactivecustscsvexport&range=5&key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4">Download CSV</a>
                                                </div>
                                                <ul class="nav nav-tabs">
                                                    <li class="active">
                                                        <a href="#tab_1_1" data-toggle="tab">5 Days</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_2" data-toggle="tab">10 Days</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_3" data-toggle="tab">15 Days</a>
                                                    </li>
                                                </ul>
                                  </div>
                                <div class="portlet-body">
                                 <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1_1">

									<table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                    <!--table class="table table-striped table-bordered table-hover table-checkable order-column"-->
                                        <thead>
                                            <tr>

<th> ID </th>
   <th> Customer Name </th>
<th> Email </th>
<th> Phone </th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                     
                                        if(count($allcustomers->nonreturncusts_5)){

                                            foreach($allcustomers->nonreturncusts_5 as $customer){

                                        ?>
                                            <tr class="odd gradeX">
 <td> <?php echo $customer->id; ?> </td>
 <td> <?php echo $customer->name; ?> </td>
<td> <?php echo $customer->email; ?> </td>
<td> <?php echo $customer->phone; ?> </td>

                                            </tr>

                                        <?php

                                            }
                                            }


                                        ?>
                                        </tbody>
                                    </table>
                                 </div>
                                    <div class="tab-pane" id="tab_1_2">
                                       									<table class="table table-striped table-bordered table-hover table-checkable order-column" id="example2">
                                    <!--table class="table table-striped table-bordered table-hover table-checkable order-column"-->
                                        <thead>
                                            <tr>

<th> ID </th>
   <th> Customer Name </th>
<th> Email </th>
<th> Phone </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                     
                                        if(count($allcustomers->nonreturncusts_10)){

                                            foreach($allcustomers->nonreturncusts_10 as $customer){

                                        ?>
                                            <tr class="odd gradeX">
 <td> <?php echo $customer->id; ?> </td>
 <td> <?php echo $customer->name; ?> </td>
<td> <?php echo $customer->email; ?> </td>
<td> <?php echo $customer->phone; ?> </td>
                                            </tr>

                                        <?php

                                            }
                                            }


                                        ?>
                                        </tbody>
                                    </table>
                                    </div>
                                      <div class="tab-pane" id="tab_1_3">
                                       									<table class="table table-striped table-bordered table-hover table-checkable order-column" id="example3">
                                    <!--table class="table table-striped table-bordered table-hover table-checkable order-column"-->
                                        <thead>
                                            <tr>

<th> ID </th>
   <th> Customer Name </th>
<th> Email </th>
<th> Phone </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                     
                                        if(count($allcustomers->nonreturncusts_15)){

                                            foreach($allcustomers->nonreturncusts_15 as $customer){

                                        ?>
                                            <tr class="odd gradeX">
 <td> <?php echo $customer->id; ?> </td>
 <td> <?php echo $customer->name; ?> </td>
<td> <?php echo $customer->email; ?> </td>
<td> <?php echo $customer->phone; ?> </td>

                                            </tr>

                                        <?php

                                            }
                                            }


                                        ?>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                                 
                                </div><!-- body end-->
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                            <div class="clear"></div>

        <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <?php include('footer.php') ?>
            <script>
               $(function(){
                  $(".nav-tabs a").click(function(){
                     
                     if ($(this).text() == '5 Days') {
                        $('.csv-link').attr('href', "<?php echo ROOT_URL; ?>/api/index.php?r=site/inactivecustscsvexport&range=5&key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4");
                     }
                     if ($(this).text() == '10 Days') {
                        $('.csv-link').attr('href', "<?php echo ROOT_URL; ?>/api/index.php?r=site/inactivecustscsvexport&range=10&key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4");
                     }
                     if ($(this).text() == '15 Days') {
                        $('.csv-link').attr('href', "<?php echo ROOT_URL; ?>/api/index.php?r=site/inactivecustscsvexport&range=15&key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4");
                     }
                  });
                  });
            </script>
            <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <style>

.page-content-wrapper .page-content{
    padding: 0 20px 10px !important;
}
        </style>