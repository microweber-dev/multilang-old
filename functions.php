<?php

current_lang();

api_expose_admin('multilang_set_default');
function multilang_set_default() {
    $lang = app('request')->input('lang');
    if (strlen($lang) && in_array($lang, multilang_locales())){
        Config::set('app.fallback_locale', $lang);
        Config::save();
    }
}

api_expose_admin('multilang_add');
function multilang_add() {
    $lang = app('request')->input('lang');
    if ($lang){
        $langs = multilang_locales();
        if (!array_key_exists($lang, $langs)){
            $langs[] = $lang;
            save_option([
                'option_key'   => 'multilang_locales',
                'option_value' => implode(';', $langs),
                'option_group' => 'website'
            ]);
        }
    }
}

api_expose_admin('multilang_remove');
function multilang_remove() {
    // TODO: purge translations?
    $lang = app('request')->input('lang');
    if ($lang){
        $langs = multilang_locales();
        if (($key = array_search($lang, $langs))!==false){
            unset($langs[ $key ]);
            save_option([
                'option_key'   => 'multilang_locales',
                'option_value' => implode(';', $langs),
                'option_group' => 'website'
            ]);
        }
    }
}

api_expose('multilang_locales');
function multilang_locales() {
    $langs = get_option('multilang_locales', 'website');
    if ($langs){
        $langs = explode(';', $langs);
    }
    if (!is_array($langs) || !count($langs)){
        $langs = array(App::getLocale());
    }

    return $langs;
}


function multilang_admin_ui() {
    echo load_module('multilang/index_admin');
}

event_bind('mw.admin.before_toolbar', 'multilang_admin_ui');
//event_bind('module.content.manager.toolbar.search', 'multilang_admin_ui');
event_bind('module.content.manager.toolbar.end', 'multilang_admin_ui');
event_bind('mw_admin_edit_page_tabs_nav', 'multilang_admin_ui');


require_once 'Laravel/multilang.php';
require_once 'Laravel/Translator.php';
require_once 'Laravel/SqlParser.php';

event_bind('mw.database.select', function ($data) {
    app('mw.translator')->translate($data['query'], $data['result']);
});

event_bind('mw.database.before_update', function ($data) {
    if (app('mw.translator')->store($data['query'], $data['bindings'])){
        throw new \Exception('Intentional query execution cancelation.');
    }
});
