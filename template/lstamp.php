<?php //print_r($result); die;?>

<style type="text/css">
.postbox {
        border-radius: 10px;
            margin-top: 17px;
}.postbox-header .hndle {
/*    padding-left: 10px;
*/    font-size: 20px;
    margin-top: 0.5rem;
}
.do {
    width: 74%;
}
.logo-1 {
    width: 160px;
    margin-left: 14px;
}
.if-p {
    width: 12px;
    display: inline-block;
    text-align: center;
    cursor: pointer;
    padding-left: 5px;
}
.connect {
    margin-right: 7px;
}
.d-1 label {
    margin-bottom: 0px!important;
}
.input-label {
    padding: 15px 25px;
}
td.input-1 {
    width: 77%;
}
.input-1 input {
    border-radius: 10px;
    border: 1px solid #ccc;
    font-size: 13px!important;
    padding: 5px 10px;
}
.input-label h5 {
    text-decoration: underline;
    font-weight: 500;
    text-transform: uppercase;
    padding-left: 8px;
}
.modal-dialog {
    margin-top: 95px;
}
.modal-content {
    border-radius: 10px;
}
.input-label tr td {
    padding: 10px 10px;
}
.modal-footer {
    border: none;
    padding-top: 0px;
    justify-content: center;
    padding-bottom: 16px;
}

.modal-footer button {
    padding: 7px 23px;
    font-size: 12px;
    border-radius: 10px;
}
.modal-header .close {
    font-weight: normal;
    font-size: 18px;
    padding: 0px;
    margin-top: -6px;
    margin-right: -7px;
}
.cross {
    display: block;
    width: 25px;
    height: 25px;
    padding-top: 2px;
    border-radius: 25px;
}
.cross:hover{
    background-color: #000;
    color: #fff;
}
.label-2 label {
    font-size: 14px;
    font-weight: normal;
}

@media (max-width: 767px) {
    td.input-1 {
    width: 100%;
}
.input-label tr td {
    padding: 3px 10px;
}
}

.d-sm-flex{
    margin-top: 19px;
}
.wrap h2{
margin-left: 9px;
}
<?php

?>
</style>
<?php if (isset($_GET['status']) && isset($_GET['message'])) {
  if ($_GET['status'] == 'error') {?>

    <div class="notice notice-error"><p><?php echo esc_html($_GET['message']); ?></p></div>
<?php }?>

<?php if ($_GET['status'] == 'success') {?>

    <div class="notice notice-success"><p><?php echo esc_html($_GET['message']); ?></p></div>
<?php }
}?>
<?php /* <table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
        <tr class="form-field">
            <td class="label-2" valign="top" scope="row">

            </td>

                <?php if (!empty($result) && $result->purchased == "yes") {?>
                    <td>

                    <button type="button" class="btn btn-success btn-sm"><?php _e('Connected', 'custom_table_example_userlist')?></button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm"><?php _e('Disconnect', 'custom_table_example_userlist')?></button>
                    </td>

                 <?php } else {?>
                    <td>
                    <input type="button" value="<?php _e('Connect', 'custom_table_example_userlist')?>" id="connect" class="button-primary connect" name="connect">

                </td>
                 <?php }?>

        </tr>
    </tbody>
</table> */?>

<div class="d-sm-flex justify-content-sm-between align-items-sm-center">
    <div class="d-1">
        <label for="title"><?php _e('L.STAMP - Notarize Posts & Pages', 'custom_table_example_userlist')?></label>
    </div>
    <div class="d-2">
         <?php if (!empty($result) && $result->purchased == "yes") {?>

        <button type="button" class="btn btn-danger connect btn-sm"><?php _e('Disconnect', 'custom_table_example_userlist')?></button>
        <button type="button" class="btn btn-success  btn-sm"><?php _e('Connected', 'custom_table_example_userlist')?></button>


          <?php } else {?>

            <input type="button" value="<?php _e('Connect', 'custom_table_example_userlist')?>" id="connect" class="button-primary connect" name="connect">

            <?php }?>

    </div>
</div>

<div class="modal" id = "lstampconncet" tabindex="-1" role="dialog" style = "display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action = "<?php echo admin_url('admin-post.php'); ?>" method = "post">
            <div class="modal-header">
                <h5 class="modal-title"><img class="logo-1" src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/Lutinx.png'; ?>"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="cross">&times;</span></button>


            </div>
            <div class="modal-body input-label">
                <h5><?php _e('Connect it to your LutinX Account', 'custom_table_example_userlist')?>:</h5>
                <table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
                    <tbody>
                        <tr class="form-field">
                            <td class="label-2" valign="top" scope="row">
                                <label for="title"><?php _e('Account iD', 'custom_table_example_userlist')?></label>
                            </td>
                            <td class="input-1">
                                <input required id="user_email" name="user_email" type="text" style="width: 99%" size="50" class="code" placeholder = "LRX123456">
                                <input name="poptype" value = "user_check" type="hidden" style="width: 99%" size="50" class="code">
                            </td>
                        </tr>
                        <tr class="form-field">
                            <td class="label-2" valign="top" scope="row">
                                <label for="price"><?php _e('Password', 'custom_table_example_userlist')?></label>
                            </td>
                            <td class="input-1">
                                <input id="user_pass" name="user_pass" type="password" style="width: 99%" class="code" required  placeholder = "Password">
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="do">Do not have a Blockchain Account, create it <a href = "<?php echo esc_url(LUTINXURL); ?>">here</a><span class="if-p" data-bs-toggle="tooltip" title="Please, if you do not have it, you can use this Presentation Code: 541147"><i class="fa fa-question-circle-o" aria-hidden="true"></i></span></td>
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




<div class="modal" id = "authcode" tabindex="-1" role="dialog" style = "display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action = "<?php echo admin_url('admin-post.php'); ?>" method = "post">
            <div class="modal-header">
                <h5 class="modal-title"><img class="logo-1" src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/Lutinx.png'; ?>"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="cross">&times;</span></button>


            </div>
            <div class="modal-body input-label">
                <?php if (isset($_GET['status']) && $_GET['status'] == 'google') {?>

                    <h5><?php _e('Google 2FA Code', 'custom_table_example_userlist')?>:</h5>
                    <table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
                 <?php } else {?>
                    <h5><?php _e('Email 2FA Code', 'custom_table_example_userlist')?></h5>
                    <table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
                 <?php }?>
                    <tbody>
                        <tr class="form-field">
                            <td valign="top" scope="row">
                                <label for="title"><?php _e('Code', 'custom_table_example_userlist')?></label>
                            </td>
                            <td class="input-1">
                                <input required id="auth_code" name="auth_code" type="text" style="width: 95%" size="50" class="code" placeholder = "code">
                                <input name="poptype" value = "user_auth_code" type="hidden" style="width: 95%" size="50" class="code" >
                                <input name="user_auth_type" value = "<?php echo (isset($_GET['status']) ? esc_html($_GET['status']) : '') ?>" type="hidden" style="width: 95%" size="50" class="code" >
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

    jQuery(document).on('click ','#connect', function () {
        jQuery('#lstampconncet').modal('show');
    })

    //      jQuery('#myModal').on('shown.bs.modal', function () {
        //   jQuery('#myInput').trigger('focus')
        // })

 </script>
<?php if (isset($_GET['status']) && ($_GET['status'] == 'google' || $_GET['status'] == 'email')) {?>
    <script>
    jQuery(document).ready(function () {
        jQuery('#authcode').modal('show');
    })
</script>
<?php }?>


