<?php

use Jfcherng\IpLocation\IpLocation;

include __DIR__ . '/vendor/autoload.php';

$ipFinder = IpLocation::getInstance();

// 如果不想要使用內建的 IP 資料庫，請進行以下設定
$ipFinder->setup([
    // ipip 資料庫的路徑
    'ipipDb' => __DIR__ . '/src/db/ipipfree.ipdb',
    // cz88 資料庫的路徑
    'cz88Db' => __DIR__ . '/src/db/qqwry.ipdb',
]);

$ip = '202.113.245.255';

$results = $ipFinder->find($ip);

\var_dump($results);
/*
array(5) {
  ["country_name"]=>
  string(6) "中国"
  ["region_name"]=>
  string(6) "天津"
  ["city_name"]=>
  string(6) "天津"
  ["owner_domain"]=>
  string(0) ""
  ["isp_domain"]=>
  string(9) "教育网"
}
*/
