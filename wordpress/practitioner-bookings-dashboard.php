<?php
/**
 * WPCode snippet: "Practitioner bookings dashboard shortcode" (snippet #201)
 * Site: mindtherapyny.wpcomstaging.com
 * Renders [mt_practitioner_bookings] — a Wix-Work-Schedule-style week grid:
 * practitioners as rows, days as columns, booking chips (time + client) in cells.
 * Week navigation via ?wk=YYYY-MM-DD. Staff-only (edit_pages capability).
 * Used on the private page /practitioner-bookings/.
 */

add_shortcode( 'mt_practitioner_bookings', function () {
    if ( ! is_user_logged_in() || ! current_user_can( 'edit_pages' ) ) {
        return '<p>This dashboard is visible to Mind Therapy staff only. Please log in.</p>';
    }
    global $wpdb;
    $tz = function_exists( 'wp_timezone' ) ? wp_timezone() : new DateTimeZone( 'America/New_York' );
    $wk = isset( $_GET['wk'] ) ? sanitize_text_field( wp_unslash( $_GET['wk'] ) ) : '';
    $base = DateTime::createFromFormat( 'Y-m-d', $wk, $tz );
    if ( ! $base ) { $base = new DateTime( 'now', $tz ); }
    $start = clone $base;
    $start->modify( '-' . intval( $start->format( 'w' ) ) . ' days' );
    $days = array();
    for ( $i = 0; $i < 7; $i++ ) { $d = clone $start; $d->modify( '+' . $i . ' days' ); $days[] = $d; }
    $end = end( $days );
    $today = ( new DateTime( 'now', $tz ) )->format( 'Y-m-d' );

    $bookings = $wpdb->get_results( "SELECT booking_id, form FROM {$wpdb->prefix}booking WHERE trash != 1" );
    $dates = $wpdb->get_results( "SELECT booking_id, booking_date, approved FROM {$wpdb->prefix}bookingdates ORDER BY booking_date ASC" );
    $dmap = array();
    foreach ( $dates as $d ) { if ( ! isset( $dmap[ $d->booking_id ] ) ) { $dmap[ $d->booking_id ] = $d; } }

    $roster = array( 'P01 Ally Nektalov', 'P02 Kameeka Burke', 'P03 Elizabeth Han', 'P04 Bryonna Perperian', 'P05 Abigail Harrison', 'P06 Tamlyn Freedman', 'P07 Constance Grey', 'P08 Barbara Hoffmann', 'P09 Sophia Alan', 'P10 Maria Cicchilli', 'P11 Sarah Kitt', 'P12 Bianca E. Ortiz', 'P13 Alessandra Scalia', 'P00 No preference' );
    $grid = array();
    foreach ( $roster as $r ) { $grid[ $r ] = array(); }

    foreach ( $bookings as $b ) {
        $f = array();
        foreach ( explode( '~', (string) $b->form ) as $chunk ) {
            $p = explode( '^', $chunk );
            if ( count( $p ) >= 3 ) { $f[ rtrim( $p[1], '0123456789' ) ] = $p[2]; }
        }
        $who = isset( $f['therapist'] ) ? trim( $f['therapist'] ) : '';
        $key = 'P00 No preference';
        if ( $who !== '' && $who !== 'No preference' ) {
            foreach ( $roster as $r ) { if ( $r === $who || false !== strpos( $r, $who ) ) { $key = $r; break; } }
            if ( 'P00 No preference' === $key ) { $key = $who; if ( ! isset( $grid[ $key ] ) ) { $grid[ $key ] = array(); } }
        }
        if ( ! isset( $dmap[ $b->booking_id ] ) ) { continue; }
        $day = substr( $dmap[ $b->booking_id ]->booking_date, 0, 10 );
        $time = isset( $f['rangetime'] ) ? $f['rangetime'] : '';
        if ( $time ) {
            $parts = explode( ' - ', $time );
            if ( count( $parts ) === 2 ) {
                $t1 = strtotime( '2000-01-01 ' . trim( $parts[0] ) );
                $t2 = strtotime( '2000-01-01 ' . trim( $parts[1] ) );
                if ( $t1 && $t2 ) { $time = date( 'g:i A', $t1 ) . ' - ' . date( 'g:i A', $t2 ); }
            }
        }
        $client = trim( ( isset( $f['firstname'] ) ? $f['firstname'] : '' ) . ' ' . ( isset( $f['secondname'] ) ? $f['secondname'] : '' ) );
        $grid[ $key ][ $day ][] = array( 'id' => $b->booking_id, 't' => $time, 'c' => $client, 'ok' => intval( $dmap[ $b->booking_id ]->approved ) === 1 );
    }

    $url = get_permalink();
    $prev = esc_url( add_query_arg( 'wk', ( clone $start )->modify( '-7 days' )->format( 'Y-m-d' ), $url ) );
    $next = esc_url( add_query_arg( 'wk', ( clone $start )->modify( '+7 days' )->format( 'Y-m-d' ), $url ) );
    $curr = esc_url( $url );

    $o = '<style>
.mtws{background:#fff;border:1px solid #e3e3e3;border-radius:14px;padding:22px 22px 8px;overflow-x:auto;font-size:14px;width:min(1180px,94vw);max-width:none !important;margin-left:auto !important;margin-right:auto !important}
.mtws-nav{display:flex;align-items:center;gap:14px;margin-bottom:16px;flex-wrap:wrap}
.mtws-nav a{display:inline-block;padding:7px 16px;border:1px solid #d5d5d5;border-radius:8px;text-decoration:none;color:#333;background:#fff;font-weight:600}
.mtws-nav .mtws-range{font-weight:700;font-size:1.05rem;color:#222;border:1px solid #e0e0e0;border-radius:8px;padding:7px 18px}
.mtws table{width:100%;border-collapse:collapse;min-width:860px}
.mtws th,.mtws td{border:1px solid #ececec;vertical-align:top}
.mtws th{padding:10px 6px;text-align:center;font-weight:600;color:#666;background:#fafafa}
.mtws th .dnum{display:block;font-size:1.25rem;color:#222;font-weight:700}
.mtws th.mtws-today{border-top:3px solid #3b78e7;background:#f2f7ff}
.mtws td{padding:6px;min-width:104px;height:56px}
.mtws td.mtws-name{min-width:170px;font-weight:700;color:#222;padding:12px 10px;background:#fff}
.mtws td.mtws-name small{display:block;font-weight:400;color:#999}
.mtws .chip{display:block;background:#e9f1fe;color:#1e3a5f;border-radius:7px;padding:5px 8px;margin:0 0 5px;border-left:4px solid #e7a94b;line-height:1.35}
.mtws .chip.ok{border-left-color:#4f9d69}
.mtws .chip small{display:block;color:#51678a}
.mtws-legend{color:#888;font-size:.85rem;padding:10px 2px 12px}
</style>';
    $o .= '<div class="mtws">';
    $o .= '<div class="mtws-nav"><a href="' . $curr . '">Today</a><a href="' . $prev . '">&lsaquo;</a><a href="' . $next . '">&rsaquo;</a><span class="mtws-range">' . esc_html( $start->format( 'M j, Y' ) . ' - ' . $end->format( 'M j, Y' ) ) . '</span></div>';
    $o .= '<table><tr><th style="text-align:left;padding-left:10px">Staff member</th>';
    foreach ( $days as $d ) {
        $cls = $d->format( 'Y-m-d' ) === $today ? ' class="mtws-today"' : '';
        $o .= '<th' . $cls . '>' . esc_html( $d->format( 'D' ) ) . '<span class="dnum">' . esc_html( $d->format( 'j' ) ) . '</span></th>';
    }
    $o .= '</tr>';
    foreach ( $grid as $who => $bydate ) {
        $pid = ''; $pname = $who;
        if ( preg_match( '/^(P[0-9]{2}) (.+)$/', $who, $mm ) ) { $pid = $mm[1]; $pname = $mm[2]; }
        $o .= '<tr><td class="mtws-name">' . esc_html( $pname ) . ( $pid ? '<small>' . esc_html( $pid ) . '</small>' : '' ) . '</td>';
        foreach ( $days as $d ) {
            $k = $d->format( 'Y-m-d' );
            $o .= '<td>';
            if ( isset( $bydate[ $k ] ) ) {
                foreach ( $bydate[ $k ] as $bk ) {
                    $o .= '<span class="chip' . ( $bk['ok'] ? ' ok' : '' ) . '" title="Booking #' . intval( $bk['id'] ) . '">' . esc_html( $bk['t'] ? $bk['t'] : 'time TBD' ) . '<small>' . esc_html( $bk['c'] ) . '</small></span>';
                }
            }
            $o .= '</td>';
        }
        $o .= '</tr>';
    }
    $o .= '</table><div class="mtws-legend">Chip stripe: green = approved, gold = pending. Empty row = open availability that week. Hover a chip for its booking ID.</div></div>';
    return $o;
} );
