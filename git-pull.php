<?php
/**
 * Plugin Name:  git pull
 * Description:  An instant git controller for small theme development environments.
 * Version:      0.1
 * Author:       Mayo Moriyama
 * Author URI:   https://github.com/mayukojpn
 * License:      GPLv2 or later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package git-pull
 */

class Git_Pull {

	public $admin_notices = [];
  public function __construct() {
    add_action( 'admin_menu', array( $this, 'admin_menu' ) );
  }

  public function admin_menu() {
  	$theme_page = add_theme_page(
  		'git pull',
			'git pull',
  		'edit_theme_options',
  		'git_pull',
  		array( $this, 'exec_command' )
  	);
  }

  public function exec_command() {
		$directory = escapeshellcmd(get_stylesheet_directory());
		$command   = "git -C {$directory} pull origin main";
		exec($command, $output);
		if ( empty( $output ) ) {
			$this->admin_notices[] = array(
				'status'  => 'error',
				'content' => "Error: <b>No renponse from server</b>.",
			);
		}
		else {
			$this->admin_notices[] = array(
				'status'  => 'success',
				'content' => 'Successfully git pull command worked: <b>' . $output[0] . '</b>',
			);
		}
    self::admin_notices();
  }

	public function admin_notices() {
		echo '<h1 class="wp-heading-inline">git pull</h1>';
		if ( empty( $this->admin_notices ) ) {
			return;
		}
		foreach ( $this->admin_notices as $notice ) {
			?>
			<div class="notice notice-<?php echo esc_attr( $notice['status'] ); ?>">
				<p><?php echo $notice['content']; // WPCS: XSS OK. ?></p>
			</div>
			<?php
		}
	}
}
new Git_Pull();
