<?php include('header.php') ?>

<style>
    .customcsv{
        opacity: 0;
    }
</style>




<?php include('right-sidebar.php') ?>

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
        margin: 10px;
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
    .Not_msg{
        float:left;
    }
    .time-box{
        float:left; 
    }
    .textBox{
        float: left;padding:1em;
    }
    .textSave{
        float: left;padding-top: 3em;padding-left:3em;
    }
    .text_select_time{
        font-size: 14px;
        font-weight: 600;
    }
    .not_msg{
        float: left;padding-left: 1em;   font-size: 14px;
        font-weight: 600;
         padding-left: 0;
    }
    .textSave{
        font-weight: 600;
    }
    .text_msg{
        float: left;
        margin-top: 8px;
        /*        margin-left: -10em;*/
        margin-bottom: 30px;
    }
    .spacing{
        margin-top:1em;
        margin-bottom:1em;
    }
    .ui-timepicker-wrapper{
        max-width: 192px;
        width: 100%;
    }
    .bold-txt{
        font-weight:600;

    }
    .SelectTime{
        border-radius:3px;
    }
    .savebox{
        margin-top: 2em;
        padding-left: 4em;
    }
    .my-text-align{
        margin-left: 5em;
    }
    .time-input-wrap{
        position:relative;
    }
    .time-input-wrap .time{
        position:relative;
        width:100%;
        padding:6px;
        border:1px solid #ccc;
    }
    .time-input-wrap .cstm-arrw{
        position:relative;
        max-width:192px;
        width:100%;
    }
    .time-input-wrap .cstm-arrw:after{
        content: '';
        position: absolute;
        top:13px;
        right:7px;
        border-top: 8px solid #000;
        border-right: 5px solid transparent;
        border-left: 5px solid transparent;
    }
    
     .portlet-body .select-box-cstm, .portlet-body .select-box-cstm select{
            width: 80px;
           }
           
           .portlet-body .upload-image-wrap{
            max-width: 100%;
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
                            <span class="caption-subject bold" style="color: #000"> INACTIVE CUSTOMER NOTIFICATIONS</span> 
                        </div>

                    </div>
                    <div class="spacing"> 
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject bold" style="color: #000"> 6<sup>th</sup> Day Notifications</span> 
                        </div>
                        <div class="portlet-body">
                            <form id="inactive_form_customer_1">
                                <div class="row my-text-align">
                                    <div class="col-12 col-md-4">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="time-box">
                                                    <span class="text_select_time">Select Time </span>
                                                    <span class="time-input-wrap">
                                                        <div style="margin-top: 6px;">
                                                            <div class="cstm-arrw">
                                                                <input  type="text" required="" name="time" class="time ui-timepicker-input SelectTime" id="setTimeExample" placeholder="Select Time">            
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label>Select Time Format</label>
                                                    <div class="select-box-cstm">
                                                        <select class="form-control">
                                                            <option>AM</option>
                                                            <option>PM</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <div> <span class="not_msg">Notification Message </span> <span class="text_msg"> <textarea name="message" required="" class="borderm form-control emoji" rows="4" cols="50" placeholder="Write Message"></textarea> </span> </div>
                                    <div> <span class="not_msg">Notification Message </span> <span class="text_msg emoji_div"> <textarea name="message" required="" class="borderm form-control emoji" rows="4" cols="50" placeholder="Write Message"></textarea> </span>
                                        </div>
                                        <div>
                                                <div class="form-group upload-image-wrap">
                                                    <label class="btn btn-secondary labelUpload">Upload Email Image</label> <input type="file" id="5"  name="image" class="changeDefaultName photo_validation">
                                                    <span class="disply-img-name img-name-box-5" title=""></span>
                                                </div>

                                            </div>
                                    </div>
                                    <div class="col-12 col-md-3 savebox"> <button type="submit" class="btn btn-primary bold-txt"> SAVE</button> </div>
                                </div>
                            </form>

                        </div>
                    </div>
                    <hr>
                    <div class="spacing"> 
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject bold" style="color: #000"> 11<sup>th</sup> Day Notifications</span> 
                        </div>
                        <div class="portlet-body">

                            <form id="inactive_form_customer_2">
                                <div class="row my-text-align">
                                    <div class="col-12 col-md-4">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="time-box">
                                                    <span class="text_select_time">Select Time </span>
                                                    <span class="time-input-wrap">
                                                        <div style="margin-top: 6px;">
                                                            <div class="cstm-arrw">
                                                                <input  type="text" name="time" required="" class="time ui-timepicker-input SelectTime" id="setTimeExample" placeholder="Select Time">            
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label>Select Time Format</label>
                                                    <div class="select-box-cstm">
                                                        <select class="form-control">
                                                            <option>AM</option>
                                                            <option>PM</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <div> <span class="not_msg">Notification Message </span> <span class="text_msg"> <textarea name="message" required="" class="borderm form-control emoji" rows="4" cols="50" placeholder="Write Message"></textarea> </span> </div>
                                    <div> <span class="not_msg">Notification Message </span> <span class="text_msg emoji_div"> <textarea name="message" required="" class="borderm form-control emoji" rows="4" cols="50" placeholder="Write Message"></textarea> </span>
                                        </div>
                                        <div>
                                                <div class="form-group upload-image-wrap">
                                                    <label class="btn btn-secondary labelUpload">Upload Email Image</label> <input type="file" id="5"  name="image" class="changeDefaultName photo_validation">
                                                    <span class="disply-img-name img-name-box-5" title=""></span>
                                                </div>

                                            </div>
                                    </div>
                                    <div class="col-12 col-md-3 savebox"> <button type="submit" class="btn btn-primary bold-txt"> SAVE</button> </div>
                                </div>
                            </form>


                        </div>
                    </div>         
                    <hr>
                    <div class="spacing"> 
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject bold" style="color: #000"> 31<sup>st</sup> Day Notifications</span> 
                        </div>
                        <div class="portlet-body">
                            <form id="inactive_form_customer_3">
                                <div class="row my-text-align">
                                    <div class="col-12 col-md-4">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="time-box">
                                                    <span class="text_select_time">Select Time </span>
                                                    <span class="time-input-wrap">
                                                        <div style="margin-top: 6px;">
                                                            <div class="cstm-arrw">
                                                                <input name="time"  type="text" required="" class="time ui-timepicker-input SelectTime" id="setTimeExample" placeholder="Select Time">            
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                        <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label>Select Time Format</label>
                                                    <div class="select-box-cstm">
                                                        <select class="form-control">
                                                            <option>AM</option>
                                                            <option>PM</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <div> <span class="not_msg">Notification Message </span> <span class="text_msg"> <textarea  name="message" required="" class="borderm form-control emoji" rows="4" cols="50" placeholder="Write Message"></textarea> </span> </div>
                                    <div> <span class="not_msg">Notification Message </span> <span class="text_msg emoji_div"> <textarea name="message" required="" class="borderm form-control emoji" rows="4" cols="50" placeholder="Write Message"></textarea> </span>
                                        </div>
                                        <div>
                                                <div class="form-group upload-image-wrap">
                                                    <label class="btn btn-secondary labelUpload">Upload Email Image</label> <input type="file" id="5"  name="image" class="changeDefaultName photo_validation">
                                                    <span class="disply-img-name img-name-box-5" title=""></span>
                                                </div>

                                            </div>
                                    </div>
                                    <div class="col-12 col-md-3 savebox"> <button type="submit" class="btn btn-primary bold-txt"> SAVE</button> </div>
                                </div>
                            </form>


                        </div>
                    </div>

                    <!-- body end-->
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
<script>
    $(document).ready(function () {
        $('.SelectTime').timepicker({
            step: '60',
            timeFormat: 'H:i',
        });
        
        $(".photo_validation").change(function () {
        var val = $(this).val();
        switch (val.substring(val.lastIndexOf('.') + 1).toLowerCase()) {
            case 'gif':
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'PNG':
            case 'JPEG 2000':
            case 'BMP':

                break;
            default:
                $(this).val('');
                // error message here
                alert("Image upload format valid format");
                break;
        }
    });
     
     $('.changeDefaultName').on('change', function (e) {
        var id = $(this).attr('id');
        var fileName = e.target.files[0].name;
        $('.img-name-box-' + id).html(fileName);
        $('.img-name-box-' + id).attr('title',fileName);
    });
    });
</script>



<!-- END PAGE LEVEL SCRIPTS -->
<style>

    .page-content-wrapper .page-content{
        padding: 0 20px 10px !important;
    }
</style>

<?php include('footer.php') ?>