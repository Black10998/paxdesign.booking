<?php
if (!defined('ABSPATH')) exit;
?>

<div class="wrap">
    <h1>Booking System Einstellungen</h1>
    
    <form method="post" action="options.php">
        <?php settings_fields('paxdesign_booking_settings'); ?>
        <?php do_settings_sections('paxdesign_booking_settings'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="paxdesign_booking_notification_email">Benachrichtigungs-E-Mail</label>
                </th>
                <td>
                    <input type="email" 
                           id="paxdesign_booking_notification_email" 
                           name="paxdesign_booking_notification_email" 
                           value="<?php echo esc_attr(get_option('paxdesign_booking_notification_email', 'info@paxdesign.at')); ?>" 
                           class="regular-text">
                    <p class="description">E-Mail-Adresse für Buchungsbenachrichtigungen</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="paxdesign_booking_email_adam">Adam's E-Mail</label>
                </th>
                <td>
                    <input type="email" 
                           id="paxdesign_booking_email_adam" 
                           name="paxdesign_booking_email_adam" 
                           value="<?php echo esc_attr(get_option('paxdesign_booking_email_adam', 'info@paxdesign.at')); ?>" 
                           class="regular-text">
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="paxdesign_booking_email_ahmad">Ahmad's E-Mail</label>
                </th>
                <td>
                    <input type="email" 
                           id="paxdesign_booking_email_ahmad" 
                           name="paxdesign_booking_email_ahmad" 
                           value="<?php echo esc_attr(get_option('paxdesign_booking_email_ahmad', 'info@paxdesign.at')); ?>" 
                           class="regular-text">
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="paxdesign_booking_email_bernt">Bernt's E-Mail</label>
                </th>
                <td>
                    <input type="email" 
                           id="paxdesign_booking_email_bernt" 
                           name="paxdesign_booking_email_bernt" 
                           value="<?php echo esc_attr(get_option('paxdesign_booking_email_bernt', 'info@paxdesign.at')); ?>" 
                           class="regular-text">
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
</div>
