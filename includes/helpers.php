<?php

/*
 * Retrieves the current page url
 *
 * @return string
*/
if ( ! function_exists( 'vln_get_current_url' ) ) {
    function vln_get_current_url() {
        global $wp;

        return home_url( $wp->request );
    }
}

/*
 * Extracts a list of urls contained in a string
 * and separated by new line.
 *
 * @param string $str
 * @return array $urls
 */
if ( ! function_exists( 'vln_extract_urls_from_string' ) ) {
    function vln_extract_urls_from_string( $str ) {
        $urls = array_filter( explode( "\r\n", $str ) );

        if ( ! empty( $urls ) ) {
            foreach ( $urls as $k => $url ) {
                $urls[ $k ] = rtrim( $url, '/\\' );
            }
        }

        return $urls;
    }
}
