<?php
/**
 * Arlo For Wordpress
 *
 * @package   Arlo_For_Wordpress_Admin
 * @author    Arlo <info@arlo.co>
 * @license   GPL-2.0+
 * @link      http://arlo.co
 * @copyright 2015 Arlo
 */

class Arlo_For_Wordpress_Settings {

	public function __construct() {

		$plugin = Arlo_For_Wordpress::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		
		if(isset($_GET['arlo-import'])) {
			$plugin->import(true);
			wp_redirect( $_SERVER['HTTP_REFERER'] );
			exit;
		}
                
		if(isset($_GET['database-upgrade'])) {
			$plugin->database_upgrade();
			wp_redirect( $_SERVER['HTTP_REFERER'] );
			exit;
		}
                

		// allocates the wp-options option key value pair that will store the plugin settings
		register_setting( 'arlo_settings', 'arlo_settings' );

		/*
		 *
		 * General Settings
		 *
		 */

		// create a section for the API Endpoint
		add_settings_section( 'arlo_general_section', __('General Settings', $this->plugin_slug), null, $this->plugin_slug );

		// create API Endpoint field                
		add_settings_field(
                        'arlo_platform_name', 
                        '<label for="arlo_platform_name">'.__('Platform Name', $this->plugin_slug).'</label>', 
                        array($this, 'arlo_simple_input_callback'), 
                        $this->plugin_slug, 'arlo_general_section', 
                        array(
                            'id' => 'platform_name',
                            'label_for' => 'arlo_platform_name',
                            'after_html' => '<a href="?page=arlo-for-wordpress&arlo-import">Re-import all data now</a>',
                            )
                );                
                
                add_settings_field('arlo_price_setting', '<label for="arlo_price_setting">Price shown</label>', array($this, 'arlo_price_setting_callback'), $this->plugin_slug, 'arlo_general_section');
                
		// create API Endpoint field
		add_settings_field(
                        'arlo_free_text', 
                        '<label for="arlo_free_text">'.__('Free text', $this->plugin_slug).'</label>', 
                        array($this, 'arlo_simple_input_callback'), 
                        $this->plugin_slug, 'arlo_general_section', 
                        array(
                            'id' => 'free_text',
                            'label_for' => 'arlo_free_text',
                            'default_val' => __('Free', $this->plugin_slug),
                            )
                );
                
		
		// create Cron field
		//add_settings_field('arlo_cron', __('Cron Status',$this->plugin_slug), array($this, 'arlo_cron_callback'), $this->plugin_slug, 'arlo_general_section', array('id'=>'arlo_cron', 'label'=>__('I have setup my own Cron jobs', $this->plugin_slug)));
		

		/*
		 *
		 * Post Type Settings
		 *
		 */

		// post type settings
	    foreach(Arlo_For_Wordpress::$post_types as $id => $post_type) {
	    	$section_id = 'arlo_'.$id.'_post_type_section';
	    
			// create on section
			add_settings_section( $section_id, __($post_type['singular_name'], $this->plugin_slug).' '.__('Post Type', $this->plugin_slug), null, $this->plugin_slug );
		
	    	// post type name
			add_settings_field(
				$id . '_post_name',
				'<label for="arlo_'.$id.'_name">Name</label>',
				array(
					$this,
					'arlo_post_name_callback'
				),
				$this->plugin_slug,
				$section_id,
				array(
					'id'		=> $id,
					'label_for' => $post_type['singular_name']
				)
			);
	    
	    	// post type name (singular)
			add_settings_field(
				$id . '_post_name_singular',
				'<label for="arlo_'.$id.'_name_singular">Name (Singular)</label>',
				array(
					$this,
					'arlo_post_name_singular_callback'
				),
				$this->plugin_slug,
				$section_id,
				array(
					'id'		=> $id,
					'label_for' => $post_type['singular_name']
				)
			);
	    
	    	// post type slug
			add_settings_field(
				$id . '_posts_page',
				'<label for="arlo_'.$id.'_posts_page">Posts Page</label>',
				array(
					$this,
					'arlo_posts_page_callback'
				),
				$this->plugin_slug,
				$section_id,
				array(
					'id'		=> $id,
					'label_for' => $post_type['singular_name']
				)
			);
	    }

		/*
		 *
		 * Template Settings
		 *
		 */

		// create a section for Templates
		add_settings_section( 'arlo_template_section', __('Templates',$this->plugin_slug), array($this, 'arlo_template_section_callback'), $this->plugin_slug );

		// loop though slug array and create each required slug field
	    foreach(Arlo_For_Wordpress::$templates as $id => $template) {
	    	$name = __($template['name'], $this->plugin_slug);
			add_settings_field( $id, '<label for="'.$id.'">'.$name.'</label>', array($this, 'arlo_template_callback'), $this->plugin_slug, 'arlo_template_section', array('id'=>$id,'label_for'=>$id) );
		}

	}

	/*
	 *
	 * SECTION CALLBACKS
	 *
	 */

	/*function arlo_cron_section_callback() {
	    echo '<p>Proactively envisioned multimedia based expertise and cross-media growth strategies. Seamlessly visualize quality intellectual capital without superior collaboration and idea-sharing. Holistically pontificate installed base portals after maintainable products.</p>';
	}*/

	function arlo_template_section_callback() {
		$output = '<div id="'.PLUGIN_PREFIX.'-template-select" class="cf">';
		$output .= '<select name="'.PLUGIN_PREFIX.'TemplateSelect">';

	    foreach(Arlo_For_Wordpress::$templates as $id => $template) {
	    	$name = __($template['name'], $this->plugin_slug);
			$output .= '<option value="'.PLUGIN_PREFIX.'-'.$id.'">'.$name.'</option>';
	    }

		$output .= '</select></div>';

		echo $output;
	}

	/*
	 *
	 * FIELD CALLBACKS
	 *
	 */
        
        function arlo_price_setting_callback() {
            $settings = get_option('arlo_settings');
            $setting_id = 'price_setting';
            
            $output = '<div id="'.PLUGIN_PREFIX.'-price-setting" class="cf">';
            $output .= '<select name="arlo_settings['.$setting_id.']">';            
            
            $val = (isset($settings[$setting_id])) ? esc_attr($settings[$setting_id]) : PLUGIN_PREFIX . '-exclgst';
            
            foreach(Arlo_For_Wordpress::$price_settings as $key => $value) {
                $key = PLUGIN_PREFIX . '-' . $key;
                $selected = $key == $val ? 'selected="selected"' : '';
                $output .= '<option ' . $selected . ' value="'.$key.'" >'.$value.'</option>';
	    }

            $output .= '</select></div>';

            echo $output;
        }
        
	function arlo_simple_input_callback($args) {
	    $settings = get_option('arlo_settings');
	    $val = (isset($settings[$args['id']])) ? esc_attr($settings[$args['id']]) : (!empty($args['default_val']) ? $args['default_val'] : '' );
            
            if (!empty($args['before_html'])) {
                $html .= $args['before_html'];
            }
            
	    $html = '<input type="text" id="arlo_'.$args['id'].'" name="arlo_settings['.$args['id'].']" value="'.$val.'" />';
            
            if (!empty($args['after_html'])) {
                $html .= $args['after_html'];
            }
            
	    echo $html;
	}     

	function arlo_post_name_callback($args) {
	    $settings = get_option('arlo_settings');
	    $val = esc_attr($settings['post_types'][$args['id']]['name']);
	    $html = '<input type="text" id="arlo_'.$args['id'].'_name" name="arlo_settings[post_types]['.$args['id'].'][name]" value="'.$val.'" />';

	    echo $html;
	}

	function arlo_post_name_singular_callback($args) {
	    $settings = get_option('arlo_settings');
	    $val = esc_attr($settings['post_types'][$args['id']]['singular_name']);
	    $html = '<input type="text" id="arlo_'.$args['id'].'_name_singular" name="arlo_settings[post_types]['.$args['id'].'][singular_name]" value="'.$val.'" />';

	    echo $html;
	}

	function arlo_posts_page_callback($args) {
	    $settings = get_option('arlo_settings');
	    $val = isset($settings['post_types'][$args['id']]['posts_page']) ? esc_attr($settings['post_types'][$args['id']]['posts_page']) : 0;

		$html = wp_dropdown_pages(array(
			'id'				=> 'arlo_'.$args['id'].'_posts_page',
		    'selected'         	=> $val,
		    'echo'             	=> 0,
		    'name'             	=> 'arlo_settings[post_types]['.$args['id'].'][posts_page]',
		    'show_option_none'	=> '-- Select --',
		    'option_none_value'	=> 0
		));

	    echo $html;
	}

	/*function arlo_cron_callback($args) {
	    $settings = (array) get_option( "arlo_settings" );
	    $val = array_key_exists($args['id'], $settings) ? esc_attr($settings[$args['id']]) : '';
	    $html = '<input type="checkbox" id="'.$args['id'].'" name="arlo_settings['.$args['id'].']" '.($val ? 'checked' : '').' />';
	    $html .= '<label for="'.$args['id'].'">'.$args['label'].'</label>';

	    echo $html;
	}*/

	function arlo_template_callback($args) {
	    $settings = get_option('arlo_settings');
	    $val = isset($settings['templates'][$args['id']]['html']) ? $settings['templates'][$args['id']]['html'] : '';
	    wp_editor($val, $args['id'], array('textarea_name'=>'arlo_settings[templates]['.$args['id'].'][html]','textarea_rows'=>'20'));
	}
}

?>