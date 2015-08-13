<?php

$lang = current_lang();

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


// UI
//
//event_bind('admin_head', 'multilang_admin_set_static_files');
//event_bind('site_header', 'multilang_admin_set_static_files');
//
//
//
//
//function multilang_admin_set_static_files() {
//    $url = module_url('multilang');
//
//    print '<script type="text/javascript" src="' . $url . 'langs.js"></script>';
//}
//

event_bind('mw.admin', 'multilang_admin_set_ui');
//event_bind('mw.live_edit', 'multilang_admin_set_ui');
event_bind('mw.front', 'multilang_admin_set_ui');


function multilang_admin_set_ui() {


    if (!get_option('is_multilang', 'website')){
        return;
    }

    $html = load_module('multilang/index_admin');
    $html_small = load_module('multilang/index_admin', array('display' => 'small'));
    $ui = array();
    $ui['html'] = $html;
    $ui['title'] = 'Language';
    $ui['class'] = 'mw-admin-content-edit-title-column-end';
    $ui['width'] = '110px';

    mw()->modules->ui('content.edit.title.end', $ui);

    $ui = array();
    $ui['html'] = $html_small;
    $ui['title'] = 'Language';

    mw()->modules->ui('content.manager.tree.after', $ui);
    mw()->modules->ui('live_edit.toolbar.action_menu.start', $ui);


    $url = module_url('multilang');

    mw()->template->head($url . 'langs.js');
    mw()->template->admin_head($url . 'langs.js');


    

     if (get_option('multilang_use_rtl', 'website')){
     
        $lang = current_lang();
    
        $rtl = array('ar', 'he', 'ur'); //make a list of rtl languages
        $textdir = 'ltr';
        if (in_array($lang, $rtl)){ //is this a rtl language?
            $textdir = 'rtl'; //switch the direction
        }
     
        mw()->template->html_opening_tag('dir', $textdir);
        mw()->template->html_opening_tag('lang', $lang);
        mw()->template->meta('language', multilang_locale_name($lang));
     }





}

function multilang_admin_ui() {
    echo load_module('multilang/index_admin');
}

//event_bind('mw.admin.before_toolbar', 'multilang_admin_ui');
//event_bind('module.content.manager.toolbar.search', 'multilang_admin_ui');
//event_bind('module.content.manager.toolbar.end', 'multilang_admin_ui');
//event_bind('mw_admin_edit_page_tabs_nav', 'multilang_admin_ui'


//Locales array

function multilang_locale_name($key) {
    $list = multilang_locales_list();
    if (isset($list[ $key ])){
        return $list[ $key ];
    } else {
        return $key;
    }
}

function multilang_locales_list() {
    $result = array(
        "af"      => "Afrikaans",
        "ak"      => "Akan",
        "sq"      => "Albanian",
        "am"      => "Amharic",
        "ar"      => "Arabic",
        "hy"      => "Armenian",
        "as"      => "Assamese",
        "asa"     => "Asu",
        "az"      => "Azerbaijani",
        "bm"      => "Bambara",
        "eu"      => "Basque",
        "be"      => "Belarusian",
        "bem"     => "Bemba",
        "bez"     => "Bena",
        "bn"      => "Bengali",
        "bs"      => "Bosnian",
        "bg"      => "Bulgarian",
        "my"      => "Burmese",
        "ca"      => "Catalan",
        "tzm"     => "Central Morocco Tamazight",
        "chr"     => "Cherokee",
        "cgg"     => "Chiga",
        "zh_Hans" => "Chinese (Simplified Han)",
        "zh_Hant" => "Chinese (Traditional Han)",
        "zh"      => "Chinese",
        "kw"      => "Cornish",
        "hr"      => "Croatian",
        "cs"      => "Czech",
        "da"      => "Danish",
        "nl"      => "Dutch",
        "ebu"     => "Embu",
        "en"      => "English",
        "eo"      => "Esperanto",
        "et"      => "Estonian",
        "ee"      => "Ewe",
        "fo"      => "Faroese",
        "fil"     => "Filipino",
        "fi"      => "Finnish",
        "fr"      => "French",
        "ff"      => "Fulah",
        "gl"      => "Galician",
        "lg"      => "Ganda",
        "ka"      => "Georgian",
        "de"      => "German",
        "el"      => "Greek",
        "gu"      => "Gujarati",
        "guz"     => "Gusii",
        "ha"      => "Hausa",
        "haw"     => "Hawaiian",
        "he"      => "Hebrew",
        "hi"      => "Hindi",
        "hu"      => "Hungarian",
        "is"      => "Icelandic",
        "ig"      => "Igbo",
        "id"      => "Indonesian",
        "ga"      => "Irish",
        "it"      => "Italian",
        "ja"      => "Japanese",
        "kea"     => "Kabuverdianu",
        "kab"     => "Kabyle",
        "kl"      => "Kalaallisut",
        "kln"     => "Kalenjin",
        "kam"     => "Kamba",
        "kn"      => "Kannada",
        "kk"      => "Kazakh",
        "km"      => "Khmer",
        "ki"      => "Kikuyu",
        "rw"      => "Kinyarwanda",
        "kok"     => "Konkani",
        "ko"      => "Korean",
        "khq"     => "Koyra Chiini",
        "ses"     => "Koyraboro Senni",
        "lag"     => "Langi",
        "lv"      => "Latvian",
        "lt"      => "Lithuanian",
        "luo"     => "Luo",
        "luy"     => "Luyia",
        "mk"      => "Macedonian",
        "jmc"     => "Machame",
        "kde"     => "Makonde",
        "mg"      => "Malagasy",
        "ms"      => "Malay",
        "ml"      => "Malayalam",
        "mt"      => "Maltese",
        "gv"      => "Manx",
        "mr"      => "Marathi",
        "mas"     => "Masai",
        "mer"     => "Meru",
        "mfe"     => "Morisyen",
        "naq"     => "Nama",
        "ne"      => "Nepali",
        "nd"      => "North Ndebele",
        "nb"      => "Norwegian Bokmål",
        "nn"      => "Norwegian Nynorsk",
        "nyn"     => "Nyankole",
        "or"      => "Oriya",
        "om"      => "Oromo",
        "ps"      => "Pashto",
        "fa"      => "Persian",
        "pl"      => "Polish",
        "pt"      => "Portuguese",
        "pa"      => "Punjabi",
        "ro"      => "Romanian",
        "rm"      => "Romansh",
        "rof"     => "Rombo",
        "ru"      => "Russian",
        "rwk"     => "Rwa",
        "saq"     => "Samburu",
        "sg"      => "Sango",
        "seh"     => "Sena",
        "sr"      => "Serbian",
        "sn"      => "Shona",
        "ii"      => "Sichuan Yi",
        "si"      => "Sinhala",
        "sk"      => "Slovak",
        "sl"      => "Slovenian",
        "xog"     => "Soga",
        "so"      => "Somali",
        "es"      => "Spanish",
        "sw"      => "Swahili",
        "sv"      => "Swedish",
        "gsw"     => "Swiss German",
        "shi"     => "Tachelhit",
        "dav"     => "Taita",
        "ta"      => "Tamil",
        "te"      => "Telugu",
        "teo"     => "Teso",
        "th"      => "Thai",
        "bo"      => "Tibetan",
        "ti"      => "Tigrinya",
        "to"      => "Tonga",
        "tr"      => "Turkish",
        "uk"      => "Ukrainian",
        "ur"      => "Urdu",
        "uz"      => "Uzbek",
        "vi"      => "Vietnamese",
        "vun"     => "Vunjo",
        "cy"      => "Welsh",
        "yo"      => "Yoruba",
        "zu"      => "Zulu"

    );

    return $result;
}