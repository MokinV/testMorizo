<?php
/**
 * Загрузка товаров из файла в инфоблок
 */

@ini_set('mbstring.func_overload', '2');
@ini_set('mbstring.internal_encoding', 'UTF-8');
@ini_set('memory_limit', '500M');

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define('CHK_EVENT', true);
define('BX_NO_ACCELERATOR_RESET', true);

$exit_code = 0;

try {
    $is_console = PHP_SAPI === 'cli' || (!isset($_SERVER['DOCUMENT_ROOT']) && !isset($_SERVER['REQUEST_URI']));

    if ($is_console) {
        $_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/../../';
    }

    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

    @set_time_limit(0);
    @ignore_user_abort(true);
    @error_reporting(0);

    $import = new \Lib\Xml\Import();
    $import->load();
} catch (\Exception $e) {
    $exit_code = 1;
}

if ($exit_code > 0) {
    http_response_code(500);
}

exit ($exit_code);

