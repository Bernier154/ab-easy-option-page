<?php
namespace ABOP\Classes;

defined( 'ABSPATH' ) || exit;

class Updater {
    public $plugin_slug;
    public $version;
    public $cache_key;
    public $cache_allowed;

    public function __construct() {

        $this->plugin_slug = plugin_basename( __DIR__ );
        $this->version = \get_plugin_data(ABOP_BASE_FILE, false, false)['Version'];
        $this->cache_key = 'abop_update_request';
        $this->cache_allowed = false;

        add_filter( 'plugins_api', array( $this, 'info' ), 20, 3 );
        add_filter( 'site_transient_update_plugins', array( $this, 'update' ) );
        add_action( 'upgrader_process_complete', array( $this, 'purge' ), 10, 2 );
        add_filter( 'plugin_row_meta', array( $this, 'add_info_button' ) , 25, 4 );

    }

    public function request(){

        $remote = get_transient( $this->cache_key );

        if( false === $remote || ! $this->cache_allowed ) {

            $remote = wp_remote_get(
                'https://antoinebernier.com/plugins/ab-easy-option-page/update/update-info.php',
                array(
                    'timeout' => 10,
                    'headers' => array(
                        'Accept' => 'application/json'
                    )
                )
            );

            if( is_wp_error( $remote ) || 200 !== wp_remote_retrieve_response_code( $remote ) || empty( wp_remote_retrieve_body( $remote ) ) ) {
                return false;
            }

            set_transient( $this->cache_key, $remote, DAY_IN_SECONDS );

        }

        $remote = json_decode( wp_remote_retrieve_body( $remote ) );

        return $remote;

    }

    function info( $res, $action, $args ) {
        if( 'plugin_information' !== $action ) { return $res; }
        if( $this->plugin_slug !== $args->slug ) { return $res; }
        $remote = $this->request();
        if( ! $remote ) { return $res; }

        $res = new \stdClass();
        $res->name = $remote->name;
        $res->slug = $remote->slug;
        $res->version = $remote->version;
        $res->tested = $remote->tested;
        $res->requires = $remote->requires;
        $res->author = $remote->author;
        $res->download_link = $remote->download_url;
        $res->trunk = $remote->download_url;
        $res->requires_php = $remote->requires_php;
        $res->last_updated = $remote->last_updated;
        $res->sections = array(
            'description' => $remote->sections->description,
            'installation' => $remote->sections->installation,
            'changelog' => $remote->sections->changelog
        );
        if( ! empty( $remote->banners ) ) {
            $res->banners = array(
                'low' => $remote->banners->low,
                'high' => $remote->banners->high
            );
        }
        return $res;
    }

    public function update( $transient ) {

        if ( empty($transient->checked ) ) {
            return $transient;
        }

        $remote = $this->request();

        if( $remote && version_compare( $this->version, $remote->version, '<' ) && version_compare( $remote->requires, get_bloginfo( 'version' ), '<=' ) && version_compare( $remote->requires_php, PHP_VERSION, '<' ) ) {
            $res = new \stdClass();
            $res->slug = $this->plugin_slug;
            $res->plugin = plugin_basename( ABOP_BASE_FILE );
            $res->new_version = $remote->version;
            $res->tested = $remote->tested;
            $res->package = $remote->download_url;

            $transient->response[ $res->plugin ] = $res;
        }
        return $transient;
    }

    public function purge( $upgrader, $options ){

        if ( $this->cache_allowed && 'update' === $options['action'] && 'plugin' === $options[ 'type' ] ) {
            delete_transient( $this->cache_key );
        }

    }

    public function add_info_button($links_array, $plugin_file_name, $plugin_data, $status){
        if( strpos( $plugin_file_name, basename( ABOP_BASE_FILE ) ) ) {

            $links_array[] = sprintf(
                '<a href="%s" class="thickbox open-plugin-details-modal">%s</a>',
                add_query_arg(
                    array(
                        'tab' => 'plugin-information',
                        'plugin' => 'ab-easy-option-page',
                        'TB_iframe' => true,
                        'width' => 772,
                        'height' => 788
                    ),
                    admin_url( 'plugin-install.php' )
                ),
                __( 'View details','abop' )
            );
    
        }
    
        return $links_array;
    }
}
add_action('admin_init',function(){
    new Updater();
});
