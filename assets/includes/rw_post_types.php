<?php
/**
 * Custom Post Type helper class, makes it easier to create custom post types
 *
 * @package     WordPress
 * @subpackage  RotorWash
 * @since       1.0.5
 */
/*******************************************************************************************/
/* USAGE:
	$product = new RW_Post_Type("movie");
	$product->add_taxonomy('Actor');
	$product->add_taxonomy('Director');
	$product->add_meta_box('Movie Info', array(
		'name' => 'text',
		'rating' => 'text',
		'review' => 'textarea',
		'Profile Image' => 'file'
	));
*/
/*******************************************************************************************/
session_start();
class RW_Post_Type{
	public $post_type_name;
	public $post_type_args;
	function __construct($name, $post_type_args = array()){
		if (!isset($_SESSION["taxonomy_data"])) {
			$_SESSION['taxonomy_data'] = array();
		}
		$this->post_type_name = strtolower($name);
		$this->post_type_args = (array)$post_type_args;
		$this->init(array(&$this, "register_post_type"));
		$this->save_post();
	}
	function init($cb){
		add_action("init", $cb);
	}
	function admin_init($cb){
		add_action("admin_init", $cb);
	}
	function register_post_type(){
		$n = ucwords($this->post_type_name);
		$label = $n;// . 's';
		$args = array(
			"label" => $label,
			'singular_name' => $n,
			"public" => true,
			"publicly_queryable" => true,
			"query_var" => true,
			#"menu_icon" => get_stylesheet_directory_uri() . "/article16.png",
			"rewrite" => true,
			"capability_type" => "post",
			"hierarchical" => false,
			"menu_position" => null,
			"supports" => array("title", "editor", "thumbnail"),
			'has_archive' => true
		);
		$args = array_merge($args, $this->post_type_args);
		register_post_type($this->post_type_name, $args);
	}
	function add_taxonomy($taxonomy_name, $plural = '', $options = array()){
		$post_type_name = $this->post_type_name;
		if (empty($plural)) {
			$plural = $taxonomy_name . 's';
		}
		$taxonomy_name = ucwords($taxonomy_name);
		$this->init(
			function() use($taxonomy_name, $plural, $post_type_name, $options){
				$options = array_merge(
					array(
 						"hierarchical" => false,
 						"label" => $taxonomy_name,
 						"singular_label" => $plural,
 						"show_ui" => true,
 						"query_var" => true,
 						"rewrite" => array("slug" => strtolower($taxonomy_name))
					),
					$options
				);
				register_taxonomy(strtolower($taxonomy_name), $post_type_name, $options);
			});
	}
	function add_meta_box($title, $form_fields = array()){
		$post_type_name = $this->post_type_name;
		add_action('post_edit_form_tag', function(){
				echo ' enctype="multipart/form-data"';
		});
		$this->admin_init(function() use($title, $form_fields, $post_type_name){
				add_meta_box(
					strtolower(str_replace(' ', '_', $title)), // id
					$title, // title
					function($post, $data){
						global $post;
						wp_nonce_field(plugin_basename(__FILE__), 'jw_nonce');
						$inputs = $data['args'][0];
						$meta = get_post_custom($post->ID);
						foreach ($inputs as $name => $type) {
							$id_name = $data['id'] . '_' . strtolower(str_replace(' ', '_', $name));
							if (is_array($inputs[$name])) {
								if (strtolower($inputs[$name][0]) === 'select') {
									$select = "<select name='$id_name' class='widefat'>";
									foreach ($inputs[$name][1] as $option) {
										if (isset($meta[$id_name]) && $meta[$id_name][0] == $option) {
											$set_selected = "selected='selected'";
										} else $set_selected = '';
										$select .= "<option value='$option' $set_selected> $option </option>";
									}
									$select .= "</select>";
									array_push($_SESSION['taxonomy_data'], $id_name);
								}
							}
							$value = isset($meta[$id_name][0]) ? $meta[$id_name][0] : '';
							$checked = ($type == 'checkbox' && !empty($value) ? 'checked' : '');
							array_push($_SESSION['taxonomy_data'], $id_name);
							$lookup = array(
								"text" => "<input type='text' name='$id_name' value='$value' class='widefat' />",
								"textarea" => "<textarea name='$id_name' class='widefat' rows='10'>$value</textarea>",
								"checkbox" => "<input type='checkbox' name='$id_name' value='$name' $checked />",
								"select" => isset($select) ? $select : '',
								"file" => "<input type='file' name='$id_name' id='$id_name' />"
							);
							?>
							<p>
								<label><?php echo ucwords($name) . ':'; ?></label>
								<?php echo $lookup[is_array($type) ? $type[0] : $type]; ?>
							</p>
							<p>
								<?php
									$file = get_post_meta($post->ID, $id_name, true);
									if ( $type === 'file' ) {
										$file = get_post_meta($post->ID, $id_name, true);
										$file_type = wp_check_filetype($file);
										$image_types = array('jpeg', 'jpg', 'bmp', 'gif', 'png');
										if ( isset($file) ) {
											if ( in_array($file_type['ext'], $image_types) ) {
												echo "<img src='$file' alt='' style='max-width: 400px;' />";
											} else {
												echo "<a href='$file'>$file</a>";
											}
										}
									}
								?>
							</p>
							<?php
						}
					},
					$post_type_name, // associated post type
					'normal', // location/context. normal, side, etc.
					'default', // priority level
					array($form_fields) // optional passed arguments.
				); // end add_meta_box
			});
	}
	function save_post(){
		add_action('save_post', function(){
				if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
				global $post;
				if ($_POST && !wp_verify_nonce($_POST['jw_nonce'], plugin_basename(__FILE__))) {
					return;
				}
				if (isset($_SESSION['taxonomy_data'])) {
					foreach ($_SESSION['taxonomy_data'] as $form_name) {
						if (!empty($_FILES[$form_name]) ) {
							if ( !empty($_FILES[$form_name]['tmp_name']) ) {
								$upload = wp_upload_bits($_FILES[$form_name]['name'], null, file_get_contents($_FILES[$form_name]['tmp_name']));

								if (isset($upload['error']) && $upload['error'] != 0) {
									wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
								} else {
									update_post_meta($post->ID, $form_name, $upload['url']);
								}
							}
   					} else {
							if (!isset($_POST[$form_name])) $_POST[$form_name] = '';
							if (isset($post->ID) ) {
								update_post_meta($post->ID, $form_name, $_POST[$form_name]);
							}
						}
					}
					$_SESSION['taxonomy_data'] = array();
				}
			});
	}
}
