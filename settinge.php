<?php

if ($hassiteconfig) { // needs this condition or there is error on login page

    $ADMIN->add('localplugins', new admin_category('local_news_category', get_string('pluginname', 'local_news')));

    $settings = new admin_settingpage('local_news', get_string('pluginname', 'local_news'));
    $ADMIN->add('local_news_category', $settings);

    $settings->add(new admin_setting_configcheckbox('local_news/enabled',
        get_string('setting_enable', 'local_news'), get_string('setting_enable_desc', 'local_news'), '1'));

//    $ADMIN->add('local_message_category', new admin_externalpage('local_news_manage', get_string('manage', 'local_news'),
//        $CFG->wwwroot . '/local/news/manage.php'));
}
