<?php


namespace Multilanguage;


use Closure;
use Microweber\Utils\Adapters\Cache\Storage\FileStorage;

class MultilanguageFileCacheDriver extends FileStorage {
    public function __construct($prefix = '') {
        parent::__construct();
    }

    public function appendLocale($key) {
        return $key . '_' . app()->getLocale();;
    }
}