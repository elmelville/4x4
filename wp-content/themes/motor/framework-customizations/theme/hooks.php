<?php
/**
 * @param FW_Ext_Backups_Demo[] $demos
 * @return FW_Ext_Backups_Demo[]
 */
function motor_backups_demos($demos) {
    $demos_array = array(
        'motor-demo-id' => array(
            'title' => esc_html__('Motor Demo 1', 'motor'),
            'screenshot' => 'http://real-web.pro/motor/screenshot.png',
            'preview_link' => 'http://motor.stockware.pro',
        ),
        /*'motor2-demo-id' => array(
            'title' => esc_html__('Motor Demo 2', 'motor'),
            'screenshot' => 'http://real-web.pro/motor/screenshot.png',
            'preview_link' => 'http://motor2.stockware.pro',
        ),*/
    );

    $download_url = 'http://real-web.pro/motor/index.php';

    foreach ($demos_array as $id => $data) {
        $demo = new FW_Ext_Backups_Demo($id, 'piecemeal', array(
            'url' => $download_url,
            'file_id' => $id,
        ));
        $demo->set_title($data['title']);
        $demo->set_screenshot($data['screenshot']);
        $demo->set_preview_link($data['preview_link']);

        $demos[ $demo->get_id() ] = $demo;

        unset($demo);
    }

    return $demos;
}
add_filter('fw:ext:backups-demo:demos', 'motor_backups_demos');