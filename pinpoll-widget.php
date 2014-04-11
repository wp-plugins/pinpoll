<?php
/*
Plugin Name: PINPOLL
Plugin URI: http://pinpoll.net/Plug-ins/
Description: This widget displays polls from pinpoll.net inside your blog.
Version: 1.0.1
Min WP Version: 3.0
Author: Tobias Oberascher
Author URI: http://pinpoll.net/Tobias
*/
?>
<?php
include ("pinpoll-code-generator.php");

add_action('widgets_init', create_function('', 'return register_widget("Pinpoll_Widget");'));
add_filter('the_content', 'widget_pinpoll_on_page', 10, 1);
add_filter( 'plugin_action_links', 'pinpoll_plugin_action_links', 10, 2 );

class Pinpoll_Widget extends WP_Widget {

	function __construct() {	   
		$widget_ops = array('classname' => 'Pinpoll_Widget', 'description' => "This widget displays polls from pinpoll.net inside your blog." );
		$control_ops = array('width' => 200, 'height' => 250);
		self::setCategories();
		parent::__construct('pinpoll', __('PINPOLL'), $widget_ops, $control_ops);
	}	
 	
	protected static $dropdown_options = array();

	protected static function getDropdown() {
		return self::$dropdown_options;
	}
	
	protected static function setDropdown($value) {
		self::$dropdown_options = $value;
	}

	protected static function setCategories() {
		
		$url = "http://pinpoll.net/bookmarklet/getCategories";
	
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
				'width' => 200, 
				'height' => 250, 
				'poll_id' => 3480,  //The default poll
				'service_url_base' => 'http://pinpoll.net/bookmarklet/getBanner', 
				'service_url' => 'http://pinpoll.net/bookmarklet/getBanner?id=3480', 
				'fallback_url_base' => 'http://pinpoll.net/', 
				'fallback_url' => 'http://pinpoll.net/poll/3480',
				'style' => '',
				'poll_type' => '',
				'category_id' => 0,
				'popular_min' => 0
			) 
		);
		$title = strip_tags($instance['title']);
		$width = strip_tags($instance['width']);
		$height = strip_tags($instance['height']);
		$poll_id = strip_tags($instance['poll_id']);
		$service_url_base = strip_tags($instance['service_url_base']);
		$service_url = strip_tags($instance['service_url_base']).strip_tags($instance['poll_id']);
		$fallback_url_base = strip_tags($instance['fallback_url_base']);
		$fallback_url = strip_tags($instance['fallback_url_base']).strip_tags($instance['poll_id']);
		$style = strip_tags($instance['style']);
		$poll_type = strip_tags($instance['poll_type']);
		$category_id = strip_tags($instance['category_id']);
		$popular_min = strip_tags($instance['popular_min']);
		?>
        
        <script type="text/javascript">
			jQuery(document).ready(function($) {
				
				$("#<?php echo $this->get_field_id('poll_type'); ?>").closest('form').find('.widget-control-save').attr('disabled', 'disabled');
								
				toggleArea = function(elem) {
					var widget_area = $(elem).closest('form');
					switch($(elem).val()) {
						case 'specific':
							widget_area.find('.category').hide();
							widget_area.find('.specific').fadeIn();
							widget_area.find('.popular_min').hide();
							widget_area.find('.widget-control-save').removeAttr('disabled');
						break;
						case 'random':
							widget_area.find('.specific').hide();
							widget_area.find('.popular_min').fadeIn();
							widget_area.find('.category').fadeIn();
							widget_area.find('.widget-control-save').removeAttr('disabled');
						break;
						default:
							widget_area.find('.category, .specific, .popular_min').fadeOut();
							widget_area.find('.widget-control-save').attr('disabled', 'disabled');
						break;
					}
										
				}
				
				$('.poll_type').on('change', function() {
					toggleArea($(this));
				});
				
				toggleArea($("#<?php echo $this->get_field_id('poll_type'); ?>"));
				
			});
		</script>
                
		<p><small>Display specific or random polls in an <a href="http://www.w3.org/TR/html4/present/frames.html#edef-IFRAME">iframe</a>. For plug-in details <a href="http://pinpoll.net/Plug-ins" target="_blank">click here</a>.</small></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input size="28" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
        <p>
        	<select class="poll_type" id="<?php echo $this->get_field_id('poll_type'); ?>" name="<?php echo $this->get_field_name('poll_type'); ?>" >
            	<option value="" <?php echo strip_tags($instance['poll_type']) == "" ? 'selected="selected"' : ''; ?>>Select Type...</option>
            	<option value="specific" <?php echo strip_tags($instance['poll_type']) == "specific" ? 'selected="selected"' : ''; ?>>Specific Poll</option>
            	<option value="random" <?php echo strip_tags($instance['poll_type']) == "random" ? 'selected="selected"' : ''; ?>>Random Polls</option>
            </select>
        </p>
		<p class="specific hide">
			<label for="<?php echo $this->get_field_id( 'poll_id' ); ?>"><?php _e( 'Poll ID:' ); ?></label>
			<input size="28" id="<?php echo $this->get_field_id('poll_id'); ?>" name="<?php echo $this->get_field_name('poll_id'); ?>" type="text" value="<?php echo esc_attr($poll_id); ?>" />
		</p>
		<p class="category hide">
        	<select class="categories" id="<?php echo $this->get_field_id('category_id'); ?>" name="<?php echo $this->get_field_name('category_id'); ?>" >
            <?php 
			
			$dropdown = self::getDropdown();
			foreach($dropdown as $key=>$elem) {
				echo sprintf('<option value="%s" %s>%s</option>', $key, (strip_tags($instance['category_id']) == $key) ? ' selected="selected"' : null, $elem);	
			}
			?>
            </select>
		</p>
		<p class="popular_min hide">
			<label for="<?php echo $this->get_field_id( 'popular_min' ); ?>"><?php _e( 'Minimum Answers:' ); ?></label>&nbsp; 
			<input size="6" id="<?php echo $this->get_field_id('popular_min'); ?>" name="<?php echo $this->get_field_name('popular_min'); ?>" type="text" value="<?php echo esc_attr($popular_min); ?>" />
		</p>
        <hr style="margin:15px 0; color:#FFF; background-color:#FFF;" />
		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width:' ); ?></label>
			<input size="4" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo esc_attr($width); ?>" />
			<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Height:' ); ?></label> 
			<input size="4" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo esc_attr($height); ?>" />
		</p>
		<p><small>Hint: Set width &amp; height in pixels (e.g., 200px or 200) or relative (e.g., 20%).</small></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e( 'Custom CSS Style:' ); ?></label>
			<input size="28" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" type="text" value="<?php echo esc_attr($style); ?>" />
		</p>
        <p><small>Hint: Do not use quotes (e.g.,<i>align:left;</i>)</small></p>
        <input type="hidden" id="<?php echo $this->get_field_id('service_url_base'); ?>" name="<?php echo $this->get_field_name('service_url_base'); ?>" value="<?php echo esc_attr($service_url_base); ?>" />
        <input type="hidden" id="<?php echo $this->get_field_id('fallback_url_base'); ?>" name="<?php echo $this->get_field_name('fallback_url_base'); ?>" value="<?php echo esc_attr($fallback_url_base); ?>" />
		<?php
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['width'] = trim(strip_tags($new_instance['width'])) != "" ? strip_tags($new_instance['width']) : 200;
		$instance['height'] = trim(strip_tags($new_instance['height'])) != "" ? strip_tags($new_instance['height']) : 250;
		$instance['poll_id'] = strip_tags($new_instance['poll_id']);
		$instance['service_url_base'] = strip_tags($new_instance['service_url_base']);
		$instance['fallback_url_base'] = strip_tags($new_instance['fallback_url_base']);
		
		switch($new_instance['poll_type']) {
			case 'random':
				$instance['service_url'] = strip_tags($new_instance['service_url_base']."?category_id=".$new_instance['category_id']."&popular_min=".$new_instance['popular_min']);
				$instance['fallback_url']= strip_tags($new_instance['fallback_url_base']."category?category_id=".$new_instance['category_id']);
			break;
			case 'specific': default:
				$instance['service_url'] = strip_tags($new_instance['service_url_base']."?id=".$new_instance['poll_id']);
				$instance['fallback_url']= strip_tags($new_instance['fallback_url_base']."poll/".$new_instance['poll_id']);
			break;	
		}
		
		$instance['style'] = strip_tags($new_instance['style']);
		$instance['poll_type'] = strip_tags($new_instance['poll_type']);
		$instance['category_id'] = strip_tags($new_instance['category_id']);
		$instance['popular_min'] = strip_tags($new_instance['popular_min']);
		return $instance;
	}

	//Display widget
	function widget($args, $instance) {	
		extract($args);
		echo $before_widget . $before_title . $instance['title']  . $after_title; 
		?>
		<iframe style="<?php echo $instance['style'] ; ?>" scrolling="0" frameborder="no" src="<?php echo $instance['service_url'] ; ?>" width="<?php echo $instance['width'] ; ?>" height="<?php echo $instance['height'] ; ?>">
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
		$others = ' width="' .($param[1] != "" ? $param[1] : 200) . '"';
		$others .= ' height="' .($param[2] != "" ? $param[2] : 250) . '"';
		$others .= ' style="' .($param[3] != "" ? $param[3] : ""). '"';
		$others .= ' frameborder="no" scrolling="0"';			
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