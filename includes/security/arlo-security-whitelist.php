<?php

namespace Arlo\Security;

use Arlo\Logger;

abstract class SecurityWhitelist {
    const ARLO_IPS = ['52.36.235.221','52.51.185.255','52.18.103.242','13.54.120.42','13.54.47.103'];

    protected $config_table;
    protected $dbl;
    protected $plugin;
    protected $version;
    protected $whitelisted_ips;
    protected $plugin_file;
    protected $minimum_version;
    protected $plugin_name;

    public function __construct($plugin, $dbl) {
        $this->dbl = &$dbl; 	
        $this->plugin = $plugin;
    }

    private function is_plugin_installed() {
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $all_plugins = get_plugins();

        if (array_key_exists($this->plugin_file, $all_plugins)) {
            $this->version = $all_plugins[$this->plugin_file]['Version'];

            return true;
        }

        return false;
    } 

    private function is_version_ok() {
        return $this->is_plugin_installed() && version_compare($this->version, $this->minimum_version) >= 0;
    }

    private function is_whitelist_ok() {
        $whitelisted_ips = $this->get_whitelisted_ips();

        foreach(self::ARLO_IPS as $ip) {
            if (!in_array($ip, $whitelisted_ips)) {
                return false;
            }
        }

        return true;
    }

    private function get_missing_arlo_ips() {
        $missing_ips = [];

        $whitelisted_ips = $this->get_whitelisted_ips();

        foreach(self::ARLO_IPS as $ip) {
            if (!in_array($ip, $whitelisted_ips)) {
                $missing_ips[] = $ip;
            }
        }

        return $missing_ips;
    }

    public function update_plugins_whitelist() {
        try {
            if ($this->is_version_ok() && !$this->is_whitelist_ok()) {
                $whitelisted_ips = $this->get_whitelisted_ips();
                $missing_ips = $this->get_missing_arlo_ips();
                $ips = array_merge($whitelisted_ips, $missing_ips);
                $this->update_whitelist($ips);
            }
        } catch(\Exception $e) {
            Logger::log("Couldn't update whitelist: " . $e->getMessage());
        }
    }
   
    public function check_plugins_whitelist() {
        try {
            if ($this->is_version_ok() && !$this->is_whitelist_ok()) {
                $this->raise_error_message();
            }
        } catch(\Exception $e) {
            Logger::log("Couldn't check whitelist: " . $e->getMessage());
        }
    }

    protected function raise_error_message() {
        $message = [
            '<p>' . sprintf(__('Arlo for WordPress has detected that %s plugin is installed, but Arlo\'s IP addresses hasn\'t been added to the whiltelist.'), $this->plugin_name) . '</p>',
            '<p>' . __('For more information, please visit our', 'arlo-for-wordpress') .' ' . sprintf(__('<a target="_blank" href="%s">Help Center</a>.', 'arlo-for-wordpress' ), 'https://support.arlo.co/hc/en-gb/articles/360001023963-Known-conflicts-with-other-WordPress-Plugins') . '</p>',
            '<p><a target="_blank" class="button button-primary" id="arlo_add_ips_to_whitelist">' . __('Add IP address', 'arlo-for-wordpress' ) . '</a> to the whitelist now</p>'
        ];

        $this->plugin->get_message_handler()->set_message('information', sprintf(__('%s plugin detected' , 'arlo-for-wordpress' ), $this->plugin_name), implode('', $message), false);
    }

    abstract protected function get_whitelisted_ips();
    abstract protected function update_whitelist($ips);
}