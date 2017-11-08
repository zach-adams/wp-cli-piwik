<?php
if ( !defined( 'WP_CLI' ) ) return;


/**
 * Changes WP-Piwik settings.
 */
class Piwik_Command extends WP_CLI_Command {
	
	private $globalSettings = array (
			// Plugin settings
			'revision' => 0,
			'last_settings_update' => 0,
			// User settings: Piwik configuration
			'piwik_mode' => 'http',
			'piwik_url' => '',
			'piwik_path' => '',
			'piwik_user' => '',
			'piwik_token' => '',
			'auto_site_config' => true,
			// User settings: Stats configuration
			'default_date' => 'yesterday',
			'stats_seo' => false,
            'stats_ecommerce' => false,
			'dashboard_widget' => false,
			'dashboard_ecommerce' => false,
			'dashboard_chart' => false,
			'dashboard_seo' => false,
			'toolbar' => false,
			'capability_read_stats' => array (
					'administrator' => true
			),
			'perpost_stats' => false,
			'plugin_display_name' => 'WP-Piwik',
			'piwik_shortcut' => false,
			'shortcodes' => false,
			// User settings: Tracking configuration
			'track_mode' => 'disabled',
			'track_codeposition' => 'footer',
			'track_noscript' => false,
			'track_nojavascript' => false,
			'proxy_url' => '',
			'track_content' => 'disabled',
			'track_search' => false,
			'track_404' => false,
			'add_post_annotations' => array(),
			'add_customvars_box' => false,
			'add_download_extensions' => '',
			'set_download_extensions' => '',
			'set_link_classes' => '',
			'set_download_classes' => '',
			'disable_cookies' => false,
			'limit_cookies' => false,
			'limit_cookies_visitor' => 34186669, // Piwik default 13 months
			'limit_cookies_session' => 1800, // Piwik default 30 minutes
			'limit_cookies_referral' => 15778463, // Piwik default 6 months
			'track_admin' => false,
			'capability_stealth' => array (),
			'track_across' => false,
			'track_across_alias' => false,
			'track_crossdomain_linking' => false,
			'track_feed' => false,
			'track_feed_addcampaign' => false,
			'track_feed_campaign' => 'feed',
			'track_heartbeat' => 0,
			'track_user_id' => 'disabled',
			// User settings: Expert configuration
			'cache' => true,
			'http_connection' => 'curl',
			'http_method' => 'post',
			'disable_timelimit' => false,
			'connection_timeout' => 5,
			'disable_ssl_verify' => false,
			'disable_ssl_verify_host' => false,
			'piwik_useragent' => 'php',
			'piwik_useragent_string' => 'WP-Piwik',
            'dnsprefetch' => false,
			'track_datacfasync' => false,
			'track_cdnurl' => '',
			'track_cdnurlssl' => '',
			'force_protocol' => 'disabled',
			'update_notice' => 'enabled'
	), $settings = array (
			'name' => '',
			'site_id' => NULL,
			'noscript_code' => '',
			'tracking_code' => '',
			'last_tracking_code_update' => 0,
			'dashboard_revision' => 0
	);
	
	/**
	 * @var string
	 */
	protected $piwik_plugin = 'wp-piwik';
	
	/**
	 * Init actions
	 */
	protected function init()
	{
		
		if(!$this->check_plugin_installed()) {
			WP_CLI::error("WP-Piwik plugin not installed!");
			exit(1);
		}
		
	}
	
	/**
	 * Checks to see if the plugin's installed or not
	 *
	 * @return bool
	 */
	protected function check_plugin_installed() {
		
		return class_exists('WP_Piwik');
	
	}
	
	/**
	 * @return \WP_Piwik\Settings
	 */
	protected function get_wp_piwik()
	{
		$wp_piwik = $GLOBALS['wp-piwik'];
		$wp_piwik_settings = new \WP_Piwik\Settings($wp_piwik);
		return $wp_piwik_settings;
	}
	
	protected function parse_args($args, $settings, $default)
	{
	
	}
	
	/**
     * Set piwik mode.
     *
     * ## OPTIONS
     *
     * <mode>
     * : You can choose between three connection methods - 'http', 'php', 'cloud', and 'disabled'
	 * ---
	 * http - This is the default option for a self-hosted Piwik and should work for most configurations. WP-Piwik will connect to Piwik using http(s)
	 * php - Choose this, if your self-hosted Piwik and WordPress are running on the same machine and you know the full server path to your Piwik instance.
	 * cloud - If you are using a cloud-hosted Piwik by InnoCraft, you can simply use this option.
	 * disabled (default) - If you just want Piwik disabled, choose this.
	 * ---
     *
     * ## EXAMPLES
     *
     *     wp piwik mode http
     *
     * @param $args
     */
    public function mode( $args, $assoc_args ) {
    	
    	$settings = ['http', 'php', 'cloud', 'disabled'];
    	$default = 'disabled';
    	
    	$this->init();
    	
        list( $mode ) = $args;
	    
        if(!in_array($mode, $settings)) {
            $mode = $default;
        }
        
        WP_CLI::debug("Mode is $mode");
        
        $wp_piwik = $this->get_wp_piwik();
        
        $wp_piwik->setGlobalOption('piwik_mode', $mode);
        $wp_piwik->save();
        
        // Print a success message
        WP_CLI::success( "Piwik Mode set to: $mode" );
        
    }
	
	/**
     * Set piwik url.
     * 
     * ## OPTIONS
     * 
     * <url>
     * : Enter your Piwik URL. This is the same URL you use to access your Piwik instance, e.g. http://www.example.com/piwik/
     * 
     * ## EXAMPLES
     * 
     *     wp piwik url http://www.example.com/piwik
     *
     * @param $args
     */
    public function url( $args, $assoc_args ) {
    	
    	$this->init();
        list( $url ) = $args;
        update_site_option ( 'wp-piwik_global-piwik_url', $url );
        // Print a success message
        WP_CLI::success( "Url set to: $url" );
        
    }
    /**
     * Set auth token.
     * 
     * ## OPTIONS
     * 
     * <token>
     * : Enter your Piwik auth token here. It is an alphanumerical code like 0a1b2c34d56e78901fa2bc3d45678efa (see WP-Piwik faq for more info)
     * 
     * ## EXAMPLES
     * 
     *     wp piwik token 0a1b2c34d56e78901fa2bc3d45678efa
     *
     * @synopsis <token>
     */
    public function token( $args, $assoc_args ) {
		
        list( $token ) = $args;
        update_site_option ( 'wp-piwik_global-piwik_token', $token );
        // Print a success message
        WP_CLI::success( "Auth token set to: $token" );
        
    }
    
}

WP_CLI::add_command( 'piwik', 'Piwik_Command' );
