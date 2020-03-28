<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/marinkosevo/
 * @since      1.0.0
 *
 * @package    Thatconverts_theguide
 * @subpackage Thatconverts_theguide/includes
 */
	 function activate() {
		global $wpdb;
		//wp_quizzes table
		$quiz_table = $wpdb->prefix . 'quizzes';
		$charset_collate = $wpdb->get_charset_collate();

		$wpdb->query( "CREATE TABLE IF NOT EXISTS $quiz_table (
			id int(11) NOT NULL AUTO_INCREMENT,
			name varchar(255) NOT NULL,
			createdAt datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			UNIQUE KEY id (id),
			PRIMARY KEY (id)
		) $charset_collate;" );

		//wp_quiz_questions table
		$questions_table = $wpdb->prefix . 'quiz_questions';

		$wpdb->query( "CREATE TABLE IF NOT EXISTS $questions_table (
			id int(11) NOT NULL AUTO_INCREMENT,
			quiz_id int(11) NOT NULL,
			name varchar(255) NOT NULL,
			question varchar(255) NOT NULL,
			createdAt datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			UNIQUE KEY id (id),
			PRIMARY KEY (id),
			FOREIGN KEY (quiz_id) REFERENCES $quiz_table(id)
		) $charset_collate;" );

		//wp_quiz_answers table
		$anwsers_table = $wpdb->prefix . 'quiz_answers';

		$wpdb->query( "CREATE TABLE IF NOT EXISTS $anwsers_table (
			id int(11) NOT NULL AUTO_INCREMENT,
			question_id int(11) NOT NULL,
			text varchar(255) NOT NULL,
			image varchar(255) NOT NULL,
			createdAt datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			UNIQUE KEY id (id),
			PRIMARY KEY (id),
			FOREIGN KEY (question_id) REFERENCES $questions_table(id)
		) $charset_collate;" );

		//wp_quiz_questions table
		$results_table = $wpdb->prefix . 'quiz_results';

		$wpdb->query( "CREATE TABLE IF NOT EXISTS $results_table (
			id int(11) NOT NULL AUTO_INCREMENT,
			quiz_id int(11) NOT NULL,
			title varchar(255) NOT NULL,
			image varchar(255) NOT NULL,
			description varchar(255) NOT NULL,
			link varchar(255) NOT NULL,
			createdAt datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			UNIQUE KEY id (id),
			PRIMARY KEY (id),
			FOREIGN KEY (quiz_id) REFERENCES $quiz_table(id)
		) $charset_collate;" );



	
	}
