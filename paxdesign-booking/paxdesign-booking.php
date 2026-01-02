<?php
/*
Plugin Name: PAXdesign Booking System
Description: Professional booking system with minimal chat-style interface
Version: 2.2.0
Author: PAXdesign
Author URI: https://paxdesign.at
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: paxdesign-booking
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PAXDESIGN_BOOKING_VERSION', '2.2.0');
define('PAXDESIGN_BOOKING_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PAXDESIGN_BOOKING_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main Plugin Class
 */
class PAXdesign_Booking {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_footer', array($this, 'render_booking_widget'));
        add_action('wp_ajax_paxdesign_submit_booking', array($this, 'handle_booking_submission'));
        add_action('wp_ajax_nopriv_paxdesign_submit_booking', array($this, 'handle_booking_submission'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }
    
    public function enqueue_assets() {
        wp_enqueue_style(
            'paxdesign-booking-styles',
            PAXDESIGN_BOOKING_PLUGIN_URL . 'assets/css/booking-styles.css',
            array(),
            PAXDESIGN_BOOKING_VERSION
        );
        
        wp_enqueue_script(
            'paxdesign-booking-script',
            PAXDESIGN_BOOKING_PLUGIN_URL . 'assets/js/booking-script.js',
            array('jquery'),
            PAXDESIGN_BOOKING_VERSION,
            true
        );
        
        wp_localize_script('paxdesign-booking-script', 'paxdesignBooking', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('paxdesign_booking_nonce'),
            'teamMembers' => $this->get_team_members(),
            'services' => $this->get_services()
        ));
    }
    
    public function get_team_members() {
        return array(
            'adam' => array(
                'name' => 'Adam Aljmersaew',
                'role' => 'Kunden- & Vertriebsmanager',
                'email' => get_option('paxdesign_booking_email_adam', 'info@paxdesign.at'),
                'image' => 'https://paxdesign.at/wp-content/uploads/2025/12/44D4A84C-8747-495B-99BD-645CAF57809A.jpeg',
                'has_services' => true
            ),
            'ahmad' => array(
                'name' => 'Ahmad Al Khallaf',
                'role' => 'Web Development Specialist',
                'email' => get_option('paxdesign_booking_email_ahmad', 'info@paxdesign.at'),
                'image' => 'https://paxdesign.at/wp-content/uploads/2025/12/38319D43-77FD-42D8-91BA-69E23BE7879C-e1767119492655.avif',
                'has_services' => false
            ),
            'bernt' => array(
                'name' => 'Bernt Unterluggauer',
                'role' => 'Leitung & Vertrieb',
                'email' => get_option('paxdesign_booking_email_bernt', 'info@paxdesign.at'),
                'image' => 'https://paxdesign.at/wp-content/uploads/2025/12/EDDB358B-0472-4E54-BA12-946C1170DA7C.jpeg',
                'has_services' => false
            )
        );
    }
    
    public function get_services() {
        return array(
            'website' => array(
                'name' => 'Website',
                'price_monthly' => '4.000',
                'price_yearly' => '42.000',
                'description' => 'Professionelle Website mit modernem Design',
                'icon' => '🌐'
            ),
            'webapp' => array(
                'name' => 'Web App',
                'price_monthly' => '8.000',
                'price_yearly' => '84.000',
                'description' => 'Maßgeschneiderte Webanwendung',
                'icon' => '💻'
            ),
            'android' => array(
                'name' => 'Android App',
                'price_monthly' => '10.000',
                'price_yearly' => '105.000',
                'description' => 'Native Android-Anwendung',
                'icon' => '📱'
            ),
            'ios' => array(
                'name' => 'iOS App',
                'price_monthly' => '12.000',
                'price_yearly' => '126.000',
                'description' => 'Native iOS-Anwendung',
                'icon' => '🍎'
            ),
            'crossplatform' => array(
                'name' => 'iOS + Android',
                'price_monthly' => '16.000',
                'price_yearly' => '168.000',
                'description' => 'Cross-Platform App',
                'icon' => '📲',
                'popular' => true
            ),
            'androidtv' => array(
                'name' => 'Android TV',
                'price_monthly' => '14.000',
                'price_yearly' => '147.000',
                'description' => 'TV-Anwendung',
                'icon' => '📺'
            ),
            'security' => array(
                'name' => 'IT-Sicherheit',
                'price_monthly' => '16.000',
                'price_yearly' => '168.000',
                'description' => 'Security Audit & Implementierung',
                'icon' => '🔒'
            ),
            'backend' => array(
                'name' => 'Backend System',
                'price_monthly' => '18.000',
                'price_yearly' => '189.000',
                'description' => 'Skalierbare Backend-Infrastruktur',
                'icon' => '⚙️'
            ),
            'devops' => array(
                'name' => 'Server & DevOps',
                'price_monthly' => '20.000',
                'price_yearly' => '210.000',
                'description' => 'Server-Infrastruktur & CI/CD',
                'icon' => '🖥️'
            ),
            'enterprise' => array(
                'name' => 'Enterprise',
                'price_monthly' => '30.000',
                'price_yearly' => '315.000',
                'description' => 'Komplettlösung für Unternehmen',
                'icon' => '🏢',
                'premium' => true
            )
        );
    }
    
    public function render_booking_widget() {
        include PAXDESIGN_BOOKING_PLUGIN_DIR . 'templates/booking-widget.php';
    }
    
    public function handle_booking_submission() {
        check_ajax_referer('paxdesign_booking_nonce', 'nonce');
        
        $booking_data = array(
            'member' => sanitize_text_field($_POST['member']),
            'service' => isset($_POST['service']) ? sanitize_text_field($_POST['service']) : '',
            'date' => sanitize_text_field($_POST['date']),
            'time' => sanitize_text_field($_POST['time']),
            'name' => sanitize_text_field($_POST['name']),
            'email' => sanitize_email($_POST['email']),
            'phone' => sanitize_text_field($_POST['phone']),
            'purpose' => sanitize_text_field($_POST['purpose']),
            'message' => sanitize_textarea_field($_POST['message'])
        );
        
        if (empty($booking_data['member']) || empty($booking_data['date']) || 
            empty($booking_data['time']) || empty($booking_data['name']) || 
            empty($booking_data['email'])) {
            wp_send_json_error(array('message' => 'Bitte füllen Sie alle Pflichtfelder aus.'));
            return;
        }
        
        $team_members = $this->get_team_members();
        $member_info = $team_members[$booking_data['member']];
        
        $email_sent = $this->send_booking_email($booking_data, $member_info);
        $this->save_booking_to_database($booking_data);
        
        if ($email_sent) {
            wp_send_json_success(array(
                'message' => 'Termin erfolgreich gebucht!',
                'booking_data' => $booking_data,
                'member_info' => $member_info
            ));
        } else {
            wp_send_json_error(array('message' => 'Fehler beim Senden der E-Mail.'));
        }
    }
    
    private function send_booking_email($booking_data, $member_info) {
        $to = get_option('paxdesign_booking_notification_email', 'info@paxdesign.at');
        $subject = sprintf('Neue Terminbuchung: %s', $booking_data['name']);
        
        $date_obj = DateTime::createFromFormat('Y-m-d', $booking_data['date']);
        $formatted_date = $date_obj->format('d.m.Y');
        
        $service_info = '';
        if (!empty($booking_data['service'])) {
            $services = $this->get_services();
            if (isset($services[$booking_data['service']])) {
                $service = $services[$booking_data['service']];
                $service_info = sprintf("\nGEWÄHLTER SERVICE:\n%s - ab €%s\n", 
                    $service['name'], 
                    $service['price_monthly']
                );
            }
        }
        
        $message = sprintf("
Neue Terminbuchung bei PAXdesign
=====================================

ANSPRECHPARTNER:
%s - %s
%s
TERMIN:
Datum: %s
Uhrzeit: %s

KUNDE:
Name: %s
E-Mail: %s
Telefon: %s

DETAILS:
Zweck: %s
Nachricht: %s
        ",
            $member_info['name'],
            $member_info['role'],
            $service_info,
            $formatted_date,
            $booking_data['time'],
            $booking_data['name'],
            $booking_data['email'],
            $booking_data['phone'],
            $booking_data['purpose'],
            $booking_data['message']
        );
        
        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: PAXdesign Booking <noreply@paxdesign.at>',
            'Reply-To: ' . $booking_data['email']
        );
        
        $sent = wp_mail($to, $subject, $message, $headers);
        
        if ($sent) {
            $this->send_customer_confirmation($booking_data, $member_info);
        }
        
        return $sent;
    }
    
    private function send_customer_confirmation($booking_data, $member_info) {
        $to = $booking_data['email'];
        $subject = 'Terminbestätigung - PAXdesign';
        
        $date_obj = DateTime::createFromFormat('Y-m-d', $booking_data['date']);
        $formatted_date = $date_obj->format('d.m.Y');
        
        $service_info = '';
        if (!empty($booking_data['service'])) {
            $services = $this->get_services();
            if (isset($services[$booking_data['service']])) {
                $service = $services[$booking_data['service']];
                $service_info = sprintf("Service: %s\n", $service['name']);
            }
        }
        
        $message = sprintf("
Hallo %s,

vielen Dank für Ihre Terminbuchung bei PAXdesign!

Ihr Termin wurde erfolgreich gebucht:

Ansprechpartner: %s
%sDatum: %s
Uhrzeit: %s

Wir freuen uns auf das Gespräch mit Ihnen!

Bei Fragen erreichen Sie uns unter:
Telefon: +43 681 20543638
E-Mail: info@paxdesign.at

Mit freundlichen Grüßen
Ihr PAXdesign Team

---
PAXdesign (PrimoJob GmbH)
Franzensbrückenstraße 14
1020 Wien
www.paxdesign.at
        ",
            $booking_data['name'],
            $member_info['name'],
            $service_info,
            $formatted_date,
            $booking_data['time']
        );
        
        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: PAXdesign <info@paxdesign.at>'
        );
        
        wp_mail($to, $subject, $message, $headers);
    }
    
    private function save_booking_to_database($booking_data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'paxdesign_bookings';
        
        $wpdb->insert(
            $table_name,
            array(
                'team_member' => $booking_data['member'],
                'service' => $booking_data['service'],
                'booking_date' => $booking_data['date'],
                'booking_time' => $booking_data['time'],
                'customer_name' => $booking_data['name'],
                'customer_email' => $booking_data['email'],
                'customer_phone' => $booking_data['phone'],
                'purpose' => $booking_data['purpose'],
                'message' => $booking_data['message'],
                'status' => 'pending',
                'created_at' => current_time('mysql')
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'PAXdesign Booking',
            'Booking System',
            'manage_options',
            'paxdesign-booking',
            array($this, 'render_admin_page'),
            'dashicons-calendar-alt',
            30
        );
        
        add_submenu_page(
            'paxdesign-booking',
            'Einstellungen',
            'Einstellungen',
            'manage_options',
            'paxdesign-booking-settings',
            array($this, 'render_settings_page')
        );
    }
    
    public function register_settings() {
        register_setting('paxdesign_booking_settings', 'paxdesign_booking_notification_email');
        register_setting('paxdesign_booking_settings', 'paxdesign_booking_email_adam');
        register_setting('paxdesign_booking_settings', 'paxdesign_booking_email_ahmad');
        register_setting('paxdesign_booking_settings', 'paxdesign_booking_email_bernt');
    }
    
    public function render_admin_page() {
        include PAXDESIGN_BOOKING_PLUGIN_DIR . 'templates/admin-page.php';
    }
    
    public function render_settings_page() {
        include PAXDESIGN_BOOKING_PLUGIN_DIR . 'templates/settings-page.php';
    }
}

function paxdesign_booking_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'paxdesign_bookings';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        team_member varchar(50) NOT NULL,
        service varchar(50),
        booking_date date NOT NULL,
        booking_time varchar(10) NOT NULL,
        customer_name varchar(255) NOT NULL,
        customer_email varchar(255) NOT NULL,
        customer_phone varchar(50),
        purpose varchar(100),
        message text,
        status varchar(20) DEFAULT 'pending',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
    add_option('paxdesign_booking_notification_email', 'info@paxdesign.at');
    add_option('paxdesign_booking_email_adam', 'info@paxdesign.at');
    add_option('paxdesign_booking_email_ahmad', 'info@paxdesign.at');
    add_option('paxdesign_booking_email_bernt', 'info@paxdesign.at');
}
register_activation_hook(__FILE__, 'paxdesign_booking_activate');

function paxdesign_booking_deactivate() {
    // Cleanup if needed
}
register_deactivation_hook(__FILE__, 'paxdesign_booking_deactivate');

function paxdesign_booking_init() {
    PAXdesign_Booking::get_instance();
}
add_action('plugins_loaded', 'paxdesign_booking_init');
