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
			description varchar(255) DEFAULT '',
			results_title varchar(255) DEFAULT '',
			results_description varchar(255) DEFAULT '',
			collect_results tinyint(1) DEFAULT '0',
			collect_email tinyint(1) DEFAULT '0',
			email_description varchar(255) DEFAULT '',
			createdAt datetime DEFAULT NULL,
			UNIQUE KEY id (id),
			PRIMARY KEY (id)
		) $charset_collate;" );

		//wp_quiz_questions table
		$questions_table = $wpdb->prefix . 'quiz_questions';

		$wpdb->query( "CREATE TABLE IF NOT EXISTS $questions_table (
			id int(11) NOT NULL AUTO_INCREMENT,
			quiz_id int(11) NOT NULL,
			question_nr int(11) NOT NULL,
			name varchar(255) NOT NULL,
			question varchar(255) NOT NULL,
			question_type varchar(255) NOT NULL,
			multiple_answers tinyint(1) NOT NULL,
			createdAt datetime DEFAULT NULL,
			UNIQUE KEY id (id),
			PRIMARY KEY (id),
			FOREIGN KEY (quiz_id) REFERENCES $quiz_table(id)
			ON DELETE CASCADE
		) $charset_collate;" );

		//wp_quiz_answers table
		$anwsers_table = $wpdb->prefix . 'quiz_answers';

		$wpdb->query( "CREATE TABLE IF NOT EXISTS $anwsers_table (
			id int(11) NOT NULL AUTO_INCREMENT,
			question_id int(11) NOT NULL,
			answer_nr int(11) NOT NULL,
			answer_icon varchar(255) DEFAULT '',
			answer_dq_nr int (11) DEFAULT NULL,
			result_nr int(11) NOT NULL,
			text varchar(255) NOT NULL,
			number1 int(11) NOT NULL,
			number2 int(11) NOT NULL,
			image varchar(255) NOT NULL,
			createdAt datetime DEFAULT NULL,
			UNIQUE KEY id (id),
			PRIMARY KEY (id),
			FOREIGN KEY (question_id) REFERENCES $questions_table(id)
			ON DELETE CASCADE
		) $charset_collate;" );

		//wp_quiz_results table
		$results_table = $wpdb->prefix . 'quiz_results';

		$wpdb->query( "CREATE TABLE IF NOT EXISTS $results_table (
			id int(11) NOT NULL AUTO_INCREMENT,
			quiz_id int(11) NOT NULL,
			result_nr int(11) NOT NULL,
			title varchar(255) NOT NULL,
			image varchar(255) NOT NULL,
			description varchar(255) NOT NULL,
			link varchar(255) NOT NULL,
			createdAt datetime DEFAULT NULL,
			UNIQUE KEY id (id),
			PRIMARY KEY (id),
			FOREIGN KEY (quiz_id) REFERENCES $quiz_table(id)
			ON DELETE CASCADE
		) $charset_collate;" );

		//wp_quiz_data table
		$data_table = $wpdb->prefix . 'quiz_data';

		$wpdb->query( "CREATE TABLE IF NOT EXISTS $data_table (
			id int(11) NOT NULL AUTO_INCREMENT,
			quiz_id int(11) NOT NULL,
			quiz_data text NOT NULL,
			email varchar(255) NOT NULL,
			createdAt datetime DEFAULT NULL,
			UNIQUE KEY id (id),
			PRIMARY KEY (id),
			FOREIGN KEY (quiz_id) REFERENCES $quiz_table(id)
			ON DELETE CASCADE
		) $charset_collate;" );



	
	}
