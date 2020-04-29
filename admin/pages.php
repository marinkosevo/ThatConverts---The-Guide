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
        'ThatConverts - The Guide', //menu title
        'manage_options', //capabilities
        'thatconverts', //menu slug
        'allQuizzes' //function
        );
        add_submenu_page('thatconverts', //parent-menu slug
        'All Quizzes', //menu title
        'All Quizzes', //page title
        'manage_options', //capabilities
        'thatconverts', //menu slug
        'allQuizzes' //function
        );
        
        add_submenu_page( 'thatconverts',
        'Add new Quiz',
        'Add new Quiz',
        'manage_options',
        'thatconverts_add_new_quiz',
        'addNewQuiz' 
            );

        add_submenu_page( 'thatconverts',
        'Settings',
        'Settings',
        'manage_options',
        'thatconverts_settings',
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
    }
}