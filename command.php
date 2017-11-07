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
		
		$this->check_plugin_installed();
		
	}
	
	/**
	 * Checks to see if the plugin's installed or not
	 */
	protected function check_plugin_installed() {
	
		$command = WP_CLI::runcommand("plugin is-installed $this->piwik_plugin", [
			'return'    =>  true,
			'parse'     =>  'json'
		]);
		
		WP_CLI::debug(print_r($command, true));
	
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
    function url( $args, $assoc_args ) {
	   
		$command = WP_CLI::runcommand("plugin is-installed $this->piwik_plugin", [
			'return'    =>  true,
			'parse'     =>  'json'
		]);
		
		WP_CLI::debug(print_r($command));
    	
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
    function token( $args, $assoc_args ) {
    	
    	WP_CLI::debug('test');
    	WP_CLI::line('test2');
	   
		$command = WP_CLI::runcommand("plugin list --format=json", [
			'return'    =>  true,
			'parse'     =>  'json'
		]);
		
		WP_CLI::debug(print_r($command, true));
		
        list( $token ) = $args;
        update_site_option ( 'wp-piwik_global-piwik_token', $token );
        // Print a success message
        WP_CLI::success( "Auth token set to: $token" );
        
    }
    
}

WP_CLI::add_command( 'piwik', 'Piwik_Command' );
