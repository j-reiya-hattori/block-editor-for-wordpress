<?php
/*
    Plugin Name: Jet Blocks
    Description: プロックエディタ用のパーツです
    Version: 2.0.0
    Plugin URI: https://jetb.co.jp
    Author: JetB株式会社
    Author URI: https://jetb.co.jp
    Update URI: jet-blocks
*/

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * デバック用
 */
// Latest Releaseの情報を取得するためのエンドポイント
// https://api.github.com/repos/j-reiya-hattori/block-editor-for-wordpress/releases/latest
define( 'MY_PLUGIN_UPDATE_URL', 'https://api.github.com/repos/j-reiya-hattori/block-editor-for-wordpress/releases/latest' );

function plugin_update_func( $update, $plugin_data ) {
    // GitHub APIを使って、Releaseの最新バージョン情報を取得する
    $response = wp_remote_get( MY_PLUGIN_UPDATE_URL );

    // レスポンスエラー
    if( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
        return $update;
    }

    // 最新バージョン、zipファイルパッケージのURLを取得
    $response_body = json_decode( wp_remote_retrieve_body( $response ), true );
    $new_version   = isset( $response_body['tag_name'] ) ? $response_body['tag_name'] : null;
    $package       = isset( $response_body['assets'][0]['browser_download_url'] ) ? $response_body['assets'][0]['browser_download_url'] : null;

    return array(
        'version'     => $plugin_data['Version'], // 現在のバージョン
        'new_version' => $new_version,            // 最新のバージョン
        'package'     => $package,                // zipファイルパッケージのURL
        'url'         => 'https://jetb.co.jp',
    );
}
add_filter( 'update_plugins_jet-blocks', 'plugin_update_func', 10, 2 );




/**
 * メニューバーに項目を追加
 */
add_action( 'admin_menu', 'register_menu_page' );
function register_menu_page(){
    add_menu_page( 'Jet Blocks', 'Jet Blocks',
    'manage_options', 'custompage', 'menu_page', '', 6 ); 
}

function menu_page(){
    $version = '2.0.0';
    echo "<h2>Jet Blocks設定</h2>";
    echo "<p>version: {$version}</p>";
    echo "<p>現在のバージョンは<b>{$version}</b>です</p>";
    echo "<h3>更新情報</h3>";
    echo "<p>2022年7月15日：　セキュリティ強化しました</p>";
}