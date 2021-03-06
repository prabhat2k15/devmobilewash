<?php include('header.php') ?>

<style>
    .customcsv{
        opacity: 0;
    }

    .dataTables_info{
        display: none;
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
<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script type="text/javascript">
    var table;
    $(document).ready(function () {

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
$range = 5;
if (isset($_GET['page_number']))
    $page_number = $_GET['page_number'];
if (isset($_GET['range']))
    $range = $_GET['range'];

$url = ROOT_URL . '/api/index.php?r=site/getinactivecustomers';


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
                            <span class="caption-subject bold uppercase" style="color: #000"> Inactive Customers</span> <a style="margin-left: 20px;" target="_blank" class="csv-link" href="javascript:void(0)">Download CSV</a>
                        </div>
                        <div class="cstm-search"><label>Search:</label> <input type="text" id="customSearch" placeholder="Search....."></div>
                        <ul class="nav nav-tabs">
                            <li <?php if (($_GET['range'] == 5) || (!isset($_GET['range']))) echo "class='active'"; ?>>
                                <a href="#tab_1_1" data-toggle="tab">5 Days</a>
                            </li>
                            <li <?php if (($_GET['range'] == 10)) echo "class='active'"; ?>>
                                <a href="#tab_1_2" data-toggle="tab">10 Days</a>
                            </li>
                            <li <?php if (($_GET['range'] == 30)) echo "class='active'"; ?>>
                                <a href="#tab_1_3" data-toggle="tab">30 Days</a>
                            </li>
                        </ul>
                    </div>
                    <div class="portlet-body remove-dattable-search">
                        <div class="tab-content">
                            <div class="tab-pane <?php if (($_GET['range'] == 5) || (!isset($_GET['range']))) echo "active"; ?>" id="tab_1_1">

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
                                    <tbody id="searchResultFor5">
                                        <?php
                                        if (count($allcustomers->inactivecusts_5)) {

                                            foreach ($allcustomers->inactivecusts_5 as $customer) {
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
                                <?php if ((!isset($_GET['range'])) || ($_GET['range'] == 5)): ?>
                                    <div class='custom-pagination'>
                                        <?php if (count($allcustomers->inactivecusts_5)): ?>
                                            <?php if (isset($_GET['page_number']) && ($_GET['page_number'] > 1)): ?>
                                                <p style="text-align: left;">Showing <?php echo ((($_GET['page_number'] - 1) * 100) + 1); ?> to <?php echo (($_GET['page_number'] - 1) * 100) + count($allcustomers->inactivecusts_5); ?> of <?php echo $allcustomers->total_entries_5; ?> entries</p>
                                            <?php else: ?>
                                                <p style="text-align: left;">Showing 1 to <?php echo count($allcustomers->inactivecusts_5); ?> of <?php echo $allcustomers->total_entries_5; ?> entries</p>

                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php
                                        if ($page_number != 1)
                                            echo "<a href='" . ROOT_URL . "/admin-new/inactive-customers.php?page_number=1&range=5'>&laquo;</a> ";
                                        for ($i = $page_number + 1, $j = 1; $i <= $allcustomers->total_pages_5; $i++, $j++) {
                                            echo "<a href='" . ROOT_URL . "/admin-new/inactive-customers.php?range=5&page_number=" . $i . "'>" . $i . "</a> ";
                                            if ($j == 5)
                                                break;
                                        }
                                        if ($page_number != $allcustomers->total_pages_5)
                                            echo "<a href='" . ROOT_URL . "/admin-new/inactive-customers.php?range=5&page_number=" . $allcustomers->total_pages_5 . "'>&raquo;</a> ";
                                        ?>
                                    </div>
                                <?php else: ?>
                                    <?php if (count($allcustomers->inactivecusts_5)): ?>
                                        <p style="text-align: left;">Showing 1 to <?php echo count($allcustomers->inactivecusts_5); ?> of <?php echo $allcustomers->total_entries_5; ?> entries</p>
                                    <?php endif; ?>
                                    <?php if ($allcustomers->total_pages_5 > 1): ?>

                                        <div class='custom-pagination'>

                                            <?php
                                            for ($i = 1, $j = 1; $i <= $allcustomers->total_pages_5; $i++, $j++) {
                                                echo "<a href='" . ROOT_URL . "/admin-new/inactive-customers.php?range=5&page_number=" . $i . "'>" . $i . "</a> ";
                                                if ($j == 5)
                                                    break;
                                            }
                                            echo "<a href='" . ROOT_URL . "/admin-new/inactive-customers.php?range=5&page_number=" . $allcustomers->total_pages_5 . "'>&raquo;</a> ";
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="tab-pane <?php if (($_GET['range'] == 10)) echo "active"; ?>" id="tab_1_2">
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
                                    <tbody id="searchResultFor10">
                                        <?php
                                        if (count($allcustomers->inactivecusts_10)) {

                                            foreach ($allcustomers->inactivecusts_10 as $customer) {
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
                                <?php if (($_GET['range'] == 10)): ?>
                                    <div class='custom-pagination'>
                                        <?php if (count($allcustomers->inactivecusts_10)): ?>
                                            <?php if (isset($_GET['page_number']) && ($_GET['page_number'] > 1)): ?>
                                                <p style="text-align: left;">Showing <?php echo ((($_GET['page_number'] - 1) * 100) + 1); ?> to <?php echo (($_GET['page_number'] - 1) * 100) + count($allcustomers->inactivecusts_10); ?> of <?php echo $allcustomers->total_entries_10; ?> entries</p>
                                            <?php else: ?>
                                                <p style="text-align: left;">Showing 1 to <?php echo count($allcustomers->inactivecusts_10); ?> of <?php echo $allcustomers->total_entries_10; ?> entries</p>

                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php
                                        if ($page_number != 1)
                                            echo "<a href='" . ROOT_URL . "/admin-new/inactive-customers.php?page_number=1&range=10'>&laquo;</a> ";
                                        for ($i = $page_number + 1, $j = 1; $i <= $allcustomers->total_pages_10; $i++, $j++) {
                                            echo "<a href='" . ROOT_URL . "/admin-new/inactive-customers.php?range=10&page_number=" . $i . "'>" . $i . "</a> ";
                                            if ($j == 5)
                                                break;
                                        }
                                        if ($page_number != $allcustomers->total_pages_10)
                                            echo "<a href='" . ROOT_URL . "/admin-new/inactive-customers.php?range=10&page_number=" . $allcustomers->total_pages_10 . "'>&raquo;</a> ";
                                        ?>
                                    </div>
                                <?php else: ?>
                                    <?php if (count($allcustomers->inactivecusts_10)): ?>
                                        <p style="text-align: left;">Showing 1 to <?php echo count($allcustomers->inactivecusts_10); ?> of <?php echo $allcustomers->total_entries_10; ?> entries</p>
                                    <?php endif; ?>
                                    <?php if ($allcustomers->total_pages_10 > 1): ?>
                                        <div class='custom-pagination'>

                                            <?php
                                            for ($i = 1, $j = 1; $i <= $allcustomers->total_pages_10; $i++, $j++) {
                                                echo "<a href='" . ROOT_URL . "/admin-new/inactive-customers.php?range=10&page_number=" . $i . "'>" . $i . "</a> ";
                                                if ($j == 5)
                                                    break;
                                            }
                                            echo "<a href='" . ROOT_URL . "/admin-new/inactive-customers.php?range=10&page_number=" . $allcustomers->total_pages_10 . "'>&raquo;</a> ";
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="tab-pane <?php if (($_GET['range'] == 30)) echo "active"; ?>" id="tab_1_3">
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
                                    <tbody id="searchResultFor30">
                                        <?php
                                        if (count($allcustomers->inactivecusts_30)) {

                                            foreach ($allcustomers->inactivecusts_30 as $customer) {
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
                                <?php if (($_GET['range'] == 30)): ?>
                                    <div class='custom-pagination'>
                                        <?php if (count($allcustomers->inactivecusts_30)): ?>
                                            <?php if (isset($_GET['page_number']) && ($_GET['page_number'] > 1)): ?>
                                                <p style="text-align: left;">Showing <?php echo ((($_GET['page_number'] - 1) * 100) + 1); ?> to <?php echo (($_GET['page_number'] - 1) * 100) + count($allcustomers->inactivecusts_30); ?> of <?php echo $allcustomers->total_entries_30; ?> entries</p>
                                            <?php else: ?>
                                                <p style="text-align: left;">Showing 1 to <?php echo count($allcustomers->inactivecusts_30); ?> of <?php echo $allcustomers->total_entries_30; ?> entries</p>

                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php
                                        if ($page_number != 1)
                                            echo "<a href='" . ROOT_URL . "/admin-new/inactive-customers.php?page_number=1&range=30'>&laquo;</a> ";
                                        for ($i = $page_number + 1, $j = 1; $i <= $allcustomers->total_pages_30; $i++, $j++) {
                                            echo "<a href='" . ROOT_URL . "/admin-new/inactive-customers.php?range=30&page_number=" . $i . "'>" . $i . "</a> ";
                                            if ($j == 5)
                                                break;
                                        }
                                        if ($page_number != $allcustomers->total_pages_30)
                                            echo "<a href='" . ROOT_URL . "/admin-new/inactive-customers.php?range=30&page_number=" . $allcustomers->total_pages_30 . "'>&raquo;</a> ";
                                        ?>
                                    </div>
                                <?php else: ?>
                                    <?php if (count($allcustomers->inactivecusts_30)): ?>
                                        <p style="text-align: left;">Showing 1 to <?php echo count($allcustomers->inactivecusts_30); ?> of <?php echo $allcustomers->total_entries_30; ?> entries</p>
                                    <?php endif; ?>
                                    <?php if ($allcustomers->total_pages_30 > 1): ?>

                                        <div class='custom-pagination'>

                                            <?php
                                            for ($i = 1, $j = 1; $i <= $allcustomers->total_pages_30; $i++, $j++) {
                                                echo "<a href='" . ROOT_URL . "/admin-new/inactive-customers.php?range=30&page_number=" . $i . "'>" . $i . "</a> ";
                                                if ($j == 5)
                                                    break;
                                            }
                                            echo "<a href='" . ROOT_URL . "/admin-new/inactive-customers.php?range=30&page_number=" . $allcustomers->total_pages_30 . "'>&raquo;</a> ";
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
        $('#example1').DataTable().clear().draw();
        $('#example2').DataTable().clear().draw();
        $('#example3').DataTable().clear().draw();
        var val = $(this).val();
        if (val.length > 0) {
            var URL = "<?php echo ROOT_URL; ?>/api/index.php?r=customers/Searchinactivecustomers&search_query=" + $.trim(val);
            $('.custom-pagination').hide();
        } else {
            $('.custom-pagination').show();
        }
        if (val.length == 0) {
            var URL = "<?php echo ROOT_URL; ?>/api/index.php?r=customers/Searchinactivecustomers";
        }
        $.ajax({
            type: "GET",
            url: URL,
            data: {key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"
            },
            success: function (data) {
                //console.log(data);
                var data = jQuery.parseJSON(data);
                console.log(data.inactivecusts_5);
                var counter = 1;
                var nactivecusts_5html = '';
                $('#example1').DataTable().clear().draw();
                $.each(data.inactivecusts_5, function (i, item) {
                    nactivecusts_5html += '<tr class="odd gradeX ajaxData">';
                    nactivecusts_5html += '<td>' + item.id;
                    +'</td>';
                    nactivecusts_5html += '<td>' + item.first_name + ' ' + item.last_name
                            + '</td>';
                    nactivecusts_5html += '<td>' + item.email;
                    +'</td>';
                    nactivecusts_5html += '<td>' + item.contact_number;
                    +'</td>';
                    if (item.total_wash != 0) {
                        nactivecusts_5html += '<td><a target="_blank" href="/admin-new/all-orders.php?customer_id=' + item.id + '">' + item.total_wash + '</a></td>';
                    } else {
                        nactivecusts_5html += '<td>' + item.total_wash;
                        +'</td>';
                    }
                    nactivecusts_5html += '</tr>';
                });
                $('#searchResultFor5').html(nactivecusts_5html);


                var nactivecusts_10html = '';
                $('#example2').DataTable().clear().draw();
                $.each(data.inactivecusts_10, function (i, item) {
                    nactivecusts_10html += '<tr class="odd gradeX ajaxData">';
                    nactivecusts_10html += '<td>' + item.id;
                    +'</td>';
                    nactivecusts_10html += '<td>' + item.first_name + ' ' + item.last_name
                            + '</td>';
                    nactivecusts_10html += '<td>' + item.email;
                    +'</td>';
                    nactivecusts_10html += '<td>' + item.contact_number;
                    +'</td>';
                    if (item.total_wash != 0) {
                        nactivecusts_10html += '<td><a target="_blank" href="/admin-new/all-orders.php?customer_id=' + item.id + '">' + item.total_wash + '</a></td>';
                    } else {
                        nactivecusts_10html += '<td>' + item.total_wash;
                        +'</td>';
                    }
                    nactivecusts_10html += '</tr>';
                });
                $('#searchResultFor10').html(nactivecusts_10html);


                var inactivecusts_30html = '';
                $('#example3').DataTable().clear().draw();
                $.each(data.inactivecusts_30, function (i, item) {
                    inactivecusts_30html += '<tr class="odd gradeX ajaxData">';
                    inactivecusts_30html += '<td>' + item.id;
                    +'</td>';
                    inactivecusts_30html += '<td>' + item.first_name + ' ' + item.last_name
                            + '</td>';
                    inactivecusts_30html += '<td>' + item.email;
                    +'</td>';
                    inactivecusts_30html += '<td>' + item.contact_number;
                    +'</td>';
                    if (item.total_wash != 0) {
                        inactivecusts_30html += '<td><a target="_blank" href="/admin-new/all-orders.php?customer_id=' + item.id + '">' + item.total_wash + '</a></td>';
                    } else {
                        inactivecusts_30html += '<td>' + item.total_wash;
                        +'</td>';
                    }
                    inactivecusts_30html += '</tr>';
                });
                $('#searchResultFor30').html(inactivecusts_30html);
                // append();
            }
        });
    }
    );
</script>