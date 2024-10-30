<style type="text/css">
.postbox-header .hndle {
    /*padding-left: 10px;*/
    text-transform: uppercase;
    font-size: 20px;
        margin-top: 0.5rem;
}
.detail-left {
    float: left;
}
.detail-right {
    float: right;
}
.width-set {
    margin-bottom: 0px;
}
.width-set li {
    display: inline-block;
    margin-right: 10px;
    margin-bottom: 0px;
}
.width-set p {
    text-align: right;
    margin-bottom: 0px;
}

@media (max-width: 767px) {
.detail-left {
    float: none;
}
.detail-right {
    float: left;
}
.width-set p {
    text-align: left;
}
}
.wrap h2{
margin-left: 9px;
}
.detail-2{
    padding-top: 17px;
}

.postbox{
    border-radius: 10px;
    margin-top: 17px;
}
</style>
<div class="clearfix detail-2">
    <div class="detail-left">
        <label for="title"><?php _e('L.STAMP - Notarize Posts & Pages', 'custom_table_example_userlist')?></label>
    </div>
    <div class="detail-right">
         <ul class="width-set">
            <?php if(isset($data->status) && $data->status == 'success'){?>
            <li> <button type="button" class="button-primary "><?php echo esc_html("Storage Left: ".$data->data->storage_left." MB");?></button>
                <p><a href="#">Buy More Space</a></p>
            </li>
            <li><button type="button" class="button-primary "><?php echo esc_html("Current File Upload: ".$data->data->file_upload." MB");?></button> 
                <p><a href="#">Increase the File Size</a></p>
            </li>
            <li class="mr-0"><button type="button" class="button-primary "><?php echo esc_html("Remaining Bc: ".$data->data->global_trxn);?></button>
                <p><a href="#">Buy More Blockchain Tx</a></p>
            </li>
            <?php }else{?>
            <li><?php echo esc_html("Please go to setings connect first to see package data!");?></li>
            <?php }?>
         </ul>
    </div>
</div>

<div class="modal" id = "lstampconncet" tabindex="-1" role="dialog" style = "display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action = "<?php echo esc_url(admin_url('admin-post.php')); ?>" method = "post">
            <div class="modal-header">
                <h5 class="modal-title">L.Stamp</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
                    <tbody>
                        <tr class="form-field">
                            <th valign="top" scope="row">
                                <label for="title"><?php _e('Email', 'custom_table_example_userlist')?></label>
                            </th>
                            <td>
                                <input required id="user_email" name="user_email" type="text" style="width: 95%"
                                size="50" class="code">
                            </td>
                        </tr>
                        <tr class="form-field">
                            <th valign="top" scope="row">
                                <label for="price"><?php _e('Password', 'custom_table_example_userlist')?></label>
                            </th>
                            <td>
                                <input id="user_pass" name="user_pass" type="password" style="width: 95%"
                                class="code" required>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <input type = "hidden" name = "type" value = "lstamp">           
            <input type = "hidden" name = "action" value = "connect">           
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Connect</button>
            </div>
        </form>
        </div>
    </div>
</div>

<script>

    jQuery(document).on('click ','.connect', function () {
        jQuery('#lstampconncet').modal('show');
    })

    //      jQuery('#myModal').on('shown.bs.modal', function () {
        //   jQuery('#myInput').trigger('focus')
        // })
    </script>