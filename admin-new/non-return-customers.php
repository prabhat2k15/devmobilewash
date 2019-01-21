<?php include('header.php') ?>

<style>
    .customcsv{
        opacity: 0;
    }

    .dataTables_info{
        display: none;
    }
</style>
<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script type="text/javascript">
    var table;
    $(document).ready(function () {
        $('.custom-pagination').show();
        table = $('#example1, #example2, #example3').dataTable({
            "pageLength": 20,
            "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]],
            "bPaginate": false,
            "aaSorting": [],
            "sDom": "<'row'<'col-sm-5'l><'col-sm-3 text-center customcsv'B><'col-sm-4'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [
                'csvHtml5'
            ]
        });


        $('.csv-link').on('click', function () {
            $('.buttons-csv').trigger('click');
        });

        oTable1 = $('#example1').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
        oTable2 = $('#example2').DataTable();
        oTable3 = $('#example3').DataTable();

        $('#customSearch1').keyup(function () {
            var val = $(this).val();
            if (val.length > 0) {
                $('.custom-pagination').hide();
            } else {
                $('.custom-pagination').show();
            }
            oTable1.search($(this).val()).draw();
            oTable2.search($(this).val()).draw();
            oTable3.search($(this).val()).draw();
        })
    });
</script>

<?php include('right-sidebar.php') ?>

<?php
$page_number = 1;
$range = 30;
if (isset($_GET['page_number']))
    $page_number = $_GET['page_number'];
if (isset($_GET['range']))
    $range = $_GET['range'];

$url = ROOT_URL . '/api/index.php?r=site/getnonreturncustomers';


//echo $url;
$handle = curl_init($url);
$data = array('key' => API_KEY, 'page_number' => $page_number, 'range' => $range, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
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

    .custom-pagination{
        text-align: center;
        margin: 10px 0;
    }

    .custom-pagination a{
        padding: 5px 10px;
        background: #337ab7;
        color: #fff;
        margin-right: 2px; 
    }

    .custom-pagination a:hover{
        text-decoration: none;
    }
    .remove-dattable-search .dataTables_filter{
        display:none;

    }
    .cstm-search{
        float:left;
        margin:6px 20px;
    }
    .cstm-search label{
        font-weight:600;
    }
    .cstm-search input{
        padding:5px;
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
                            <span class="caption-subject bold uppercase" style="color: #000"> Non-Returning Customers</span> <a style="margin-left: 20px;" target="_blank" class="csv-link" href="javascript:void(0)">Download CSV</a>
                        </div>
                        <div class="cstm-search"><label>Search:</label> <input type="text" id="customSearch" placeholder="Search....."></div>
                        <ul class="nav nav-tabs">
                            <li <?php if (($_GET['range'] == 30) || (!isset($_GET['range']))) echo "class='active'"; ?>>
                                <a href="#tab_1_1" data-toggle="tab">30 Days</a>
                            </li>
                            <li <?php if (($_GET['range'] == 60)) echo "class='active'"; ?>>
                                <a href="#tab_1_2" data-toggle="tab">60 Days</a>
                            </li>
                            <li <?php if (($_GET['range'] == 90)) echo "class='active'"; ?>>
                                <a href="#tab_1_3" data-toggle="tab">90 Days</a>
                            </li>
                        </ul>
                    </div>
                    <div class="portlet-body remove-dattable-search">
                        <div class="tab-content">
                            <div class="tab-pane <?php if (($_GET['range'] == 30) || (!isset($_GET['range']))) echo "active"; ?>" id="tab_1_1">

                                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                    <!--table class="table table-striped table-bordered table-hover table-checkable order-column"-->
                                    <thead>
                                        <tr>

                                            <th> ID </th>
                                            <th> Customer Name </th>
                                            <th> Email </th>
                                            <th> Phone </th>
                                            <th> Washes </th>
                                            <!--<th> Last Order </th>-->

                                        </tr>
                                    </thead>
                                    <tbody id="searchResultFor30">
                                        <?php
                                        if (count($allcustomers->nonreturncusts_30)) {

                                            foreach ($allcustomers->nonreturncusts_30 as $customer) {
                                                ?>
                                                <tr class="odd gradeX">
                                                    <td> <?php echo $customer->id; ?> </td>
                                                    <td> <?php echo $customer->first_name . " " . $customer->last_name; ?> </td>
                                                    <td> <?php echo $customer->email; ?> </td>
                                                    <td> <?php echo $customer->contact_number; ?> </td>
                                                    <td> <?php
                                                        if ($customer->total_wash > 0)
                                                            echo "<a target='_blank' href='" . ROOT_URL . "/admin-new/all-orders.php?customer_id=" . $customer->id . "'>" . $customer->total_wash . "</a>";
                                                        else
                                                            echo $customer->total_wash;
                                                        ?> </td>


                                                </tr>

                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php if ((!isset($_GET['range'])) || ($_GET['range'] == 30)): ?>
                                    <div class='custom-pagination'>
                                        <?php if (count($allcustomers->nonreturncusts_30)): ?>
                                            <?php if (isset($_GET['page_number']) && ($_GET['page_number'] > 1)): ?>
                                                <p style="text-align: left;">Showing <?php echo ((($_GET['page_number'] - 1) * 100) + 1); ?> to <?php echo (($_GET['page_number'] - 1) * 100) + count($allcustomers->nonreturncusts_30); ?> of <?php echo $allcustomers->total_entries_30; ?> entries</p>
                                            <?php else: ?>
                                                <p style="text-align: left;">Showing 1 to <?php echo count($allcustomers->nonreturncusts_30); ?> of <?php echo $allcustomers->total_entries_30; ?> entries</p>

                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php
                                        if ($page_number != 1)
                                            echo "<a href='" . ROOT_URL . "/admin-new/non-return-customers.php?page_number=1&range=30'>&laquo;</a> ";
                                        for ($i = $page_number + 1, $j = 1; $i <= $allcustomers->total_pages_30; $i++, $j++) {
                                            echo "<a href='" . ROOT_URL . "/admin-new/non-return-customers.php?range=30&page_number=" . $i . "'>" . $i . "</a> ";
                                            if ($j == 5)
                                                break;
                                        }
                                        if ($page_number != $allcustomers->total_pages_30)
                                            echo "<a href='" . ROOT_URL . "/admin-new/non-return-customers.php?range=30&page_number=" . $allcustomers->total_pages_30 . "'>&raquo;</a> ";
                                        ?>
                                    </div>
                                <?php else: ?>
                                    <?php if (count($allcustomers->nonreturncusts_30)): ?>
                                        <p style="text-align: left;">Showing 1 to <?php echo count($allcustomers->nonreturncusts_30); ?> of <?php echo $allcustomers->total_entries_30; ?> entries</p>
                                    <?php endif; ?>
                                    <?php if ($allcustomers->total_pages_30 > 1): ?>

                                        <div class='custom-pagination'>

                                            <?php
                                            for ($i = 1, $j = 1; $i <= $allcustomers->total_pages_30; $i++, $j++) {
                                                echo "<a href='" . ROOT_URL . "/admin-new/non-return-customers.php?range=30&page_number=" . $i . "'>" . $i . "</a> ";
                                                if ($j == 5)
                                                    break;
                                            }
                                            echo "<a href='" . ROOT_URL . "/admin-new/non-return-customers.php?range=30&page_number=" . $allcustomers->total_pages_30 . "'>&raquo;</a> ";
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="tab-pane <?php if (($_GET['range'] == 60)) echo "active"; ?>" id="tab_1_2">
                                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example2">
                                    <!--table class="table table-striped table-bordered table-hover table-checkable order-column"-->
                                    <thead>
                                        <tr>

                                            <th> ID </th>
                                            <th> Customer Name </th>
                                            <th> Email </th>
                                            <th> Phone </th>
                                            <th> Washes </th>
                                            <!--<th> Last Order </th>-->

                                        </tr>
                                    </thead>
                                    <tbody id="searchResultFor60">
                                        <?php
                                        if (count($allcustomers->nonreturncusts_60)) {

                                            foreach ($allcustomers->nonreturncusts_60 as $customer) {
                                                ?>
                                                <tr class="odd gradeX">
                                                    <td> <?php echo $customer->id; ?> </td>
                                                    <td> <?php echo $customer->first_name . " " . $customer->last_name; ?> </td>
                                                    <td> <?php echo $customer->email; ?> </td>
                                                    <td> <?php echo $customer->contact_number; ?> </td>
                                                    <td> <?php
                                                        if ($customer->total_wash > 0)
                                                            echo "<a target='_blank' href='" . ROOT_URL . "/admin-new/all-orders.php?customer_id=" . $customer->id . "'>" . $customer->total_wash . "</a>";
                                                        else
                                                            echo $customer->total_wash;
                                                        ?> </td>



                                                </tr>

                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php if (($_GET['range'] == 60)): ?>
                                    <div class='custom-pagination'>
                                        <?php if (count($allcustomers->nonreturncusts_60)): ?>
                                            <?php if (isset($_GET['page_number']) && ($_GET['page_number'] > 1)): ?>
                                                <p style="text-align: left;">Showing <?php echo ((($_GET['page_number'] - 1) * 100) + 1); ?> to <?php echo (($_GET['page_number'] - 1) * 100) + count($allcustomers->nonreturncusts_60); ?> of <?php echo $allcustomers->total_entries_60; ?> entries</p>
                                            <?php else: ?>
                                                <p style="text-align: left;">Showing 1 to <?php echo count($allcustomers->nonreturncusts_60); ?> of <?php echo $allcustomers->total_entries_60; ?> entries</p>

                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php
                                        if ($page_number != 1)
                                            echo "<a href='" . ROOT_URL . "/admin-new/non-return-customers.php?page_number=1&range=60'>&laquo;</a> ";
                                        for ($i = $page_number + 1, $j = 1; $i <= $allcustomers->total_pages_60; $i++, $j++) {
                                            echo "<a href='" . ROOT_URL . "/admin-new/non-return-customers.php?range=60&page_number=" . $i . "'>" . $i . "</a> ";
                                            if ($j == 5)
                                                break;
                                        }
                                        if ($page_number != $allcustomers->total_pages_60)
                                            echo "<a href='" . ROOT_URL . "/admin-new/non-return-customers.php?range=60&page_number=" . $allcustomers->total_pages_60 . "'>&raquo;</a> ";
                                        ?>
                                    </div>
                                <?php else: ?>
                                    <?php if (count($allcustomers->nonreturncusts_60)): ?>
                                        <p style="text-align: left;">Showing 1 to <?php echo count($allcustomers->nonreturncusts_60); ?> of <?php echo $allcustomers->total_entries_60; ?> entries</p>
                                    <?php endif; ?>
                                    <?php if ($allcustomers->total_pages_60 > 1): ?>
                                        <div class='custom-pagination'>

                                            <?php
                                            for ($i = 1, $j = 1; $i <= $allcustomers->total_pages_60; $i++, $j++) {
                                                echo "<a href='" . ROOT_URL . "/admin-new/non-return-customers.php?range=60&page_number=" . $i . "'>" . $i . "</a> ";
                                                if ($j == 5)
                                                    break;
                                            }
                                            echo "<a href='" . ROOT_URL . "/admin-new/non-return-customers.php?range=60&page_number=" . $allcustomers->total_pages_60 . "'>&raquo;</a> ";
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="tab-pane <?php if (($_GET['range'] == 90)) echo "active"; ?>" id="tab_1_3">
                                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example3">
                                    <!--table class="table table-striped table-bordered table-hover table-checkable order-column"-->
                                    <thead>
                                        <tr>

                                            <th> ID </th>
                                            <th> Customer Name </th>
                                            <th> Email </th>
                                            <th> Phone </th>
                                            <th> Washes </th>
                                            <!--<th> Last Order </th>-->

                                        </tr>
                                    </thead>
                                    <tbody id="searchResultFor90">
                                        <?php
                                        if (count($allcustomers->nonreturncusts_90)) {

                                            foreach ($allcustomers->nonreturncusts_90 as $customer) {
                                                ?>
                                                <tr class="odd gradeX">
                                                    <td> <?php echo $customer->id; ?> </td>
                                                    <td> <?php echo $customer->first_name . " " . $customer->last_name; ?> </td>
                                                    <td> <?php echo $customer->email; ?> </td>
                                                    <td> <?php echo $customer->contact_number; ?> </td>
                                                    <td> <?php
                                                        if ($customer->total_wash > 0)
                                                            echo "<a target='_blank' href='" . ROOT_URL . "/admin-new/all-orders.php?customer_id=" . $customer->id . "'>" . $customer->total_wash . "</a>";
                                                        else
                                                            echo $customer->total_wash;
                                                        ?> </td>



                                                </tr>

                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php if (($_GET['range'] == 90)): ?>
                                    <div class='custom-pagination'>
                                        <?php if (count($allcustomers->nonreturncusts_90)): ?>
                                            <?php if (isset($_GET['page_number']) && ($_GET['page_number'] > 1)): ?>
                                                <p style="text-align: left;">Showing <?php echo ((($_GET['page_number'] - 1) * 100) + 1); ?> to <?php echo (($_GET['page_number'] - 1) * 100) + count($allcustomers->nonreturncusts_90); ?> of <?php echo $allcustomers->total_entries_90; ?> entries</p>
                                            <?php else: ?>
                                                <p style="text-align: left;">Showing 1 to <?php echo count($allcustomers->nonreturncusts_90); ?> of <?php echo $allcustomers->total_entries_90; ?> entries</p>

                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php
                                        if ($page_number != 1)
                                            echo "<a href='" . ROOT_URL . "/admin-new/non-return-customers.php?page_number=1&range=90'>&laquo;</a> ";
                                        for ($i = $page_number + 1, $j = 1; $i <= $allcustomers->total_pages_90; $i++, $j++) {
                                            echo "<a href='" . ROOT_URL . "/admin-new/non-return-customers.php?range=90&page_number=" . $i . "'>" . $i . "</a> ";
                                            if ($j == 5)
                                                break;
                                        }
                                        if ($page_number != $allcustomers->total_pages_90)
                                            echo "<a href='" . ROOT_URL . "/admin-new/non-return-customers.php?range=90&page_number=" . $allcustomers->total_pages_90 . "'>&raquo;</a> ";
                                        ?>
                                    </div>
                                <?php else: ?>
                                    <?php if (count($allcustomers->nonreturncusts_90)): ?>
                                        <p style="text-align: left;">Showing 1 to <?php echo count($allcustomers->nonreturncusts_90); ?> of <?php echo $allcustomers->total_entries_90; ?> entries</p>
                                    <?php endif; ?>
                                    <?php if ($allcustomers->total_pages_90 > 1): ?>

                                        <div class='custom-pagination'>

                                            <?php
                                            for ($i = 1, $j = 1; $i <= $allcustomers->total_pages_90; $i++, $j++) {
                                                echo "<a href='" . ROOT_URL . "/admin-new/non-return-customers.php?range=90&page_number=" . $i . "'>" . $i . "</a> ";
                                                if ($j == 5)
                                                    break;
                                            }
                                            echo "<a href='" . ROOT_URL . "/admin-new/non-return-customers.php?range=90&page_number=" . $allcustomers->total_pages_90 . "'>&raquo;</a> ";
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
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
    /*
     $(function(){
     $(".nav-tabs a").click(function(){
     
     if ($(this).text() == '30 Days') {
     $('.csv-link').attr('href', "<?php echo ROOT_URL; ?>/api/index.php?r=site/nonreturncustscsvexport&page_number=<?php echo $page_number; ?>&range=30&key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4");
     }
     if ($(this).text() == '60 Days') {
     $('.csv-link').attr('href', "<?php echo ROOT_URL; ?>/api/index.php?r=site/nonreturncustscsvexport&page_number=<?php echo $page_number; ?>&range=60&key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4");
     }
     if ($(this).text() == '90 Days') {
     $('.csv-link').attr('href', "<?php echo ROOT_URL; ?>/api/index.php?r=site/nonreturncustscsvexport&page_number=<?php echo $page_number; ?>&range=90&key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4");
     }
     });
     });*/
</script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>

<!-- END PAGE LEVEL SCRIPTS -->
<style>

    .page-content-wrapper .page-content{
        padding: 0 20px 10px !important;
    }
</style>
<script>
    $('#customSearch').keyup(function () {
        var val = $(this).val();
        if (val.length > 0) {
            $('.custom-pagination').hide();
        } else {
            $('.custom-pagination').show();
        }
        console.log(val);
        $.ajax({
            type: "GET",
            url: "<?php echo ROOT_URL; ?>/api/index.php?r=customers/SearchCustomerNonReturn&search_query=" + $.trim(val),
            data: {'test': 'test'},
            success: function (data) {
                console.log(data);
                var data = jQuery.parseJSON(data);
                //console.log(data)
                var html = '';
                $.each(data, function (i, item) {
                    html += '<tr class="odd gradeX">';
                    html += '<td>' + item.id;
                    +'</td>';
                    html += '<td>' + item.first_name + ' ' + item.last_name
                            + '</td>';
                    html += '<td>' + item.email;
                    +'</td>';
                    html += '<td>' + item.contact_number;
                    +'</td>';
                    if (item.total_wash != 0) {
                        html += '<td><a target="_blank" href="/admin-new/all-orders.php?customer_id=' + item.id + '">' + item.total_wash + '</a></td>';
                    } else {
                        html += '<td>' + item.total_wash;
                        +'</td>';
                    }
                    html += '</tr>';
                    if (item.nonreturn_cat == 90) {
                        $('#searchResultFor90').html('');
                        $('#searchResultFor90').append(html);
                    }
                    if (item.nonreturn_cat == 60) {
                        $('#searchResultFor60').html('');
                        $('#searchResultFor60').append(html);
                    }
                    if (item.nonreturn_cat == 30) {
                        $('#searchResultFor30').html('');
                        $('#searchResultFor30').append(html);
                    }

                });
                console.log(html);
                // append();
            }
        });
    }
    );
</script>