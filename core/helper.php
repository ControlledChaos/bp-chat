<?php



//Helper class
class BPChatHelper{
    private static $instance;
    
    private function __construct(){
        
        add_action( 'wp_login', array( $this, 'update_user_on_login' ), 20 );
        //logout user from chat when user logs out
        add_action( 'wp_logout', array( $this, 'cleanup' ) );

        add_action( 'wp_head', array( $this, 'check_current_user' ) );


        add_action( 'wp_footer', 'bp_chat_show_chat_bar' );
    }
    
    function get_instance(){
        
        if( ! isset( self::$instance ) )
                self::$instance = new self();
        return self::$instance;
    }
    
        //on login update table
    function check_current_user() {
        if( ! is_user_logged_in () )
            return;//do not cause any more load
        global $bp;
        bpchat_login_user( $bp->loggedin_user->id );//it will solve the login issue
        bpchat_update_last_active( $bp->loggedin_user->id );//update last active time for user
    }
    function update_user_on_login( $user_login ) {
         $user = new WP_User( $user_login );
         bpchat_login_user( $user->ID );
    }

    //add_action("clear_auth_cookie","bp_chat_cleanup");//may be we can use this hook too

    function cleanup(){
        
        bpchat_logout_user( get_current_user_id() );

    }



}//end of helper class

BPChatHelper::get_instance();

