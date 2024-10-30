<?php
class Custom_Table_Archive_List_User_Dashboard_Table extends WP_List_Table
{
  /**
   * [REQUIRED] You must declare constructor and give some basic params
   */
  public function __construct()
  {
    global $status, $page;

    parent::__construct(array(
      'singular' => 'userdashboard',
      'plural'   => 'userdashboards',
    ));
  }

  /**
   * [REQUIRED] this is a default column renderer
   *
   * @param $item - row (key, value array)
   * @param $column_name - string (key)
   * @return HTML
   */
  public function column_default($item, $column_name)
  {

    return $item[$column_name];
  }

  // public function column_action($item)
  // {
  //   // links going to /admin.php?page=[your_plugin_page][&other_params]
  //   // notice how we used $_REQUEST['page'], so action will be done on curren page
  //   // also notice how we use $this->_args['singular'] so in this example it will
  //   // be something like &person=2
  //   $actions = array(
  //     'view' => sprintf('<a target = "_blank" class = "btn btn-success" href="'.$item['link'].'">%s</a>',  __('View', 'custom_table_example_userlist')),
     

  //   );

  //   return sprintf('%s %s',
  //     $item['action'],
  //     $this->row_actions($actions)
  //   );
  // }




  public function column_link($item)
  {
    // links going to /admin.php?page=[your_plugin_page][&other_params]
    // notice how we used $_REQUEST['page'], so action will be done on curren page
    // also notice how we use $this->_args['singular'] so in this example it will
    // be something like &person=2
    $actions = array(
      'link' => sprintf('<a target = "_blank" class = "btn btn-success btn-sm" href="'.$item['link'].'">%s</a>',  __('View', 'custom_table_example_userlist')),
     'link2' => sprintf('<a onclick="copyshortlink(this)" class = "btn btn-success btn-sm" href="javascript:void(0)">%s</a>',  __('Link', 'custom_table_example_userlist')),
     

    );

    return sprintf('%s %s',
      '<span class = "clslink">'.$item['link'].'</span>',
      $this->row_actions($actions)
    );
  }

  public function column_service_type($item)
  {
    if($item['service_type'] == 'lstamp'){
      return sprintf(
      "Blockchain Intellectual Property"
    );
    }
    
  }

  public function column_cb($item)
  {
    return sprintf(
      '<input type="checkbox" name="id[]" value="%s" />',
      $item['id']
    );
  }






  public function get_columns()
  {
    $columns = array(
      'cb'           => '<input type="checkbox" />',
      'realname'     => __('Name', 'custom_table_example_userlist'),
      'link'         => __('Link', 'custom_table_example_userlist'),
      'service_type' => __('LutinX Service', 'custom_table_example_userlist'),
      //'action'       => __('Action', 'custom_table_example_userlist'),

    );

    return $columns;
  }

  /**
   * [OPTIONAL] This method return columns that may be used to sort table
   * all strings in array - is column names
   * notice that true on name column means that its default sort
   *
   * @return array
   */
  public function get_sortable_columns()
  {
    $sortable_columns = array(
      'realname'     => array('name', true),
      'link'         => array('link', true),
      'service_type' => array('service', true),
      //'action'      => array('action', true),
      //'message'    => array('Message', true),

    );
    return $sortable_columns;
  }
  /**
   * [OPTIONAL] Return array of bult actions if has any
   *
   * @return array
   */

  /**
   * [OPTIONAL] This method processes bulk actions
   * it can be outside of class
   * it can not use wp_redirect coz there is output already
   * in this example we are processing delete action
   * message about successful deletion will be shown on page in next part
   */

  /**
   * [REQUIRED] This is the most important method
   *
   * It will get rows from database and prepare them to be showed in table
   */
  public function prepare_items()
  {
    global $wpdb;
    $user_ID = get_current_user_id();
    $table      = 'lstamppdf';
    $table_name = $wpdb->prefix . $table; // do not forget about tables prefix

    $per_page = 10; // constant, how much records will be shown per page
    //$user_id  = $_GET['user_id'];

    $columns  = $this->get_columns();
    $hidden   = array();
    $sortable = $this->get_sortable_columns();

    // here we configure table headers, defined in our methods
    $this->_column_headers = array($columns, $hidden, $sortable);

    // [OPTIONAL] process bulk action if any
    $this->process_bulk_action();

    // will be used in pagination settings
    $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name  WHERE `admin_id` = $user_ID and `service_type` = 'lstamp'");

    // prepare query params, as usual current page, order by and order direction
    $paged = isset($_REQUEST['paged']) ? ($per_page * max(0, intval($_REQUEST['paged']) - 1)) : 0;

    // [REQUIRED] define $items array
    // notice that last argument is ARRAY_A, so we will retrieve array
    $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name where `admin_id` = $user_ID and `service_type` = 'lstamp' ORDER BY id DESC  LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

    // print_r($xx);
    // die();

    // [REQUIRED] configure pagination
    $this->set_pagination_args(array(
      'total_items' => $total_items, // total items defined above
      'per_page'    => $per_page, // per page constant defined at top of method
      'total_pages' => ceil($total_items / $per_page), // calculate pages count
    ));
  }

  public function prepare_dashboard_items()
  {
    global $wpdb;
    $table      = 'lstamppdf';
    $table_name = $wpdb->prefix . $table; // do not forget about tables prefix

    $per_page = 10; // constant, how much records will be shown per page
    $user_id  = sanitize_text_field($_GET['user_id']);

    $columns  = $this->get_columns();
    $hidden   = array();
    $sortable = $this->get_sortable_columns();

    // here we configure table headers, defined in our methods
    $this->_column_headers = array($columns, $hidden, $sortable);

    // [OPTIONAL] process bulk action if any
    $this->process_bulk_action();

    // will be used in pagination settings
    $total_items = $wpdb->get_var("SELECT COUNT(id ) FROM $table_name ");

    // prepare query params, as usual current page, order by and order direction
    $paged = isset($_REQUEST['paged']) ? ($per_page * max(0, intval($_REQUEST['paged']) - 1)) : 0;

    // [REQUIRED] define $items array
    // notice that last argument is ARRAY_A, so we will retrieve array
    $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name  ORDER BY id DESC  LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

    // print_r($xx);
    // die();

    // [REQUIRED] configure pagination
    $this->set_pagination_args(array(
      'total_items' => $total_items, // total items defined above
      'per_page'    => $per_page, // per page constant defined at top of method
      'total_pages' => ceil($total_items / $per_page), // calculate pages count
    ));
  }




}


