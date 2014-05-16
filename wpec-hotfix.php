<?php

/*
Plugin Name: WPeC Hotfix
Plugin URI: http://www.pyebrook.com
Description: Some support functions when you encounter issues with WP e-Commerce - Use at your own risk
Version: 1.0
Author: Jeffrey
Author URI: http://www.pyebrook.com
License: GPL2
*/

if ( is_admin() ) {


	class WPEC_Hotfix {

		function __construct() {
			add_action( 'admin_menu', array( &$this, 'admin_init' ), 11 );
		}

		function admin_init() {

			// add the admin options page
			$parent_slug = add_menu_page(
								'WPeC Hotfix for WP e-Commerce',
								'WPeC Hotfix',
								'manage_options',
								__CLASS__,
								array( &$this, 'fixes' ),
								plugin_dir_url( __FILE__ ) . 'pye-brook-logo-16-16.png'
			);
		}

		function fixes() {
			?>
			<div class="wrap">
				<h2>WPeC Hotfix</h2>
				<blockquote>
					Use this plugin at your own risk, no warranty. You might want to consult the support forums before
					using this plugin.<br>

					AND BACKUP YOUR FILES AND DATABASE BEFORE DOING ANYTHING!!!

				</blockquote>
				<?php
				$this->do_action();
				?>
				<hr>
				<table>

					<tr>
						<td>
							<input type="submit"
							       name="clear_wp_cache"
							       id="clear_wp_cache"
							       class="button-primary"
							       value="Clear WordPress Cache">
						</td>
						<td>
							Clears the internal WordPress cache
						</td>
					</tr>

					<tr>
						<td>
							<input type="submit"
							       name="empty_checkout_options"
							       id="empty_checkout_options"
							       class="button-primary"
							       value="Delete all WPeC checkout options">
						</td>
						<td>
							Deletes all WPeC checkout options.  Makes, them gone, vanished, kaput!
							You will not have any checkout options after you click this button.
						</td>
					</tr>

					<tr>
						<td>
							<input type="submit"
							       name="add_default_checkout_options"
							       id="add_default_checkout_options"
							       class="button-primary"
							       value="Clear WordPress Cache">
						</td>
						<td>
							Inserts the default checkout options.  If there are conflicting options already present
							you will see database errors.
						</td>
					</tr>



				</table>
			</div>
		<?php
		}

		function do_action() {
			if ( empty( $_POST ) ) {
				return;
			} elseif ( isset( $_POST['clear_wp_cache'] ) ) {
				$this->clear_wp_cache();
			} elseif ( isset( $_POST['empty_checkout_options'] ) ) {
				$this->empty_checkout_options();
			} elseif ( isset( $_POST['add_default_checkout_options'] ) ) {
				$this->add_default_checkout_options();
			}
		}

		function clear_wp_cache() {
			$result = wp_cache_flush();
			?>
			<blockquote>
			The WordPress cache was cleared.  The function <b>wp_cache_flush</b> returned <?php var_export( $result );?>
			</blockquote>
			<hr>
			<?php
		}


		function empty_checkout_options() {
			global $wpdb;
			$sql = 'TRUNCATE TABLE ' . WPSC_TABLE_CHECKOUT_FORMS;
			$result = $wpdb->query( $sql );
			?>
			<blockquote>
				The database was asked to empty the checkout options table  This is the SQL that was used:<br>
				<i><?php echo $sql;?></i><br>
		         The function <b>$wpdb->print_error()</b> returns the following:<br>
				<?php $wpdb->print_error();?><br>
			</blockquote>
			<hr>
			<?php
		}

		function add_default_checkout_options() {
			global $wpdb;
			$result = wpsc_add_checkout_fields();
			$sql = 'SELECT * FROM '. WPSC_TABLE_CHECKOUT_FORMS;
			$rows = $wpdb->get_results( $sql, ARRAY_A );
			$row_text = var_export( $rows, true );
			?>
			<blockquote>
				The database was asked to insert the default WPeC checkout fields.  After the request there were submitted
				this is what the database table <?php echo WPSC_TABLE_CHECKOUT_FORMS;?> table contained:<br>
				<?php echo $row_text;?>
			</blockquote>
			<hr>
			<?php
		}
	}

	$hotfix = new WPEC_Hotfix();
}

