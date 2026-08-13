<?php
/**
 * WPCode snippet: "Calendar chips: patient + practitioner + time" (snippet #203)
 * Site: mindtherapyny.wpcomstaging.com
 * Admin-only enhancement for Booking Calendar (free) admin screens:
 * rewrites the calendar/timeline chip labels from "ID: LastName" to
 * "ID: Patient | Practitioner | Time", with a full-detail hover tooltip.
 * Data comes from a small authenticated admin-ajax endpoint.
 */

add_action( 'wp_ajax_mt_booking_meta', function () {
    if ( ! current_user_can( 'edit_pages' ) ) { wp_send_json( array() ); }
    global $wpdb;
    $rows = $wpdb->get_results( "SELECT booking_id, form FROM {$wpdb->prefix}booking WHERE trash != 1" );
    $out = array();
    foreach ( $rows as $b ) {
        $f = array();
        foreach ( explode( '~', (string) $b->form ) as $c ) {
            $p = explode( '^', $c );
            if ( count( $p ) >= 3 ) { $f[ preg_replace( '/\d+$/', '', $p[1] ) ] = $p[2]; }
        }
        $time = isset( $f['rangetime'] ) ? $f['rangetime'] : '';
        if ( $time ) {
            $parts = explode( ' - ', $time );
            if ( count( $parts ) === 2 ) {
                $t1 = strtotime( '2000-01-01 ' . trim( $parts[0] ) );
                $t2 = strtotime( '2000-01-01 ' . trim( $parts[1] ) );
                if ( $t1 && $t2 ) { $time = date( 'g:ia', $t1 ) . '-' . date( 'g:ia', $t2 ); }
            }
        }
        $client = trim( ( isset( $f['firstname'] ) ? $f['firstname'] : '' ) . ' ' . ( isset( $f['secondname'] ) ? $f['secondname'] : '' ) );
        $prac = isset( $f['therapist'] ) ? $f['therapist'] : '';
        $out[ $b->booking_id ] = array( 'c' => $client, 'p' => $prac, 't' => $time );
    }
    wp_send_json( $out );
} );

add_action( 'admin_print_footer_scripts', function () {
    if ( empty( $_GET['page'] ) || strpos( (string) $_GET['page'], 'wpbc' ) !== 0 ) { return; }
    echo <<<'MTJS'
<script>
(function () {
    var map = null;
    fetch( ajaxurl + '?action=mt_booking_meta', { credentials: 'same-origin' } )
        .then( function (r) { return r.json(); } )
        .then( function (m) { map = m; decorate(); setInterval( decorate, 1200 ); } );
    function decorate() {
        if ( ! map ) { return; }
        var els = document.querySelectorAll( 'a.in_cell_date_booking_title:not([data-mt-done])' );
        Array.prototype.forEach.call( els, function (el) {
            var m2 = ( el.textContent || '' ).match( /^\s*(\d+):\s*(.*)$/ );
            if ( ! m2 ) { return; }
            var id = m2[1], meta = map[ id ];
            if ( ! meta ) { return; }
            var bits = [ meta.c || m2[2] ];
            if ( meta.p ) { bits.push( meta.p ); }
            if ( meta.t ) { bits.push( meta.t ); }
            el.textContent = id + ': ' + bits.join( ' | ' );
            el.setAttribute( 'data-mt-done', '1' );
            el.title = 'Booking #' + id + ( meta.c ? ' | Patient: ' + meta.c : '' ) + ( meta.p ? ' | Practitioner: ' + meta.p : '' ) + ( meta.t ? ' | Time: ' + meta.t : '' );
        } );
    }
})();
</script>
MTJS;
} );
