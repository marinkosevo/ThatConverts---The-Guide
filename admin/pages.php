<?php
//Include Admin pages
include ('all_quizzes.php');
include ('add_new_quiz.php');
include ('edit_quiz.php');
include ('delete_quiz.php');
include ('settings.php');

//End of include

// Add Admin menu
if ( !function_exists( 'quizAddMenu' ) ) {
	function quizAddMenu() {
        add_menu_page('All Quizzes', //page title
        'dabbel - The Guide', //menu title
        'manage_options', //capabilities
        'dabbel', //menu slug
        'allQuizzes' //function
        );
        add_submenu_page('dabbel', //parent-menu slug
        'All Quizzes', //menu title
        'All Quizzes', //page title
        'manage_options', //capabilities
        'dabbel', //menu slug
        'allQuizzes' //function
        );
        
        add_submenu_page( 'dabbel',
        'Add new Quiz',
        'Add new Quiz',
        'manage_options',
        'dabbel_add_new_quiz',
        'addNewQuiz' 
            );

        add_submenu_page( 'dabbel',
        'Settings',
        'Settings',
        'manage_options',
        'dabbel_settings',
        'quiz_settings' 
            );
        }
	}
add_action('admin_menu', 'quizAddMenu');
//End Add Admin menu
//Add admin scripts
add_action( 'admin_enqueue_scripts', 'quizEnqueueAdmin' );
if ( !function_exists( 'quizEnqueueAdmin' ) ) {
function quizEnqueueAdmin(){
    wp_enqueue_script( 'quiz_admin_custom_script', plugins_url( 'assets/js/quiz_admin_custom.js', __FILE__ ), array('jquery'), null );
    wp_enqueue_script( 'jscolor_script', plugins_url( 'assets/js/jscolor.js', __FILE__ ), array('jquery'), null );
    wp_enqueue_style( 'quiz_admin_custom_css',plugins_url( 'assets/css/quiz_admin_custom.css', __FILE__ ), array(), null );
    wp_localize_script( 'quiz_admin_custom_script', 'ajax_admin_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    // Enqueue WordPress media scripts
    wp_enqueue_media();
    // Enqueue custom script that will interact with wp.media
    wp_enqueue_script( 'image_library', plugins_url( 'assets/js/image_library.js' , __FILE__ ), array('jquery'), '0.1' );
    }
}
