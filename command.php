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
	 * @var \WP_Piwik\Settings
	 */
	protected $piwik_settings;
	
	/**
	 * Init actions
	 */
	protected function init()
	{
		
		if(!$this->check_plugin_installed()) {
			WP_CLI::error("WP-Piwik plugin not installed!");
			exit(1);
		}
		
		$this->piwik_settings = $this->get_wp_piwik();
		
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
	 * Load up the WP_Piwik settings class
	 *
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
    	
    	$this->init();
    	
    	$available_args = ['http', 'php', 'cloud', 'disabled'];
    	$default = 'disabled';
    	
        list( $mode ) = $args;
	    
        if(!in_array($mode, $available_args)) {
            $mode = $default;
        }
        
        WP_CLI::debug("Mode is $mode");
        
        $this->piwik_settings->setGlobalOption('piwik_mode', $mode);
        $this->piwik_settings->save();
        
        // Print a success message
        WP_CLI::success( "Piwik Mode set to: $mode" );
        
    }
	
	/**
     * Set piwik path.
     *
     * ## OPTIONS
     *
     * <path>
     * : Enter the file path to your Piwik instance, e.g. /var/www/piwik/.
     *
     * ## EXAMPLES
     *
     *     wp piwik path /var/www/piwik/
     *
     * @param $args
     */
    public function path( $args, $assoc_args ) {
    	
    	$this->init();
    	
    	$default = '';
    	
        list( $path ) = $args;
	    
        if(empty($path)) {
            $path = $default;
        }
        
        WP_CLI::debug("Path is $path");
        
        $this->piwik_settings->setGlobalOption('piwik_path', $path);
        $this->piwik_settings->save();
        
        WP_CLI::success( "Piwik Path set to: $path" );
        
    }
    
	/**
     * Enter your Piwik URL.
     *
     * ## OPTIONS
     *
     * <url>
     * : Enter your Piwik URL. This is the same URL you use to access your Piwik instance, e.g. http://www.example.com/piwik/.
     *
     * ## EXAMPLES
     *
     *     wp piwik url http://www.example.com/piwik/
     *
     * @param $args
     */
    public function url( $args, $assoc_args ) {
    	
    	$this->init();
    	
    	$default = '';
    	
        list( $url ) = $args;
	    
        if(empty($url)) {
            $url = $default;
        }
        
        WP_CLI::debug("URL is $url");
        
        $this->piwik_settings->setGlobalOption('piwik_url', $url);
        $this->piwik_settings->save();
        
        WP_CLI::success( "Piwik URL set to: $url" );
        
    }
    
	/**
     * Enter your Piwik Token.
     *
     * ## OPTIONS
     *
     * <token>
     * : Enter your Piwik auth token here. It is an alphanumerical code like 0a1b2c34d56e78901fa2bc3d45678efa. See https://wordpress.org/plugins/wp-piwik/faq/.
     *
     * ## EXAMPLES
     *
     *     wp piwik token a1b2c34d56e78901fa2bc3d45678efa
     *
     * @param $args
     */
    public function token( $args, $assoc_args ) {
    	
    	$this->init();
    	
    	$default = '';
    	
        list( $token ) = $args;
	    
        if(empty($token)) {
            $token = $default;
        }
        
        WP_CLI::debug("Token is $token");
        
        $this->piwik_settings->setGlobalOption('piwik_token', $token);
        $this->piwik_settings->save();
        
        WP_CLI::success( "Piwik Token set to: $token" );
        
    }
    
	/**
     * You can choose between four tracking code modes
     *
     * ## OPTIONS
     *
     * <tracking_mode>
	 * ---
	 * default - WP-Piwik will use Piwik's standard tracking code.
	 * js - You can choose this tracking code, to deliver a minified proxy code and to avoid using the files called piwik.js or piwik.php.
	 * proxy - Use this tracking code to not reveal the Piwik server URL. See https://piwik.org/faq/how-to/#faq_132
	 * manually - Enter your own tracking code manually. You can choose one of the prior options, pre-configure your tracking code and switch to manually editing at last.
	 * disabled (default) - If you just want Piwik tracking code disabled, choose this.
	 * ---
     *
     * ## EXAMPLES
     *
     *     wp piwik tracking_mode default
     *
     * @param $args
     */
    public function tracking_mode( $args, $assoc_args ) {
    	
    	$this->init();
    	
    	$default = '';
    	
        list( $tracking_mode ) = $args;
	    
        if(empty($tracking_mode)) {
            $tracking_mode = $default;
        }
        
        WP_CLI::debug("Tracking mode is $tracking_mode");
        
        $this->piwik_settings->setGlobalOption('track_mode', $tracking_mode);
        $this->piwik_settings->save();
        
        WP_CLI::success( "Piwik Tracking Mode set to: $tracking_mode" );
        
    }
    
}

WP_CLI::add_command( 'piwik', 'Piwik_Command' );
