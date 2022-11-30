<?php

error_reporting(E_ALL & ~E_NOTICE);
require_once  'PDO.class.php';
require_once  'trader.market.logic.php';
// $db2 = new pdo\DB('mariadb', 'admin_setscoped', 'admin_setscopeu', 'firstlife142');
$db = new DB('18.138.178.103', 3360, 'admin_setscoped', 'admin_setscopeu', 'firstlife142');
$tradeDateRow = $db->query("
SELECT trade_date 
FROM  fact_stock_history
where  is_odd_lot LIKE  'N'
AND  security_type LIKE  'S'
order by trade_date desc
LIMIT 1
");

$recentTradeDate = $tradeDateRow[0]["trade_date"];
$stockSymbolRows = $db->query("
SELECT security_symbol,security_type
FROM  fact_stock_history
WHERE  trade_date =  '" . $recentTradeDate . "'
AND  is_odd_lot LIKE  'N'
order by security_symbol
");

$allStocksDataArray = array();

$stockCount = 0;
$retrieveDate = date('Y-m-d H:i:s');
foreach ($stockSymbolRows as $stockSymbolRow) {
	$stock = $stockSymbolRow["security_symbol"];
	// print_r($stockSymbolRow);
	$stockCount++;

	$retrieverData = array();

	// stock
	$retrieverData['stock']  = $stock;
	// url

	// retrieve date
	$retrieverData['retrieve_date'] = $retrieveDate;

	$retrieverData['security_type'] = $stockSymbolRow["security_type"];

	if ($stockSymbolRow["security_type"] != 'S' && $stockSymbolRow["security_type"] != 'F') {
		$retrieverData['action'] = 'N/A';
		$retrieverData['action_detail'] = 'N/A';
	}


	array_push($allStocksDataArray, $retrieverData);
}
print_r(implode(",", array_keys($allStocksDataArray[0])));
print_r(implode(",", array_values($allStocksDataArray[0])));
$sql = "TRUNCATE TABLE fact_retriever_buffer_new";
$db->query($sql);

foreach ($allStocksDataArray as $oneStockData) {
	$db->insert(
		`fact_retriever_buffer_new`,
		$oneStockData
	);
}


// echo "Done retrieving - symbols - ".count($allStocksDataArray);

$db->CloseConnection();
// $db2->CloseConnection();
