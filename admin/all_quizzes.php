<?php
//Admin page for listing all quizzes

    function allQuizzes()
    {
        if(isset($_GET['action']) && ($_GET['action'] == 'edit')){
            editQuiz($_GET['quiz']);
        }
        else{
            $myListTable = new Quiz_Table();
            echo '<div class="wrap"><h2>My List Table Test</h2>';
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
            var $example_data = array(
                array('id' => 1,'name' => 'Quarter Share', 'createdAt' => 'Nathan Lowell'),
                array('id' => 2,'name' => 'Quarter Share', 'createdAt' => 'Nathan Lowell'),
                array('id' => 3,'name' => 'Quarter Share', 'createdAt' => 'Nathan Lowell'),
                array('id' => 4,'name' => 'Quarter Share', 'createdAt' => 'Nathan Lowell'),
                array('id' => 5,'name' => 'Quarter Share', 'createdAt' => 'Nathan Lowell'),
                array('id' => 6,'name' => 'Quarter Share', 'createdAt' => 'Nathan Lowell'),
                array('id' => 7,'name' => 'Quarter Share', 'createdAt' => 'Nathan Lowell')
                );

    //Getting columns
    function get_columns(){
        $columns = array(
          'cb'        => '<input type="checkbox" />',
          'id' => 'ID',
          'name'    => 'Name',
          'createdAt'      => 'Created At'
        );
        return $columns;
      }
      //Preparing items for display
        function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $data = $this->get_quizzes(15);
        $this->_column_headers = array($columns, $hidden, $sortable);
        usort( $data, array( &$this, 'usort_reorder' ) );
        $per_page = 5;
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
          'id'  => array('id',false),
          'name' => array('name',false),
          'createdAt'   => array('createdAt',false)
        );
        return $sortable_columns;
      }
      //Sort functions
      function usort_reorder( $a, $b ) {
        // If no sort, default to title
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'name';
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
                  'delete'    => sprintf('<a href="?page=%s&action=%s&quiz=%s">Delete</a>',$_REQUEST['page'],'delete',$item['id']),
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
      

    //End of Class
}