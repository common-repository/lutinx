<?php 
/**
 * Custom_Table_Example_List_Table class that will display our custom table
 * records in nice table
 */
class Custom_Table_Dashboard_List_User_Table extends WP_List_Table
{
  /**
   * [REQUIRED] You must declare constructor and give some basic params
   */
  public function __construct()
  {
    global $status, $page;

    parent::__construct(array(
      'singular' => 'lutinx',
      'plural'   => 'lutinxs',
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

  /**
   * [OPTIONAL] this is example, how to render specific column
   *
   * method name must be like this: "column_[column_name]"
   *
   * @param $item - row (key, value array)
   * @return HTML
   */

  /**
   * [OPTIONAL] this is example, how to render column with actions,
   * when you hover row "Edit | Delete" links showed
   *
   * @param $item - row (key, value array)
   * @return HTML
   */
  public function column_email($item)
  {
    // links going to /admin.php?page=[your_plugin_page][&other_params]
    // notice how we used $_REQUEST['page'], so action will be done on curren page
    // also notice how we use $this->_args['singular'] so in this example it will
    // be something like &person=2
    $actions = array(
      'edit'   => sprintf('<a href="?page=lutinxs_form&user_id=%s">%s</a>', $item['user_id'], __('Edit', 'custom_table_example_userlist')),
      'delete' => sprintf('<a href="?page=%s&action=delete&user_id=%s">%s</a>', esc_html($_REQUEST['page']), $item['user_id'], __('Delete', 'custom_table_example_userlist')),

    );

    return sprintf('%s %s',
      $item['email'],
      $this->row_actions($actions)
    );
  }

  /* function column_description($item)
  {
  return sprintf(
  $item['description']
  );
  } */

  /**
   * [REQUIRED] this is how checkbox column renders
   *
   * @param $item - row (key, value array)
   * @return HTML
   */
  public function column_cb($item)
  {
    return sprintf(
      '<input type="checkbox" name="user_id[]" value="%s" />',
      $item['user_id']
    );
  }

  public function column_fname($item)
  {
    return sprintf(
      $item['fname']
    );
  }

  public function column_lname($item)
  {
    return sprintf(
      $item['lname']
    );
  }

  public function column_is_active($item)
  {
    if ($item['is_active'] == 1) {
      $status = "<font style='color:green'>Active</font>";
    } else {
      $status = "<font style='color:red'>Deactive</font>";
    }
    return sprintf(
      $status
    );
  }

  public function column_message($item)
  {
    // links going to /admin.php?page=[your_plugin_page][&other_params]
    // notice how we used $_REQUEST['page'], so action will be done on curren page
    // also notice how we use $this->_args['singular'] so in this example it will
    // be something like &person=2
    $actions = array(
      'send_message' => sprintf('<a href="?page=lutinxs_msg_form&user_id=%s">%s</a>', $item['user_id'], __('Send Message', 'custom_table_example_userlist')),

    );

    return sprintf('%s %s',
      $item['fname'],
      $this->row_actions($actions)
    );
  }

  /**
   * [REQUIRED] This method return columns to display in table
   * you can skip columns that you do not want to show
   * like content, or description
   *
   * @return array
   */
  public function get_columns()
  {
    $columns = array(
      'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text

      'user_id'   => __('ID', 'custom_table_example_userlist'),
      'email'     => __('Email', 'custom_table_example_userlist'),
      // 'description' => __('Description', 'custom_table_example_userlist'),
      //  'category' => __('Category', 'custom_table_example_userlist'),
      'fname'     => __('First Name', 'custom_table_example_userlist'),
      'lname'     => __('Last Name', 'custom_table_example_userlist'),

      'is_active' => __('Status', 'custom_table_example_userlist'),
      'message'   => __('Message', 'custom_table_example_userlist'),

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
      'user_id'   => array('user_id', true),
      'email'     => array('Email', true),
      'fname'     => array('First Name', true),
      'lname'     => array('Last Name', true),
      'is_active' => array('Status', true),

    );
    return $sortable_columns;
  }
  /**
   * [OPTIONAL] Return array of bult actions if has any
   *
   * @return array
   */
  public function get_bulk_actions()
  {
    $actions = array(
      'delete' => 'Delete',
    );
    return $actions;
  }

  /**
   * [OPTIONAL] This method processes bulk actions
   * it can be outside of class
   * it can not use wp_redirect coz there is output already
   * in this example we are processing delete action
   * message about successful deletion will be shown on page in next part
   */
  public function process_bulk_action()
  {
    global $wpdb;
    $table      = 'users';
    $table_name = $wpdb->prefix . $table; // do not forget about tables prefix

    if ('delete' === $this->current_action()) {
      $ids = isset($_REQUEST['user_id']) ? sanitize_text_field($_REQUEST['user_id']) : array();
      if (is_array($ids)) {
        $ids = implode(',', $ids);
      }

      if (!empty($ids)) {
        $wpdb->query("DELETE FROM $table_name WHERE user_id IN($ids)");
      }
    }
  }

  /**
   * [REQUIRED] This is the most important method
   *
   * It will get rows from database and prepare them to be showed in table
   */
  public function prepare_items()
  {
    global $wpdb;
    $table      = 'users';
    $table_name = $wpdb->prefix . $table; // do not forget about tables prefix

    $per_page = 10; // constant, how much records will be shown per page

    $columns  = $this->get_columns();
    $hidden   = array();
    $sortable = $this->get_sortable_columns();

    // here we configure table headers, defined in our methods
    $this->_column_headers = array($columns, $hidden, $sortable);

    // [OPTIONAL] process bulk action if any
    $this->process_bulk_action();

    // will be used in pagination settings
    $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

    // prepare query params, as usual current page, order by and order direction
    $paged   = isset($_REQUEST['paged']) ? ($per_page * max(0, intval($_REQUEST['paged']) - 1)) : 0;
    $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? sanitize_text_field($_REQUEST['orderby']) : 'user_id';
    $order   = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? sanitize_text_field($_REQUEST['order']) : 'asc';

    // [REQUIRED] define $items array
    // notice that last argument is ARRAY_A, so we will retrieve array
    $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

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