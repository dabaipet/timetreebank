<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'think\\worker\\' => array($vendorDir . '/topthink/think-worker/src'),
    'think\\mongo\\' => array($vendorDir . '/topthink/think-mongo/src'),
    'think\\migration\\' => array($vendorDir . '/topthink/think-migration/src'),
    'think\\helper\\' => array($vendorDir . '/topthink/think-helper/src'),
    'think\\composer\\' => array($vendorDir . '/topthink/think-installer/src'),
    'think\\captcha\\' => array($vendorDir . '/topthink/think-captcha/src'),
    'think\\' => array($vendorDir . '/topthink/think-image/src'),
    'app\\' => array($baseDir . '/application'),
    'Workerman\\' => array($vendorDir . '/workerman/workerman'),
    'Qiniu\\' => array($vendorDir . '/qiniu/php-sdk/src/Qiniu'),
    'Qcloud\\Sms\\' => array($vendorDir . '/qcloudsms/qcloudsms_php/src'),
    'Phinx\\' => array($vendorDir . '/topthink/think-migration/phinx/src/Phinx'),
    'GatewayWorker\\' => array($vendorDir . '/workerman/gateway-worker/src'),
);
