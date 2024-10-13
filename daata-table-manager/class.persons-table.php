<?php
if( !class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH. "wp-admin/includes/class-wp-list-table.php");
}


class Persons_Table extends WP_List_Table {
    
    // set data into tables
    function set_data($data) {
        $this->items = $data;
    }


    // columns
    function get_columns()
    {
        return [
            'cb'    => '<input type="checkbox" />',
            'name'  => __( 'Name', 'datatable' ),
            'email'  => __( 'Email', 'datatable' ),
            'age'  => __( 'Age', 'datatable' ),
        ];
    }


    // edit any specific column
    function column_cb($item)
    {
        return  "<input type='checkbox' value=' {$item['id']}' />" ; 
    }

    function column_email($item)
    {
        return "<strong> {$item['email']} </strong> ";
    }

    // table columns name
    function prepare_items()
    {
        $this->_column_headers = array($this->get_columns() ); 
    }

    // columns value
    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

}
