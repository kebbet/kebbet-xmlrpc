<?php
/**
 * Plugin Name:       Kebbet plugins - XML-RPC DDoS Protection
 * Plugin URI:        https://github.com/kebbet/kebbet-xmlrpc
 * Description:       Stops abuse of your site's XML-RPC by simply removing some methods used by attackers. While you can use the rest of XML-RPC methods.
 * Author:            Samuel Aguilera / Erik Betshammar
 * Author URI:        https://verkan.se
* Version:            20210519.01
 * Requires at least: 4.8
 * License:           GPL2
 *
 * @package kebbet-xmlrpc
 * @author Samuel Aguilera / Erik Betshammar
 */

/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2 as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace kebbet\xmlrpc;

/**
 * Disable XML-RPC.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Unset the pingback methods
 *
 * @param array $methods The XML-RPC methods.
 */
function block_xmlrpc_ddos( $methods ) {
	unset( $methods['pingback.ping'] );
	unset( $methods['pingback.extensions.getPingbacks'] );
	unset( $methods['wp.getUsersBlogs'] ); // New method used by attackers to perform brute force discovery of existing users.
	return $methods;
}
add_filter( 'xmlrpc_methods', __NAMESPACE__ . '\block_xmlrpc_ddos' );

/**
 * Unset the X-pingback header
 */
function remove_x_pingback() {
	header_remove( 'X-Pingback' );
}
add_action( 'wp', __NAMESPACE__ . '\remove_x_pingback', 9999 );
