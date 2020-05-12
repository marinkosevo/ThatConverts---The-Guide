<?php

/**
 * Fired during plugin deactivation
 *
 */
	function deactivate() {
 		global $wpdb;
		$quiz_table = $wpdb->prefix . 'quizzes';
		$questions_table = $wpdb->prefix . 'quiz_questions';
		$results_table = $wpdb->prefix . 'quiz_results';
		$anwsers_table = $wpdb->prefix . 'quiz_answers';
		$data_table = $wpdb->prefix . 'quiz_data';
		$settings_table = $wpdb->prefix . 'quiz_settings';

		$wpdb->query( "DROP TABLE IF EXISTS $quiz_table;" );
		$wpdb->query( "DROP TABLE IF EXISTS $questions_table;" );
		$wpdb->query( "DROP TABLE IF EXISTS $results_table;" );
		$wpdb->query( "DROP TABLE IF EXISTS $anwsers_table;" );
		$wpdb->query( "DROP TABLE IF EXISTS $data_table;" );
		$wpdb->query( "DROP TABLE IF EXISTS $settings_table;" );
 
 
	}
