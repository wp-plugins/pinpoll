<?php
$pinpoll_settings_init = new pinpoll_settings('pinpoll_settings_config');

class pinpoll_settings_config {
		 
	function __construct() {
		//do nothing
	}

	protected static function getDropdown() {
		return self::$dropdown_options;
	}
	
	protected static function setDropdown($value) {
		self::$dropdown_options = $value;
	}

	// MAIN CONFIGURATION SETTINGS 
	var $group = "pinpollSettingsDisplay"; // defines setting groups
	var $page_name = "pinpoll_settings_display"; // defines which pages settings will appear on. Either bespoke or media/discussion/reading etc
	 
	//  DISPLAY SETTINGS
	var $title = "Code Generator";  
	var $intro_text = 'This page allows you to generate the required HTML code to include the <a href="http://wordpress.org/plugins/pinpoll/" target="_blank">PINPOLL Widget</a> within any post.'; 
	var $nav_title = "PINPOLL"; // how page is listed on left-hand Settings panel

	//  SECTIONS
	var $sections = array(
      'code_options' => array(
          'title' => 'Poll Settings',
          'description' => "Enter or select the required parameters for your polls and then hit the button 'Get Code!'",  
          'fields' => array(
            'poll_type' => array(
              'label' => "Select Type",
              'dropdown' => "poll_type_dropdown",
              'default_value' => ""		
            ),
            'poll_id' => array (
              'label' => "Specific Poll",
              'description' => "If you choose to include a specific poll, enter the ID of such existing poll from pinpoll.net.",
              'default_value' => "3480"
            ),
			'board_id' => array(
              'label' => "Specific Board",
              'description' => "If you choose to include a specific board, enter the ID of such existing board from pinpoll.net.",
              'default_value' => "0"
            ),
			'category_id' => array(
              'label' => "Random Category",
              'dropdown' => "category_id_dropdown",
              'default_value' => "0"
            ),
            'popular_min' => array (
              'label' => "Minimum Answers",
              'description' => "If you choose to include random polls, you may limit their minimum sample size.",
              'default_value' => "0"
            ),
		    'width' => array (
              'label' => "Width",
              'description' => "Set width as integer for pixels (e.g., 350).",
              'length' => "3",
              'default_value' => "350"
            ),
			'height' => array (
              'label' => "Height",
              'description' => "Set height as integer for pixels (e.g., 350).",
              'length' => "3",
              'default_value' => "350"
            ),
            'colour' => array (
              'label' => "Custom Colour",
              'description' => "Use hex-code without # sign to adjust look &amp; feel (e.g., FF3366).",
              'default_value' => ""
            )
          )
		)
    );
 	
	// DROPDOWN OPTIONS
	public static $dropdown_options = array (
		'poll_type_dropdown' => array (
			'' => "Select Type...",
			'poll' => "Specific Poll",
			'board' => "Specific Board",
			'category' => "Random Polls from Category"
		)
	);
 	
	public static function setCategories() {
		
		$url = "https://pinpoll.net/plugin/getCategories";
	
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
				
		$options = self::getDropdown();
		
		$options['category_id_dropdown'] = $categoriesArr;
		
		self::setDropdown($options);
	
	}
//  end class
};
 
class pinpoll_settings {
 
	function pinpoll_settings($settings_class) {
		global $pinpoll_settings;
		
		call_user_func(array($settings_class, 'setCategories'));
		$pinpoll_settings = get_class_vars($settings_class);
				
		if (function_exists('add_action')) :
		  add_action('admin_init', array( &$this, 'plugin_admin_init'));
		  add_action('admin_menu', array( &$this, 'plugin_admin_add_page'));
		endif;
	}
	 
	function plugin_admin_add_page() {
		global $pinpoll_settings;
		add_options_page($pinpoll_settings['title'], $pinpoll_settings['nav_title'], 'manage_options', $pinpoll_settings['page_name'], array( &$this,'plugin_options_page'));
	}
 
	function plugin_options_page() {
		global $pinpoll_settings;
		echo '</pre><div>';
		printf('<h2>%s</h2>%s<form action="options.php" method="post">', $pinpoll_settings['title'], $pinpoll_settings['intro_text']);
		settings_fields($pinpoll_settings['group']);
		do_settings_sections($pinpoll_settings['page_name']);
		printf('<p class="submit"><input type="submit" name="Submit" value="%s" /></p></form>',__('Get Code!'));
			
		//Produce the Code
		$service_url_base =	"https://pinpoll.net/plugin/getPoll/";
		$options = get_option($pinpoll_settings['group'].'_'.'poll_type');		
			
		switch($options['text_string']) {
			case 'poll': default:
				$options = get_option($pinpoll_settings['group'].'_'.'poll_id');
				$poll_id =	$options['text_string'];
				$url = $service_url_base."?id=".$poll_id;
			break;	
			case 'board': default:
				$options = get_option($pinpoll_settings['group'].'_'.'board_id');
				$board_id =	$options['text_string'];
				$url = $service_url_base."?board_id=".$board_id;
			break;	
			case 'category':
				$options = get_option($pinpoll_settings['group'].'_'.'category_id');
				$category_id =	$options['text_string'];
				$options = get_option($pinpoll_settings['group'].'_'.'popular_min');
				$popular_min =	$options['text_string'];
				$url = $service_url_base."?category_id=".$category_id."&popular_min=".$popular_min;
			break;
		}
		
		$options = get_option($pinpoll_settings['group'].'_'.'height');		
		$height = $options['text_string'];
		$options = get_option($pinpoll_settings['group'].'_'.'width');		
		$width = $options['text_string'];
		$options = get_option($pinpoll_settings['group'].'_'.'colour');		
		$colour = $options['text_string'];
		
		$url.= "&width=".$width."&height=".$height."&colour=".$colour;
	
		echo "<h3>Copy this code and paste it to the post of your choice:</h3>";
		echo '<textarea style="width:500px; height:50px; font-family:courier; font-size:13px;" name="code" id="code">';
		printf('[pinpoll]%s,%s,%s[/pinpoll]', $url, $width, $height);
		echo "</textarea>";
		
		echo '</div><pre>';
	}
 
	function plugin_admin_init(){
		global $pinpoll_settings;
		foreach ($pinpoll_settings["sections"] as $section_key=>$section_value) {
			add_settings_section($section_key, $section_value['title'], array( &$this, 'plugin_section_text'), $pinpoll_settings['page_name'], $section_value);
			foreach ($section_value['fields'] as $field_key=>$field_value) {
				$function = (!empty($field_value['dropdown'])) ? array( &$this, 'plugin_setting_dropdown' ) : array( &$this, 'plugin_setting_string' );
				$function = (!empty($field_value['function'])) ? $field_value['function'] : $function;
				$callback = (!empty($field_value['callback'])) ? $field_value['callback'] : NULL;
				add_settings_field($pinpoll_settings['group'].'_'.$field_key, $field_value['label'], $function, $pinpoll_settings['page_name'], $section_key, array_merge($field_value,array('name' => $pinpoll_settings['group'].'_'.$field_key)));
				register_setting($pinpoll_settings['group'], $pinpoll_settings['group'].'_'.$field_key, $callback);
			}
		}
	}
 
	function plugin_section_text($value = null) {
	  global $pinpoll_settings;
	  printf("%s", $pinpoll_settings['sections'][$value['id']]['description']);
	}
 
	function plugin_setting_string($value = null) {
		$options = get_option($value['name']);
		$default_value = (!empty ($value['default_value'])) ? $value['default_value'] : null;
		printf('<input id="%s" type="text" name="%1$s[text_string]" value="%2$s" size="40" /> %3$s%4$s',
		$value['name'],
		(!empty ($options['text_string'])) ? $options['text_string'] : $default_value,
		(!empty ($value['suffix'])) ? $value['suffix'] : NULL,
		(!empty ($value['description'])) ? sprintf("<em>%s</em>",$value['description']) : null);
	}
 
	function plugin_setting_dropdown($value = null) {
		global $pinpoll_settings;
		$options = get_option($value['name']);
		$default_value = (!empty ($value['default_value'])) ? $value['default_value'] : null;
		$current_value = ($options['text_string']) ? $options['text_string'] : $default_value;
		$chooseFrom = "";
		$choices = $pinpoll_settings['dropdown_options'][$value['dropdown']];
		foreach($choices as $key=>$option) {
			$chooseFrom .= sprintf('<option value="%s" %s>%s</option>',
			$key,($current_value == $key ) ? ' selected="selected"' : null, $option);
		}
		printf('<select id="%s" name="%1$s[text_string]">%2$s</select>%3$s',$value['name'], $chooseFrom, (!empty ($value['description'])) ? sprintf("<em>%s</em>",$value['description']) : null);
	} 
//end class
}
?>