<?php
//Include public pages
include('add_shortcode.php');
include('quiz_submit.php');

//End of include

//End Add Admin menu

//Add public scripts
add_action( 'wp_enqueue_scripts', 'quizEnqueuePublic' );


	function quizEnqueuePublic(){
		global $post;   
		if( isset($post->post_content) AND has_shortcode( $post->post_content, 'dabbel_quiz') ){
		wp_enqueue_script( 'quiz_custom_script', plugins_url( 'assets/js/quiz_custom.js', __FILE__ ), array('jquery'), null );
		wp_enqueue_script( 'lottie_script', plugins_url( 'assets/js/lottie.js', __FILE__ ), array('jquery'), null );
		wp_localize_script( 'quiz_custom_script', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    	wp_enqueue_style( 'quiz__custom_css',plugins_url( 'assets/css/quiz_custom.css', __FILE__ ), array(), null );

		}
	}

	

