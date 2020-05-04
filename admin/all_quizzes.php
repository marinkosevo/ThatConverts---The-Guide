<?php
//Admin page for listing all quizzes

    function allQuizzes()
    {
        if(isset($_GET['action']) && ($_GET['action'] == 'edit')){
            editQuiz($_GET['quiz']);
        }
        else if(isset($_GET['action']) && ($_GET['action'] == 'results')){

            $myListTable = new QuizResults_Table();
            echo '<div class="wrap"><h2>'. __('All Quizzes Table', 'thatconverts_theguide').'</h2>';
            echo '<form method="GET">' ;
            $myListTable->prepare_items(); 
            ?>

            <form method="post">
            <input type="hidden" name="page" value="my_list_test" />
            <?php $myListTable->search_box('search', 'search_id'); ?>
            </form>
            <button type="button" class="button action" onclick="export_csv_all(<?php echo $_GET['quiz']; ?>)">Export all as CSV</button>
            <?php
            $myListTable->display(); 
            echo '</form></div>';     
            
        }
        else{
            if(isset($_GET['action']) && ($_GET['action'] == 'delete')){
                deleteQuiz($_GET['quiz']);
            }
            $myListTable = new Quiz_Table();
            echo '<div class="wrap"><h2>'. __('All Quizzes Table', 'thatconverts_theguide').'</h2>';
            echo '<form method="GET">' ;
            $myListTable->prepare_items(); 
            ?>
            <form method="post">
            <input type="hidden" name="page" value="my_list_test" />
            <?php $myListTable->search_box('search', 'search_id'); ?>
            </form>
            <?php
            $myListTable->display(); 
            echo '</form></div>';     
        }
    
    }

    add_action("wp_ajax_export_csv", "export_csv");

    function export_csv(){
        global $wpdb;
        $results_table = $wpdb->prefix.'quiz_data';
        if(isset($_POST['id'])){
            $result_id = $_POST['id'];
            $results_sql = "SELECT * FROM $results_table WHERE id = $result_id";
            $results_data = $wpdb->get_results( $results_sql, 'ARRAY_A' );
            $email = $results_data[0]['email'];
            $csv_fields=array();

            $results_data = unserialize($results_data[0]['quiz_data']);
            foreach ($results_data as $key=>$fields) {
                $csv_fields[$key] = array();
                foreach($fields as $field){
                    array_push($csv_fields[$key], $field);
                }
            }
            $data['email'] = $email;
            $data['data'] = array_values($csv_fields);
            wp_send_json_success($data);
        }
        else{
            $result_id = $_POST['quiz_id'];
            $results_sql = "SELECT * FROM $results_table WHERE quiz_id = $result_id";
            $results_data = $wpdb->get_results( $results_sql, 'ARRAY_A' );
            $csv_fields=array();

            foreach ($results_data as $key=>$fields) {
                $fields['quiz_data'] = unserialize($fields['quiz_data']);

                foreach($fields as $key2=>$field){
                    $csv_fields[$key][$key2] = array();
                    array_push($csv_fields[$key][$key2], $field);
                }
            }
            $data = array_values($csv_fields);
            wp_send_json_success($data);

        }
    }
//Check if WP_List_Table exists
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

//Create Quiz List table class
class Quiz_Table extends WP_List_Table {

    //No items writing
    function no_items() {
        _e( 'No quizzes found.' );
      }
      
    //Getting data from database
    function get_quizzes() {

            global $wpdb;
        
            $sql = "SELECT * FROM {$wpdb->prefix}quizzes";
        
            if ( ! empty( $_REQUEST['orderby'] ) ) {
                $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
                $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
            }
        
            $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        
            return $result;
        }

    //Getting columns
    function get_columns(){
        $columns = array(
          'cb'        => '<input type="checkbox" />',
          'id' => 'ID',
          'name'    => __('Name', 'thatconverts_theguide'),
          'createdAt'      => __('Created At', 'thatconverts_theguide'),
          'shortcode' => __('Shortcode', 'thatconverts_theguide')
        );
        return $columns;
      }
      //Preparing items for display
        function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $data = $this->get_quizzes();
        $this->_column_headers = array($columns, $hidden, $sortable);
        usort( $data, array( &$this, 'usort_reorder' ) );
        $per_page = 10;
        $current_page = $this->get_pagenum();
        $total_items = count($data);
      
        //Pagination
        // only ncessary because we have sample data
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
      
        $this->set_pagination_args( array(
          'total_items' => $total_items,                  //WE have to calculate the total number of items
          'per_page'    => $per_page                     //WE have to determine how many items to show on a page
            ) );
        $this->items = $data;
        }
        //End Pagination 

        //What to display in column
        function column_default( $item, $column_name ) {
        switch( $column_name ) { 
            case 'id':
            case 'name':
            case 'createdAt':
            return $item[ $column_name ];
            default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }
    //Get sortable columns
    function get_sortable_columns() {
        $sortable_columns = array(
          'id'  => array('id',true),
          'name' => array('name',false),
          'createdAt'   => array('createdAt',false)
        );
        return $sortable_columns;
      }
      //Sort functions
      function usort_reorder( $a, $b ) {
        // If no sort, default to title
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'id';
        // If no order, default to asc
        $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
        // Determine sort order
        $result = strcmp( $a[$orderby], $b[$orderby] );
        // Send final sort direction to usort
        return ( $order === 'asc' ) ? $result : -$result;
      }
      
      //Actions (edit and delete)
      function column_name($item) {
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&quiz=%s">Edit</a>',$_REQUEST['page'],'edit',$item['id']),
            'results'      => sprintf('<a href="?page=%s&action=%s&quiz=%s">Results</a>',$_REQUEST['page'],'results',$item['id']),
            'delete'    => sprintf('<a onclick="return confirm(`Are you sure you want to delete the quiz: %s?\nIt cannot be undone!`)" href="?page=%s&action=%s&quiz=%s">Delete</a>',$item['name'],$_REQUEST['page'],'delete',$item['id']),
              );
      
        return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions) );
      }
      function get_bulk_actions() {
        $actions = array(
          'delete'    => 'Delete'
        );
        return $actions;
      }
      function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="quiz[]" value="%s" />', $item['id']
            );         
        }    

      function column_shortcode($item) {
        return sprintf(
            '[thatconverts_quiz id="%s"]', $item['id']
            );         
        }  
        function delete($item) {
            echo 'test';           
         }      
                    

    //End of Class
}

//Create Quiz List table class
class QuizResults_Table extends WP_List_Table {

    //No items writing
    function no_items() {
        _e( 'No data found.' );
      }
      
    //Getting data from database
    function get_results($id) {

            global $wpdb;
        
            $sql = "SELECT * FROM {$wpdb->prefix}quiz_data WHERE quiz_id = $id";
        
            if ( ! empty( $_REQUEST['orderby'] ) ) {
                $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
                $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
            }
        
            $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        
            return $result;
        }

    //Getting columns
    function get_columns(){
        $columns = array(
          'cb'        => '<input type="checkbox" />',
          'id' => 'ID',
          'email'    => __('Email', 'thatconverts_theguide'),
          'createdAt'      => __('Created At', 'thatconverts_theguide'),
          'export' => __('Export', 'thatconverts_theguide')
        );
        return $columns;
      }
      //Preparing items for display
        function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $data = $this->get_results($_GET['quiz']);
        $this->_column_headers = array($columns, $hidden, $sortable);
        usort( $data, array( &$this, 'usort_reorder' ) );
        $per_page = 10;
        $current_page = $this->get_pagenum();
        $total_items = count($data);
      
        //Pagination
        // only ncessary because we have sample data
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
      
        $this->set_pagination_args( array(
          'total_items' => $total_items,                  //WE have to calculate the total number of items
          'per_page'    => $per_page                     //WE have to determine how many items to show on a page
            ) );
        $this->items = $data;
        }
        //End Pagination 

        //What to display in column
        function column_default( $item, $column_name ) {
        switch( $column_name ) { 
            case 'id':
            case 'email':
            case 'createdAt':
            return $item[ $column_name ];
            default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }
    //Get sortable columns
    function get_sortable_columns() {
        $sortable_columns = array(
          'id'  => array('id',true),
          'name' => array('name',false),
          'createdAt'   => array('createdAt',false)
        );
        return $sortable_columns;
      }
      //Sort functions
      function usort_reorder( $a, $b ) {
        // If no sort, default to title
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'id';
        // If no order, default to asc
        $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
        // Determine sort order
        $result = strcmp( $a[$orderby], $b[$orderby] );
        // Send final sort direction to usort
        return ( $order === 'asc' ) ? $result : -$result;
      }
      
      //Actions (edit and delete)
      function column_name($item) {
        $actions = array(
                  'edit'      => sprintf('<a href="?page=%s&action=%s&quiz=%s">Edit</a>',$_REQUEST['page'],'edit',$item['id']),
                  'delete'    => sprintf('<a onclick="return confirm(`Are you sure you want to delete the quiz: %s?\nIt cannot be undone!`)" href="?page=%s&action=%s&quiz=%s">Delete</a>',$item['name'],$_REQUEST['page'],'delete',$item['id']),
              );
      
        return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions) );
      }
      function get_bulk_actions() {
        $actions = array(
          'delete'    => 'Delete'
        );
        return $actions;
      }
      function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="quiz[]" value="%s" />', $item['id']
            );         
        }    

      function column_export($item) {
        return  sprintf('<button onclick="export_csv(%s)">Export as CSV</button>',$item['id']);        
        }    
                    

    //End of Class
}