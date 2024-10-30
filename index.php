<?php
/*
Plugin Name: LutinX 
Description: LutinX is the Blockchain Platform for everyone. Powerful and easy to use.
Plugin URI: https://wordpress.org/lutinx
Author URI: #
Author: LutinX
License: Public Domain
Version: 1.2.2
 */

/**
 * PART 1. Defining Custom Database Table
 * ============================================================================
 *
 * In this part you are going to define custom database table,
 * create it, update, and fill with some dummy data
 *
 * http://codex.wordpress.org/Creating_Tables_with_Plugins
 *
 * In case your are developing and want to check plugin use:
 *
 * DROP TABLE IF EXISTS wp_cte;
 * DELETE FROM wp_options WHERE option_name = 'custom_table_example_userlist_install_data';
 *
 * to drop table and option
 */

/**
 * $custom_table_example_userlist_db_version - holds current database version
 * and used on plugin update to sync database tables
 */

global $custom_table_example_userlist_db_version;
$custom_table_example_userlist_db_version = '1.2.2'; // version changed from 1.0 to 1.1

/**
 * register_activation_hook implementation
 *
 * will be called when user activates plugin first time
 * must create needed database tables
 */

function custom_table_example_userlist_install_userlist()
{
  global $wpdb;
  global $custom_table_example_userlist_db_version;

  if (!defined('LUTINXURL')) {
    // NOTE :  LUTINXURL DEFINED TWO PLACES IN THIS FILE CHANGE BOTH PLACES
    define('LUTINXURL', 'https://int-99a.lutinx.com/');
  }
  //Usage
  $adminIdArray = admin_user_ids();


  $table_name = $wpdb->prefix . 'lstamplist'; // do not forget about tables prefix

  // sql to create your table
  // NOTICE that:
  // 1. each field MUST be in separate line
  // 2. There must be two spaces between PRIMARY KEY and its name
  //    Like this: PRIMARY KEY[space][space](id)
  // otherwise dbDelta will not work
  $sql = "CREATE TABLE " . $table_name . " (
  id int(11) NOT NULL AUTO_INCREMENT,
  admin_id int(11) NOT NULL,
  username tinytext NOT NULL,
  service_type VARCHAR(100) NOT NULL,
  purchased ENUM('no', 'yes') NULL,
  PRIMARY KEY  (id)
);";

$table_name1 = $wpdb->prefix . 'lstamppdf'; // do not forget about tables prefix
$sql1        = "CREATE TABLE " . $table_name1 . " (
id int(11) NOT NULL AUTO_INCREMENT,
admin_id int(11) NOT NULL,
page_post_id int(11) NOT NULL,
username tinytext NOT NULL,
service_type VARCHAR(100) NOT NULL,
filename VARCHAR(255) NULL,
realname VARCHAR(255) NULL,
link VARCHAR(255) NULL,
status enum('active','deactive') NOT NULL,
created datetime NOT NULL,
modified datetime NOT NULL,
PRIMARY KEY  (id)
);";

  // we do not execute sql directly
  // we are calling dbDelta which cant migrate database
require_once ABSPATH . 'wp-admin/includes/upgrade.php';
  dbDelta($sql);
  dbDelta($sql1);

$tablename = $wpdb->prefix . 'usermeta';
$usermeta  = $wpdb->get_row('SELECT * FROM ' . $tablename . ' WHERE `meta_key` = "lutinx_appid"');
if (empty($usermeta)) {
  $app_id = generateRandomString(45);

  $siteurl = get_site_url();

  $url = LUTINXURL . "PluginApi/RegisterDomain/";
  $args = array(
    'site_url' => $siteurl, 
    'app_id'   => $app_id
  );



  $response = wp_remote_post( $url, array(
    'method'      => 'POST',    
    'headers'     => array(),
    'body'        => $args,
    'cookies'     => array()
  )
);

  if ( is_wp_error( $response ) ) {
    $error_message = $response->get_error_message();
    echo esc_html("Something went wrong: $error_message");
  } else {
    $data = json_decode($response['body']);
    if ($data->status == 'success') {
      update_user_meta($adminIdArray[0], 'lutinx_appid', $app_id);
    } else {

    }
  }
}

  // save current database version for later use (on upgrade)
add_option('custom_table_example_userlist_db_version', $custom_table_example_userlist_db_version);

/**
 * [OPTIONAL] Example of updating to 1.1 version
 *
 * If you develop new version of plugin
 * just increment $custom_table_example_userlist_db_version variable
 * and add following block of code
 *
 * must be repeated for each new version
 * in version 1.1 we change email field
 * to contain 200 chars rather 100 in version 1.0
 * and again we are not executing sql
 * we are using dbDelta to migrate table changes
 */

$installed_ver = get_option('custom_table_example_userlist_db_version');
if ($installed_ver != $custom_table_example_userlist_db_version) {

  $sql = "CREATE TABLE " . $table_name . " (
  id int(11) NOT NULL AUTO_INCREMENT,
  admin_id int(11) NOT NULL,
  username tinytext NOT NULL,
  service_type VARCHAR(100) NOT NULL,
  purchased ENUM('no', 'yes') NULL,
  PRIMARY KEY  (id)
);";

$table_name1 = $wpdb->prefix . 'lstamppdf'; // do not forget about tables prefix
$sql1        = "CREATE TABLE " . $table_name1 . " (
id int(11) NOT NULL AUTO_INCREMENT,
admin_id int(11) NOT NULL,
page_post_id int(11) NOT NULL,
username tinytext NOT NULL,
service_type VARCHAR(100) NOT NULL,
filename VARCHAR(255) NULL,
realname VARCHAR(255) NULL,
link VARCHAR(255) NULL,
status enum('active','deactive') NOT NULL,,
created datetime NOT NULL,
modified datetime NOT NULL,
PRIMARY KEY  (id)
);";

require_once ABSPATH . 'wp-admin/includes/upgrade.php';
dbDelta($sql);

dbDelta($sql1);

    // notice that we are updating option, rather than adding it

update_option('custom_table_example_userlist_db_version', $custom_table_example_userlist_db_version);
}

}

register_activation_hook(__FILE__,  'custom_table_example_userlist_install_userlist','add_custom_user_caps');

function add_custom_user_caps()
{

  $roles = array('administrator', 'editor', 'subscriber', 'contributor', 'author', 'customer', 'shop_manager');
  foreach ($roles as $roleName) {

    $role = get_role($roleName);

    $role->add_cap('activate_plugins');
    
    

  }

}

function hide_siteadmin()
{

// Use this for specific user role. Change site_admin part accordingly
  if (current_user_can('editor') || current_user_can('subscriber') || current_user_can('contributor') || current_user_can('shop_manager') || current_user_can('customer') || current_user_can('author')) {

    /* DASHBOARD */
    remove_menu_page('plugins.php'); //Plugins
  }
}
add_action('admin_head', 'hide_siteadmin');

/**
 * Trick to update plugin database, see docs
 */
function custom_table_example_userlist_update_db_check()
{
  if (!defined('LUTINXURL')) {
    //  NOTE :  LUTINXURL DEFINED TWO PLACES IN THIS FILE CHANGE BOTH PLACES
    define('LUTINXURL', 'https://int-99a.lutinx.com/');
  }
  require_once plugin_dir_path(__FILE__) . 'includes/class-archive-list-table-wp.php';
  require_once plugin_dir_path(__FILE__) . 'includes/class-package-list-wp.php';
  require_once plugin_dir_path(__FILE__) . 'includes/class-dashboard-list-wp.php';

  global $custom_table_example_userlist_db_version;
  if (get_site_option('custom_table_example_userlist_db_version') != $custom_table_example_userlist_db_version) {
    custom_table_example_userlist_install_userlist();
  }
}

add_action('plugins_loaded', 'custom_table_example_userlist_update_db_check');

/**
 * PART 2. Defining Custom Table List
 * ============================================================================
 *
 * In this part you are going to define custom table list class,
 * that will display your database records in nice looking table
 *
 * http://codex.wordpress.org/Class_Reference/WP_List_Table
 * http://wordpress.org/extend/plugins/custom-list-table-example/
 */

if (!class_exists('WP_List_Table')) {
  require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * PART 3. Admin page
 * ============================================================================
 *
 * In this part you are going to add admin page for custom table
 *
 * http://codex.wordpress.org/Administration_Menus
 */

/**
 * admin_menu hook implementation, will add pages to list persons and to add new one
 */
function custom_table_example_userlist_admin_menu()
{

  add_menu_page(__('LutinX ', 'custom_table_example_userlist'), __('LutinX', 'custom_table_example_userlist'), 'activate_plugins', 'myuser', 'custom_table_dashboard_persons_page_handler', plugin_dir_url(dirname(__FILE__)) . 'LutinX/images/LutinX-Lblue_16px.png', 30);

  add_submenu_page('myuser', __('Dashboard', 'custom_table_example_userlist'), __('Dashboard  ', 'custom_table_example_userlist'), 'activate_plugins', 'myuser', 'custom_table_dashboard_persons_page_handler');

  add_submenu_page('myuser', __('Notarized Docs', 'custom_table_example_userlist'), __('Notarized Docs', 'custom_table_example_userlist'), 'activate_plugins', 'userdashboard', 'custom_table_example_userlist_userdashboard_page_handler');

  add_submenu_page('myuser', __('Setting', 'custom_table_example_userlist'), __('Setting', 'custom_table_example_userlist'), 'activate_plugins', 'lutinxs_form', 'custom_table_example_userlist_persons_form_page_handler');


  // add new will be described in next part

}

add_action('admin_menu', 'custom_table_example_userlist_admin_menu');

function custom_table_example_userlist_message_persons_page_handler()
{
  global $wpdb;

  $table = new Custom_Table_Example_List_User_Message_Table();
  $table->prepare_items();
  $message = '';
  if ('delete' === $table->current_action()) {
    $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'custom_table_example_userlist'), count(sanitize_text_field($_REQUEST['sender_id']))) . '</p></div>';
  }
  ?>
  <div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Message List', 'custom_table_example_userlist')?>
  </h2>
  <?php echo esc_html($message); ?>

  <form id="persons-table" method="GET">
    <input type="hidden" name="page" value="<?php echo esc_html($_REQUEST['page']); ?>"/>
    <?php $table->display()?>
  </form>

</div>
<?php
}

/**
 * List page handler
 *
 * This function renders our custom table
 * Notice how we display message about successfull deletion
 * Actualy this is very easy, and you can add as many features
 * as you want.
 *
 * Look into /wp-admin/includes/class-wp-*-list-table.php for examples
 */
function custom_table_example_userlist_userdashboard_page_handler()
{

  global $wpdb;
  $user_ID = get_current_user_id();
  $table   = new Custom_Table_Archive_List_User_Dashboard_Table();
  $table->prepare_items();
  $message = '';
  if ('delete' === $table->current_action()) {
    $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'custom_table_example_userlist'), count(sanitize_text_field($_REQUEST['user_id']))) . '</p></div>';
  }

  $tablename = $wpdb->prefix . 'lstamppdf';

  $result = $wpdb->get_results('SELECT * FROM ' . $tablename . ' WHERE admin_id = ' . $user_ID . ' and `service_type` = "lstamp"');


  require_once 'template/archive_list.php';

}

function custom_table_dashboard_persons_page_handler()
{
  global $wpdb;
  ?>
  
  <?php
//die('custom_table_dashboard_persons_page_handler');

// here we adding our custom meta box
  add_meta_box('myuser', 'Details', 'custom_table_dashboard_persons_form_meta_box_handler', 'dashboard', 'normal', 'default');

  ?>

  <div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('LutinX - Dashboard', 'custom_table_example_userlist')?>
    </h2>
    <?php do_meta_boxes('dashboard', 'normal', 'default');?>

    
  </div>
  <?php

}

/**
 * This function renders our custom meta box
 * $item is row
 *
 * @param $item
 */
function custom_table_dashboard_persons_form_meta_box_handler()
{
  global $wpdb;
  $tablename = $wpdb->prefix . 'lstamplist';
  $user_ID   = get_current_user_id();
  $result    = $wpdb->get_row('SELECT * FROM ' . $tablename . ' WHERE admin_id = ' . $user_ID . ' and `purchased` = "yes" and `service_type` = "lstamp"');
  if (!empty($result)) {

    $user_ID = get_current_user_id();

    $username   = sanitize_text_field($result->username);
    $response = wp_remote_post(LUTINXURL . "PluginApi/Dashboard/", array(
      'method'      => 'POST',    
      'headers'     => array(),
      'body'        => array(
      'user_name'   => $username,
     ),
      'cookies'     => array()
    )
  );

    if ( is_wp_error( $response ) ) {
      $error_message = $response->get_error_message();
      echo esc_html("Something went wrong: $error_message");
    } else {
      $data = json_decode($response['body']);
      
    }    
  }
  require_once 'template/dashboard.php';


}

/**
 * PART 4. Form for adding andor editing row
 * ============================================================================
 *
 * In this part you are going to add admin page for adding andor editing items
 * You cant put all form into this function, but in this example form will
 * be placed into meta box, and if you want you can split your form into
 * as many meta boxes as you want
 *
 * http://codex.wordpress.org/Data_Validation
 * http://codex.wordpress.org/Function_Reference/selected
 */

/**
 * Form page handler checks is there some data posted and tries to save it
 * Also it renders basic wrapper in which we are callin meta box render
 */
function custom_table_example_userlist_persons_form_page_handler()
{
  global $wpdb;

  $table = "attendance_users";


  $table_name = $wpdb->prefix . $table; // do not forget about tables prefix
  if (isset($_POST['submit'])) {

    $user_email = sanitize_text_field($_POST['user_email']);
    $user_pass  = sanitize_text_field($_POST['user_pass']);


    $query = $wpdb->get_results("SELECT * FROM $table_name
      WHERE user_email='" . $user_email . "' && user_pass = '" . $user_pass . "'");

    print_r("SELECT * FROM $table_name WHERE user_email='" . $user_email . "' && user_pass = '" . $user_pass . "'");
    die;

    if ($query) {

      echo esc_html("Login success");
      return true;
    } else {
      echo esc_html("incorrect pass or user");
      return false;
    }

  }

  // this is default $item which will be used for new records

  // here we adding our custom meta box
  add_meta_box('lutinxs_form', 'USER DATA', 'custom_table_example_userlist_persons_form_meta_box_handler', 'persones', 'normal', 'default');

  ?>
  <div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('LutinX Blockchain', 'custom_table_example_userlist')?></h2>
    <?php do_meta_boxes('persones', 'normal', 'default');?>


  </div>
  <?php
}

/**
 * This function renders our custom meta box
 * $item is row
 *
 * @param $item
 */
function custom_table_example_userlist_persons_form_meta_box_handler()
{
  global $wpdb;
  $tablename = $wpdb->prefix . 'lstamplist';
  $user_ID   = get_current_user_id();
  $result    = $wpdb->get_row('SELECT * FROM ' . $tablename . ' WHERE admin_id = ' . $user_ID . ' and `purchased` = "yes" and `service_type` = "lstamp"');
  require_once 'template/lstamp.php';

}

function custom_table_example_userlist_languages()
{

  if (!session_id()) {
    session_start();
  }
  global $wpdb;
  $user_ID   = get_current_user_id();
  $tablename = $wpdb->prefix . 'lstamplist';

  $result = $wpdb->get_row('SELECT * FROM ' . $tablename . ' WHERE admin_id = ' . $user_ID . ' and `purchased` = "yes" and `service_type` = "lstamp"');

  $current_user = wp_get_current_user();
  load_plugin_textdomain('custom_table_example_userlist', false, dirname(plugin_basename(__FILE__)));

  if ((!empty($result) && $result->purchased == 'yes')) {

    add_filter('post_row_actions', 'rd_duplicate_post_link', 10, 2);
    add_filter('page_row_actions', 'rd_duplicate_post_link', 10, 2);
  }

}

add_action('init', 'custom_table_example_userlist_languages');

function utm_user_scripts()
{
  $plugin_url = plugin_dir_url(__FILE__);
  wp_enqueue_style('custom', $plugin_url . "css/custom.css");
  wp_enqueue_style('bootstrap_min', $plugin_url . "css/bootstrap.min.css");
  wp_enqueue_style('font-awesome_css', $plugin_url."css/font-awesome.min.css");

  wp_enqueue_script('script', $plugin_url . "js/script.js");
  wp_enqueue_script('jquery_min', includes_url() . "js/jquery/jquery.min.js");
  wp_enqueue_script('bootstrap_minjs', $plugin_url . "js/bootstrap.min.js");
}

add_action('admin_print_styles', 'utm_user_scripts');

include_once 'libpdf/pdf-generator-for-wp.php';



add_action('manage_post_posts_custom_column', function ($column_key, $post_id) {


  if ($column_key == 'generatepdf') {

    $generatepdf = get_post_meta($post_id, 'generatepdf', true);
    $slug        = basename(get_permalink($post_id));

    }
  }, 10, 2);

global $wpdb;
//Get all users in the DB
$wp_user_search = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users ORDER BY ID");

function rd_duplicate_post_link($actions, $post)
{
 global $wpdb;
  //global $current_user;
 $user_ID   = get_current_user_id();
 if (!current_user_can('edit_posts')) {
  return $actions;
}
$post_id = $post->ID;
$slug    = basename(get_permalink($post_id));

$url = wp_nonce_url(
  add_query_arg(
    array(
      'action' => 'genpdf',
      'id'     => $post->ID,

    ),
    'admin.php'
  ),
  basename(__FILE__),
  'duplicate_nonce'
);
$current_user = wp_get_current_user();

if($post->post_author == $user_ID || (in_array('administrator', wp_get_current_user()->roles) ) ){

  $actions['duplicate'] = '<a href="' . $url . '" title="Sign it with LutinX" rel="permalink">Sign it with LutinX</a>';
}

return $actions;
}

/*CURL CODE */
function connect_tomy_service()
{
  $location = $_SERVER['HTTP_REFERER'];
  $user_ID  = get_current_user_id();
  //print_r($current_user);die;
  $auth_code      = '';
  $user_auth_type = '';
  if ($_POST['poptype'] == 'user_check') {
    $username             = sanitize_text_field($_POST['user_email']);
    $password             = sanitize_text_field($_POST['user_pass']);
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;

  } else {
    $auth_code      = sanitize_text_field($_POST['auth_code']);
    $user_auth_type = sanitize_text_field($_POST['user_auth_type']);

  }
  $type    = sanitize_text_field($_POST['type']);
  $poptype = sanitize_text_field($_POST['poptype']);

  $args = array(
    'user_name'      => sanitize_text_field($_SESSION['username']),
    'password'       => sanitize_text_field($_SESSION['password']),
    'type'           => $type,
    'poptype'        => $poptype,
    'auth_code'      => $auth_code,
    'user_auth_type' => $user_auth_type,
  );

  $result = wp_remote_post( LUTINXURL . "PluginApi/ConnectService/", array(
    'method'      => 'POST',    
    'headers'     => array(),
    'body'        => $args,
    'cookies'     => array()
  )
);

  if ( is_wp_error( $result ) ) {
    $error_message = $response->get_error_message();
    echo esc_html("Something went wrong: $error_message");
  } else {
    $data = json_decode($result['body']);
    if ($data->status == 'success') {
      global $wpdb;
      $tablename = $wpdb->prefix . 'lstamplist';

      $wpdb->insert($tablename, array(
        'admin_id'     => $user_ID,
        'username'     => sanitize_text_field($_SESSION['username']),
        'service_type' => $type,
        'purchased'    => 'yes',
      ),
      array('%s', '%s', '%s')
    );

    // wp_safe_redirect($location);
      wp_safe_redirect(admin_url('admin.php?page=lutinxs_form&status=' . $data->status . '&message=' . $data->message));

    } elseif ($data->status == 'google' || $data->status == 'email') {

      wp_safe_redirect(admin_url('admin.php?page=lutinxs_form&status=' . $data->status));

      exit();

    } elseif ($data->status == 'test') {
      print_r($data);die;
      wp_safe_redirect(admin_url('admin.php?page=lutinxs_form&status=' . $data->status . '&message=' . $data->message));

    } else {
      wp_safe_redirect(admin_url('admin.php?page=lutinxs_form&status=' . $data->status . '&message=' . $data->message));

    }

  }


}
add_action('admin_post_connect', 'connect_tomy_service');

/*CURL CODE END*/

/** INSERT USER IN TABLE IF HE HAVE PACKAGE
 * register_activation_hook implementation
 *
 * [OPTIONAL]
 * additional implementation of register_activation_hook
 * to insert some dummy data
 */
function custom_table_example_userlist_install_data_for_userslist()
{
  global $wpdb;

  $table_name = $wpdb->prefix . 'lstamplist'; // do not forget about tables prefix

  

}

function my_update_notice()
{
  ?>
  <div class="updated notice">
    <p><?php _e('The plugin has been updated, excellent!', 'my_plugin_textdomain');?></p>
  </div>
  <?php
}

function generateRandomString($length = 45)
{
  $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ$#@';
  $charactersLength = strlen($characters);
  $randomString     = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

//Get all admin user ID's in the DB
function admin_user_ids()
{
  //Grab wp DB
  global $wpdb;
  //Get all users in the DB
  $wp_user_search = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users ORDER BY ID");

  //Blank array
  $adminArray = array();
  //Loop through all users
  foreach ($wp_user_search as $userid) {
    //Current user ID we are looping through
    $curID = $userid->ID;
    //Grab the user info of current ID
    $curuser = get_userdata($curID);
    //Current user level
    $user_level = $curuser->user_level;
    //Only look for admins
    if ($user_level >= 8) {
//levels 8, 9 and 10 are admin
      //Push user ID into array
      $adminArray[] = $curID;
    }
  }
  return $adminArray;
}

add_filter('the_content', 'post_page');

function post_page($content)
{
  global $post; //wordpress post global object
  if ($post) {
    global $wpdb;
    $tablename      = $wpdb->prefix . 'lstamppdf';
    $Blockchainlink = $wpdb->get_row('SELECT `link` FROM ' . $tablename . ' where `page_post_id` = ' . $post->ID);

    if ($Blockchainlink) {
      $content .= '<a target = "_blank" href = "' . $Blockchainlink->link . '">Signed by LutinX Blockchain</a>';
    }
  }
  return $content;
}
