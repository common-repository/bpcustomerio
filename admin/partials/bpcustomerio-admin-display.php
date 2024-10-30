<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Bpcustomerio
 * @subpackage Bpcustomerio/admin/partials
 */
class Bpcustomerio_Admin_Display {

	public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_settings_menu'));
    }
	
public static function add_settings_menu(){
  		 add_menu_page('BP Customer IO', 'BP Customer IO', 'manage_options', 'bpcustomerio', array(__CLASS__,'bpcustomeriogeneral_setting'));
	}
 
	public static function bpcustomeriogeneral_setting_fields(){
			
	    $fields[] = array(
			'title' => esc_html__('Analytics for BuddyPress by Customer.io', 'bpcustomerio'),
			'type' => 'title',
			'id' => 'general_options_setting'
		);
		$fields[] = array(
			'title' => esc_html__('Enable Option', 'bpcustomerio'),
			'id' => 'bpcustomerio_enable',
			'type' => 'checkbox',
			'label' => esc_html__('Enable Option', 'bpcustomerio'),
			'default' => 'no',
			'class'=>'regular-text',
		);
		$fields[] = array(
			'title' => esc_html__('Enable Profile Not Completed Event', 'bpcustomerio'),
			'id' => 'bpcustomerio_pnce',
			'type' => 'checkbox',
			'label' => esc_html__('Enable Option', 'bpcustomerio'),
			'default' => 'no',
			'class'=>'regular-text',
		);
		
		$fields[] = array(
			'title' => esc_html__('Site ID', 'bpcustomerio'),
			'id' => 'bpcustomerio_site_id',
			'type' => 'text',
			'label' => esc_html__('Enter Site ID', 'bpcustomerio'),
			'default' => '',
			'class'=>'regular-text',
		);

		$fields[] = array(
			'title' => esc_html__('API Key', 'bpcustomerio'),
			'id' => 'bpcustomerio_appid',
			'type' => 'text',
			'label' => esc_html__('Enter API Key', 'bpcustomerio'),
			'default' => '',
			'class'=>'regular-text',
		);
		
		
		
			

		$fields[] = array('type' => 'sectionend', 'id' => 'general_options_setting');
		return $fields;
	}
	
	public static function bpcustomeriogeneral_setting() {
		$genral_setting_fields = self::bpcustomeriogeneral_setting_fields();

		$Html_output = new Bpcustomerio_Html_output();
		$Html_output->save_fields($genral_setting_fields);
		if (isset($_POST['bpcustomerio_intigration'])):
            ?>
            <div id="setting-error-settings_updated" class="updated settings-error"> 
                <p><?php echo '<strong>' . esc_html__('Settings were saved successfully.', 'bpcustomerio') . '</strong>'; ?></p></div>

            <?php
            endif;

        if( !in_array( 'buddypress/bp-loader.php',apply_filters('active_plugins',get_option('active_plugins')))) {?>  
               
                <div class="error"><p><?php echo '<strong>' . esc_html__('Buddypress needs to be activated.', 'bpcustomerio') . '</strong>'; ?></p> </div>
		<?php }else {?>
        <div class="div_general_settings">
        <div class="div_log_settings">
	        <form id="bpcustomerio_integration_form_general" enctype="multipart/form-data" action="" method="post">
	            <?php $Html_output->init($genral_setting_fields); ?>
	            <p class="submit">
                    <input type="hidden" name="bpcustomerio-ajax-nonce" id="bpcustomerio-ajax-nonce" value="<?php echo wp_create_nonce( "bpcustomerio-ajax-nonce" ); ?>" />
	                <input type="submit" name="bpcustomerio_intigration" class="button-primary" value="<?php esc_attr_e('Save Settings', 'Option'); ?>" />
	            </p>
	        </form>
        </div><?php 

		}
	}
	
}
Bpcustomerio_Admin_Display::init();