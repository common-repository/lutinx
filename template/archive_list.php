<style>
    .link a{
            padding: 3px 23px;
    text-transform: uppercase;
    font-size: 13px;
    }

    .link2 a{
            padding: 3px 23px;
    text-transform: uppercase;
    font-size: 13px;
    }

    .row-actions{
        margin-top: 5px;
    }

</style>

<div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Blockchain Notarized Pages & Posts', 'custom_table_example_userlist')?>
    </h2>
    <?php echo esc_html($message); ?>

    <form id="persons-table" method="GET">
        <input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page']); ?>"/>
        <?php $table->display()?>
    </form>

</div>

<script>
    function copyshortlink(e) {
        var copyText = jQuery(e).parent().parent().children().children().attr("href");
        //var copyText = jQuery("#lrxisurl_"+id).attr("href");
       var $temp = jQuery("<input>");
       jQuery("body").append($temp);
       $temp.val(copyText).select();
       document.execCommand("copy");
       $temp.remove();
       alert('Link copied');
        //console.log(txt);
    }
</script>