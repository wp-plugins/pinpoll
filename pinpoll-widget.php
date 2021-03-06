<?php
/*
Plugin Name: Pinpoll
Plugin URI: https://pinpoll.com
Description: Create polls. Embed polls. Know your audience and customers.
Version: 2.6
Min WP Version: 3.0
Author: Tobias Oberascher
Author URI: https://pinpoll.com
*/
?>
<?php
include ("pinpoll-code-generator.php");

add_action('widgets_init', create_function('', 'return register_widget("Pinpoll_Widget");'));
add_filter('the_content', 'widget_pinpoll_on_page', 10, 1);
add_filter( 'plugin_action_links', 'pinpoll_plugin_action_links', 10, 2 );

class Pinpoll_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'Pinpoll_Widget', 'description' => "This widget displays polls from pinpoll.com inside your blog." );
		$control_ops = array('height' => 350);
		self::setCategories();
		parent::__construct('pinpoll', __('Pinpoll'), $widget_ops, $control_ops);
	}

	protected static $dropdown_options = array();

	protected static function getDropdown() {
		return self::$dropdown_options;
	}

	protected static function setDropdown($value) {
		self::$dropdown_options = $value;
	}

	protected static function setCategories() {

		$url = "https://pinpoll.com/plugin/getCategories";

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$result=curl_exec($ch);

		$categories = json_decode($result, true);

		$categoriesArr = array();

		foreach($categories['categories'] as $key=>$elem) {
			$categoriesArr[$elem['category_id']] = $elem['title'];
		}

		self::setDropdown($categoriesArr);

	}

	function form($instance) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => 'Share Opinion.',
				'height' => 350,
				'poll_id' => 3480,  //The default poll
				'service_url_base' => 'https://pinpoll.com/plugin',
				'service_url' => 'https://pinpoll.com/plugin?id=3480',
				'fallback_url_base' => 'https://pinpoll.com',
				'fallback_url' => 'https://pinpoll.com/poll/3480',
				'colour' => '',
				'poll_type' => '',
				'board_id' => 0,
				'category_id' => 0,
				'popular_min' => 0,
				'description' => 1,
				'map' => 1
			)
		);
		$title = strip_tags($instance['title']);
		$height = strip_tags($instance['height']);
		$poll_id = strip_tags($instance['poll_id']);
		$service_url_base = strip_tags($instance['service_url_base']);
		$service_url = strip_tags($instance['service_url_base']).strip_tags($instance['poll_id']);
		$fallback_url_base = strip_tags($instance['fallback_url_base']);
		$fallback_url = strip_tags($instance['fallback_url_base']).strip_tags($instance['poll_id']);
		$colour = strip_tags($instance['colour']);
		$poll_type = strip_tags($instance['poll_type']);
		$board_id = strip_tags($instance['board_id']);
		$category_id = strip_tags($instance['category_id']);
		$popular_min = strip_tags($instance['popular_min']);
		$description = strip_tags($instance['description']);
		$map = strip_tags($instance['map']);
		?>

        <script type="text/javascript">
			jQuery(document).ready(function($) {

				toggleArea = function(elem) {
					var widget_area = $(elem).closest('form');
					switch($(elem).val()) {
						case 'poll':
							widget_area.find('.category, .board').hide();
							widget_area.find('.poll').fadeIn();
							widget_area.find('.popular_min').hide();
							widget_area.find('.widget-control-save').removeAttr('disabled');
						break;
						case 'board':
							widget_area.find('.category, .poll').hide();
							widget_area.find('.board').fadeIn();
							widget_area.find('.popular_min').hide();
							widget_area.find('.widget-control-save').removeAttr('disabled');
						break;
						case 'category':
							widget_area.find('.poll, .board').hide();
							widget_area.find('.popular_min').fadeIn();
							widget_area.find('.category').fadeIn();
							widget_area.find('.widget-control-save').removeAttr('disabled');
						break;
						default:
							widget_area.find('.poll, .board, .category, .popular_min').fadeOut();
							widget_area.find('.widget-control-save').attr('disabled', 'disabled');
						break;
					}

				}

				$('.poll_type').on('change', function() {
					toggleArea($(this));
				});

				//Init and hide all
				toggleArea($("#<?php echo $this->get_field_id('poll_type'); ?>"));

			});
		</script>

		<p><small>Display specific polls and boards or load random polls from a category in an <a href="http://www.w3.org/TR/html4/present/frames.html#edef-IFRAME">iframe</a>. For plug-in details <a href="https://pinpoll.com" target="_blank">click here</a>.</small></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input size="28" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
        <p>
        	<select class="poll_type" id="<?php echo $this->get_field_id('poll_type'); ?>" name="<?php echo $this->get_field_name('poll_type'); ?>" >
            	<option value="" <?php echo strip_tags($instance['poll_type']) == "" ? 'selected="selected"' : ''; ?>>Select Type...</option>
            	<option value="poll" <?php echo strip_tags($instance['poll_type']) == "poll" ? 'selected="selected"' : ''; ?>>Specific Poll</option>
            	<option value="board" <?php echo strip_tags($instance['poll_type']) == "board" ? 'selected="selected"' : ''; ?>>Specific Board</option>
            	<option value="category" <?php echo strip_tags($instance['poll_type']) == "category" ? 'selected="selected"' : ''; ?>>Random Polls from Category</option>
            </select>
        </p>
		<p class="poll">
			<label for="<?php echo $this->get_field_id( 'poll_id' ); ?>"><?php _e( 'Poll ID:' ); ?></label>
			<input size="28" id="<?php echo $this->get_field_id('poll_id'); ?>" name="<?php echo $this->get_field_name('poll_id'); ?>" type="text" value="<?php echo esc_attr($poll_id); ?>" />
		</p>
		<p class="board">
			<label for="<?php echo $this->get_field_id( 'board_id' ); ?>"><?php _e( 'Board ID:' ); ?></label>
			<input size="28" id="<?php echo $this->get_field_id('board_id'); ?>" name="<?php echo $this->get_field_name('board_id'); ?>" type="text" value="<?php echo esc_attr($board_id); ?>" />
		</p>
		<p class="category">
        	<select class="categories" id="<?php echo $this->get_field_id('category_id'); ?>" name="<?php echo $this->get_field_name('category_id'); ?>" >
            <?php

			$dropdown = self::getDropdown();
			foreach($dropdown as $key=>$elem) {
				echo sprintf('<option value="%s" %s>%s</option>', $key, (strip_tags($instance['category_id']) == $key) ? ' selected="selected"' : null, $elem);
			}
			?>
            </select>
		</p>
		<p class="popular_min">
			<label for="<?php echo $this->get_field_id( 'popular_min' ); ?>"><?php _e( 'Minimum Answers:' ); ?></label>&nbsp;
			<input size="4" id="<?php echo $this->get_field_id('popular_min'); ?>" name="<?php echo $this->get_field_name('popular_min'); ?>" type="text" value="<?php echo esc_attr($popular_min); ?>" />
		</p>
        <hr style="margin:15px 0; color:#FFF; background-color:#FFF;" />
		<p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Height:' ); ?></label>
			<input size="4" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo esc_attr($height); ?>" />
		</p>
		<p><small>Hint: Set height as integer for pixels (e.g., 350).</small></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'colour' ); ?>"><?php _e( 'Custom Colour:' ); ?></label>
			<input size="28" id="<?php echo $this->get_field_id('colour'); ?>" name="<?php echo $this->get_field_name('colour'); ?>" type="text" value="<?php echo esc_attr($colour); ?>" />
		</p>
        <p><small>Hint: Use hex-code without # sign to adjust look &amp; feel (e.g., FF3366).</small></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description:' ); ?></label>
			<input id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" type="checkbox" value="<?php echo esc_attr($description); ?>" <?php echo strip_tags($instance['description']) == 1 ? 'checked="checked"' : ''; ?> />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'map' ); ?>"><?php _e( 'Map:' ); ?></label>
			<input id="<?php echo $this->get_field_id('map'); ?>" name="<?php echo $this->get_field_name('map'); ?>" type="checkbox" value="<?php echo esc_attr($map); ?>"  <?php echo strip_tags($instance['map']) == 1 ? 'checked="checked"' : ''; ?> />
		</p>
        <input type="hidden" id="<?php echo $this->get_field_id('service_url_base'); ?>" name="<?php echo $this->get_field_name('service_url_base'); ?>" value="<?php echo esc_attr($service_url_base); ?>" />
        <input type="hidden" id="<?php echo $this->get_field_id('fallback_url_base'); ?>" name="<?php echo $this->get_field_name('fallback_url_base'); ?>" value="<?php echo esc_attr($fallback_url_base); ?>" />
		<?php
	}

	function update($new_instance, $old_instance) {

		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['height'] = trim(strip_tags($new_instance['height'])) != "" ? strip_tags($new_instance['height']) : 350;
		$instance['poll_id'] = strip_tags($new_instance['poll_id']);
		$instance['service_url_base'] = strip_tags($new_instance['service_url_base']);
		$instance['fallback_url_base'] = strip_tags($new_instance['fallback_url_base']);

		switch($new_instance['poll_type']) {
			case 'poll': default:
				$instance['service_url'] = strip_tags($new_instance['service_url_base']."?id=".$new_instance['poll_id']);
				$instance['fallback_url']= strip_tags($new_instance['fallback_url_base']."poll/".$new_instance['poll_id']);
			break;
			case 'board': default:
				$instance['service_url'] = strip_tags($new_instance['service_url_base']."?board_id=".$new_instance['board_id']);
				$instance['fallback_url']= strip_tags($new_instance['fallback_url_base']."board?board_id=".$new_instance['board_id']);
			break;
			case 'category':
				$instance['service_url'] = strip_tags($new_instance['service_url_base']."?category_id=".$new_instance['category_id']."&popular_min=".$new_instance['popular_min']);
				$instance['fallback_url']= strip_tags($new_instance['fallback_url_base']."category?category_id=".$new_instance['category_id']);
			break;
		}

		if(strlen(strip_tags($new_instance['height'])) > 0) {
			$instance['service_url'].="&height=".strip_tags($new_instance['height']);
		}

		if(strlen(strip_tags($new_instance['colour'])) > 0) {
			$instance['service_url'].="&colour=".strip_tags($new_instance['colour']);
		}

		if(array_key_exists("description", $new_instance)) {
			$instance['service_url'].="&description=1";
			$instance['description'] = 1;
		}else{
			$instance['service_url'].="&description=0";
			$instance['description'] = 0;
		}

		if(array_key_exists("map", $new_instance)) {
			$instance['service_url'].="&map=1";
			$instance['map'] = 1;
		}else{
			$instance['service_url'].="&map=0";
			$instance['map'] = 0;
		}

		$instance['poll_type'] = strip_tags($new_instance['poll_type']);
		$instance['category_id'] = strip_tags($new_instance['category_id']);
		$instance['colour'] = strip_tags($new_instance['colour']);
		$instance['popular_min'] = strip_tags($new_instance['popular_min']);

		return $instance;
	}

	//Display widget
	function widget($args, $instance) {
		extract($args);
		echo $before_widget . $before_title . $instance['title']  . $after_title;
		?>
		<iframe style="<?php echo $instance['style'] ; ?>" scrolling="0" frameborder="no" src="<?php echo $instance['service_url'] ; ?>" width="100%" height="<?php echo $instance['height'] ; ?>">
			iFrames are not supported by your browser. No worries, follow this link to <a href="<?php echo $instance['fallback_url'] ; ?>">open the poll</a>.
		</iframe>
		<?php
		echo $after_widget;
	}
}

//Parse content to convert [pinpoll] [/pinpoll] tags to html code
function widget_pinpoll_on_page($text){
	$regex = '#\[pinpoll]((?:[^\[]|\[(?!/?pinpoll])|(?R))+)\[/pinpoll]#';
	if (is_array($text)) {
		$param = explode(",", $text[1]);
		$others = "";
		$others .= ' height="' .($param[1] != "" ? $param[1] : 350) . '"';
		$others .= ' frameborder="0" scrolling="no"';
		//generate the iframe tag
		$text = '<iframe src="'.$param[0].'"'.$others.'></iframe>';
	}
	return preg_replace_callback($regex, 'widget_pinpoll_on_page', $text);
}

// Display a Settings link on the main Plugins page
function pinpoll_plugin_action_links( $links, $file ) {
	if ( $file == plugin_basename( __FILE__ ) ) {
		$pinpoll_links = '<a href="'.get_admin_url().'options-general.php?page=pinpoll_settings_display">'.__('Generator').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $pinpoll_links );
	}
	return $links;
}
?>
