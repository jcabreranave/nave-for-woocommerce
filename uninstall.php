<?php
/**
 * Nave for WooCommerce — Uninstall.
 *
 * Limpia datos del plugin al desinstalar desde el admin de WordPress.
 * No se ejecuta al desactivar, solo al eliminar.
 *
 * @package NaveWC
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

// 1. Eliminar transients de tokens.
global $wpdb;
$wpdb->query(
    "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_nave_wc_token_%' OR option_name LIKE '_transient_timeout_nave_wc_token_%'"
);

// 2. Desregistrar cron.
$timestamp = wp_next_scheduled( 'nave_wc_polling_cron' );
if ( $timestamp ) {
    wp_unschedule_event( $timestamp, 'nave_wc_polling_cron' );
}

// 3. Limpiar locks residuales.
$wpdb->query(
    "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'nave_lock_%'"
);

// 4. Eliminar settings del gateway.
delete_option( 'woocommerce_nave_settings' );
