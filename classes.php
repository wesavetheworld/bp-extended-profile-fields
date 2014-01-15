<?php


class BP_Extended_Profile_Fields_Manager{
    
    /**
     *
     * @var BP_Extended_Profile_Fields_Manager 
     */
    private static $instance;
    /**
     * Path to this plugin directory
     * @var BP_Xprofile_Custom_Field[]
     */
    private $fields = array();
        
    private function __construct() {
        
       //add all registered types to the allowed type 
       add_filter( 'xprofile_field_types', array( $this, 'add_types' ) );
       
        //render for the BuddyPress Add Field/Edit field 
        add_action( 'xprofile_admin_field', array( $this, 'admin_render' ) );
        
        //
        add_action( 'xprofile_field_additional_options', array( $this, 'admin_render_select' ) );
        add_action( 'xprofile_field_additional_options', array( $this, 'admin_render_form' ) );
        
        
    }
    
    /**
     * 
     * @return BP_Extended_Profile_Fields_Manager
     */
    public static function get_instance(){
        
        if( !isset( self::$instance ) )
            self::$instance = new self();
        
        return self::$instance;
    }
    
    
    
    function register_field( BP_Xprofile_Custom_Field $field ){
        
        $this->fields[$field->get_type()] = $field;
        return $this;
    }
    
    /**
     * Add all valid types to allowed type
     * @param type $types
     * @return type
     */
    public function add_types( $types ){
        
        foreach( $this->fields as $field )
            array_push ( $types, $field->get_type() );
        
        return $types;
    }
    /**
     * 
     * @param BP_Xprofile_Field $field
     */
    public function admin_render( $field ) {

        if( isset( $this->fields[$field->type] ) )
            $this->fields[$field->type]->admin_render( $field );
            
        
    }
    
    /**
     * Used to add the options in the select box on add/edit field page in xprofile admin
     * @param type $current_field
     */
    public function admin_render_select( $current_field ){
        
        
        echo "<select name='bp-extended-select' id='bp-extended-select' style='display:none;'>";
            foreach( $this->fields as $field )    
               echo "<option value='". $field->get_type ()."'". selected( $current_field->type, $field->get_type(), false ) .">" . $field->get_label () . "</option>";     
        
            
         echo "</select>"; 
        
    }
    public function admin_render_form( $current_field ){

       foreach( $this->fields as $field )    
            $field->admin_render_form( $current_field );        
    }
}

/**
 * 
 * @return BP_Extended_Profile_Fields_Manager
 */

function bp_custom_fields_manager(){
    
    return BP_Extended_Profile_Fields_Manager::get_instance();
}

//initialize
bp_custom_fields_manager();