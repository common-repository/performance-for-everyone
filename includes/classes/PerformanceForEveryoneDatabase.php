<?php

class PerformanceForEveryoneDatabase {
	function __construct() {
	}

	public function get_db_performance_results_html() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wppfep_db_measurements';
		$results    = $wpdb->get_results( $wpdb->prepare(
			"SELECT id, checked_at, db_size, num_posts, num_comments, num_users 
         FROM {$table_name} ORDER BY checked_at DESC LIMIT %d",
			20 ) );

		ob_start();
		?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Checked At</th>
                <th>DB Size (MB)</th>
                <th>Number of Posts</th>
                <th>Number of Comments</th>
                <th>Number of Users</th>
                <th>Improvement</th>
                <th>#</th>
            </tr>
            </thead>
            <tbody>
			<?php
			$prev_result = null;
			foreach ( $results as $index => $result ) {
				$improvement = '';
				if ( $index === 0 && isset( $results[1] ) ) {
					$next_result = $results[1];
					$improvement = sprintf(
						'<ul>
                        <li>DB Size: %.2f MB</li>
                        <li>Posts: %d</li>
                        <li>Comments: %d</li>
                        <li>Users: %d</li>
                    </ul>',
						esc_attr( $result->db_size ) - esc_attr( $next_result->db_size ),
						esc_attr( $result->num_posts ) - esc_attr( $next_result->num_posts ),
						esc_attr( $result->num_comments ) - esc_attr( $next_result->num_comments ),
						esc_attr( $result->num_users ) - esc_attr( $next_result->num_users )
					);
				} elseif ( $prev_result ) {
					$improvement = sprintf(
						'<ul>
                        <li>DB Size: %.2f MB</li>
                        <li>Posts: %d</li>
                        <li>Comments: %d</li>
                        <li>Users: %d</li>
                    </ul>',
						esc_attr( $prev_result->db_size ) - esc_attr( $result->db_size ),
						esc_attr( $prev_result->num_posts ) - esc_attr( $result->num_posts ),
						esc_attr( $prev_result->num_comments ) - esc_attr( $result->num_comments ),
						esc_attr( $prev_result->num_users ) - esc_attr( $result->num_users )
					);
				}

				printf(
					'<tr>
                    <td>%s</td>
                    <td>%.2f</td>
                    <td>%d</td>
                    <td>%d</td>
                    <td>%d</td>
                    <td>%s</td>
                    <td><button class="wppfe-db-delete-record" data-id="%d"><i class="fa fa-trash"></i></button></td>
                </tr>',
					esc_html( $result->checked_at ),
					esc_html( $result->db_size ),
					esc_html( $result->num_posts ),
					esc_html( $result->num_comments ),
					esc_html( $result->num_users ),
					$improvement,
					esc_attr( $result->id )
				);

				$prev_result = $result;
			}
			?>
            </tbody>
        </table>
		<?php
		return ob_get_clean();
	}


	function wppfep_cleanup_database() {
		global $wpdb;

		// Delete post revisions
		$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type = 'revision'" );

		// Delete spam comments
		$wpdb->query( "DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam'" );

		// Delete transient options
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_%' AND option_name NOT LIKE '_transient_timeout_%'" );

		$wpdb->query(
			"DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_%' AND option_value < UNIX_TIMESTAMP()"
		);
	}

    public function wppfe_delete_db_record($id){
	    global $wpdb;
	    if ( isset( $id ) ) {
		    $record_id = (int) $id;
		    $table_name = $wpdb->prefix . 'wppfep_db_measurements';

		    $deleted = $wpdb->delete( $table_name, array( 'id' => $record_id ), array( '%d' ) );

		    if ( $deleted ) {
			    wp_send_json_success();
		    } else {
			    wp_send_json_error();
		    }
	    } else {
		    wp_send_json_error();
	    }
    }

	function wppfep_optimize_database_tables() {
		global $wpdb;

		$tables           = $wpdb->get_results( 'SHOW TABLE STATUS', ARRAY_A );
		$optimized_tables = [];

		foreach ( $tables as $table ) {
			$table_name = $table['Name'];

			$result        = $wpdb->query( "OPTIMIZE TABLE $table_name" );
			$repair_result = $wpdb->query( "REPAIR TABLE $table_name" );
		}
	}

	function wppfep_cleanup_unused_data() {
		global $wpdb;

		$wpdb->query( "DELETE pm FROM {$wpdb->postmeta} pm LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id WHERE p.ID IS NULL" );
		$wpdb->query( "DELETE tr FROM {$wpdb->term_relationships} tr LEFT JOIN {$wpdb->posts} p ON p.ID = tr.object_id WHERE p.ID IS NULL" );
	}

	function wppfep_optimize_autoloaded_data() {
		global $wpdb;

		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE autoload = 'yes' AND option_name LIKE '_transient_%'" );
	}

	public function wppfep_check_db_performance() {
		global $wpdb;
		// Fetch data and insert in a single transaction
		$db_size      = $this->get_db_size();
		$num_posts    = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts}" );
		$num_comments = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->comments}" );
		$num_users    = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->users}" );

		$wpdb->query( 'START TRANSACTION' );
		$wpdb->insert(
			$wpdb->prefix . 'wppfep_db_measurements',
			array(
				'db_size'      => $db_size,
				'num_posts'    => $num_posts,
				'num_comments' => $num_comments,
				'num_users'    => $num_users
			)
		);
		$wpdb->query( 'COMMIT' );

		// Retrieve the last 10 records
		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wppfep_db_measurements ORDER BY checked_at DESC LIMIT 10" );

		// Initialize the HTML table
		$html = '<table class="table table-bordered">';
		$html .= '<thead><tr><th>Checked At</th><th>DB Size (MB)</th><th>Number of Posts</th><th>Number of Comments</th><th>Number of Users</th><th>Improvement</th></tr></thead>';
		$html .= '<tbody>';

		// Render the rows
		$prev_result = null;
		foreach ( $results as $result ) {
			$improvement = '';
			if ( $prev_result ) {
				$improvement = '<ul>';
				$improvement .= '<li>DB Size: ' . round( $prev_result->db_size - $result->db_size, 2 ) . ' MB</li>';
				$improvement .= '<li>Posts: ' . ( $prev_result->num_posts - $result->num_posts ) . '</li>';
				$improvement .= '<li>Comments: ' . ( $prev_result->num_comments - $result->num_comments ) . '</li>';
				$improvement .= '<li>Users: ' . ( $prev_result->num_users - $result->num_users ) . '</li>';
				$improvement .= '</ul>';
			}

			$html .= '<tr>';
			$html .= '<td>' . esc_html( $result->checked_at ) . '</td>';
			$html .= '<td>' . esc_html( $result->db_size ) . '</td>';
			$html .= '<td>' . esc_html( $result->num_posts ) . '</td>';
			$html .= '<td>' . esc_html( $result->num_comments ) . '</td>';
			$html .= '<td>' . esc_html( $result->num_users ) . '</td>';
			$html .= '<td>' . $improvement . '</td>';
			$html .= '</tr>';

			$prev_result = $result;
		}

		$html .= '</tbody></table>';

		return wp_send_json_success( $html );
	}


	private function get_db_size() {
		global $wpdb;
		$result = $wpdb->get_results( 'SHOW TABLE STATUS', ARRAY_A );
		$dbsize = 0;
		foreach ( $result as $row ) {
			$dbsize += $row['Data_length'] + $row['Index_length'];
		}

		return round( $dbsize / 1024 / 1024, 2 ); // Size in MB
	}

	function wppfep_backup_database() {
		global $wpdb, $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$creds = request_filesystem_credentials( site_url() );

		if ( ! WP_Filesystem( $creds ) ) {
			// If credentials could not be retrieved, return or handle the error
			return false;
		}

		// Define the backup directory path
		$backup_dir = WP_CONTENT_DIR . '/wpfe_backups';

		// Check if the directory exists, and create it if not
		if ( ! $wp_filesystem->is_dir( $backup_dir ) ) {
			$wp_filesystem->mkdir( $backup_dir, FS_CHMOD_DIR );
		}

		$backup_files = glob( $backup_dir . '/backup_*.sql' );

		$timestamp   = time();
		$date_time   = gmdate( 'Y-m-d_H-i-s', $timestamp );
		$hash        = hash( 'sha256', $timestamp );
		$backup_file = $backup_dir . '/backup_' . $date_time . '_' . $hash . '.sql';

		$tables = $wpdb->get_results( "SHOW TABLES", ARRAY_N );

		$sql_dump = '';
		foreach ( $tables as $table ) {
			$table_name   = $table[0];
			$create_table = $wpdb->get_row( "SHOW CREATE TABLE $table_name", ARRAY_N );
			$sql_dump     .= $create_table[1] . ";\n\n";

			$rows = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_N );
			foreach ( $rows as $row ) {
				$values   = array_map( array( $wpdb, 'escape' ), $row );
				$sql_dump .= "INSERT INTO $table_name VALUES ('" . implode( "','", $values ) . "');\n";
			}
			$sql_dump .= "\n\n";
		}

		if ( ! $wp_filesystem->put_contents( $backup_file, $sql_dump, FS_CHMOD_FILE ) ) {
			return wp_send_json_error( 'Failed to create backup file.' );
		} else {
			return wp_send_json_success( 'Backup created successfully.' );
		}
	}

	function wppfep_delete_backup() {
		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$creds = request_filesystem_credentials( site_url() );

		if ( ! WP_Filesystem( $creds ) ) {
			return wp_send_json_error( 'Failed to initialize file system.' );
		}

		if ( ! isset( $_POST['file'] ) ) {
			return wp_send_json_error( 'No file specified.' );
		}

		$file       = sanitize_text_field( $_POST['file'] );
		$backup_dir = WP_CONTENT_DIR . '/wpfe_backups';
		$file_path  = $backup_dir . '/' . basename( $file );

		if ( ! $wp_filesystem->exists( $file_path ) ) {
			return wp_send_json_error( 'Backup file does not exist.' );
		}

		if ( $wp_filesystem->delete( $file_path ) ) {
			return wp_send_json_success( 'Backup deleted successfully.' );
		} else {
			return wp_send_json_error( 'Failed to delete backup.' );
		}
	}


	function wppfep_list_backups() {
		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$creds = request_filesystem_credentials( site_url() );

		if ( ! WP_Filesystem( $creds ) ) {
			return wp_send_json_error( 'Failed to initialize file system.' );
		}

		// Define the backup directory path
		$backup_dir = WP_CONTENT_DIR . '/wpfe_backups';

		// Check if the directory exists
		if ( ! $wp_filesystem->is_dir( $backup_dir ) ) {
			return wp_send_json_error( 'Backup directory does not exist.' );
		}

		// Get all backup files
		$backup_files = glob( $backup_dir . '/backup_*.sql' );

		if ( ! $backup_files ) {
			return wp_send_json_error( 'No backups found.' );
		}

		$backups = array();
		foreach ( $backup_files as $backup_file ) {
			$backups[] = array(
				'file_name' => basename( $backup_file ),
				'download_link' => content_url( 'wpfe_backups/' . basename( $backup_file ) )
			);
		}

		return wp_send_json_success( array( 'backups' => $backups ) );
	}


}
