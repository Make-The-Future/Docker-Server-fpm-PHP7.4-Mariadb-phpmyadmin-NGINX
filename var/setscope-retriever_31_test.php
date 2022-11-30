<?php
$periodXpathString = '//*[@id="maincontent"]/div/div[4]/table/thead[1]/tr/th/strong';
$xpath = retrieveSuperQuickXpathFromUrl("https://www.set.or.th/set/companyhighlight.do?symbol=AOT&language=en&country=US");
// $periodXpathString = '//*[@id="maincontent"]/div/div[4]/table/thead[1]/tr/th[2]/strong';

$dataNodes = parseValue($xpath, $periodXpathString);
buildKeyValueData($dataNodes, 'period', false);
$dataArray = buildKeyValueData($dataNodes, 'period', false);
echo "<br/>";
$retrieverData = array();
$retrieverData = array_merge($retrieverData, $dataArray);
print_r($retrieverData);
function parseValue($xpath, $xpathString)
{
    $dataNodes = $xpath->query($xpathString);
    return $dataNodes;
}
function retrieveSuperQuickXpathFromUrl($urlString)
{
    //      set_time_limit(0);
    // $oldSetting = libxml_use_internal_errors( true );
    // libxml_clear_errors();
    //$dirty_html = file_get_contents($urlString);

    // $proxy = '103.253.73.102:808';
    // $proxyAuthen = 'user_deep:Deepscope@2019';

    $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
    $ch = curl_init();
    //curl_setopt($ch, CURLOPT_URL, $urlString);$curl = curl_init();

    echo "url >> " . $urlString;

    curl_setopt_array($ch, array(
        CURLOPT_URL => $urlString,
        // CURLOPT_PROXY => $proxy,
        // CURLOPT_PROXYUSERPWD => $proxyAuthen,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Postman-Token:  2b42cd5f-80c6-40ca-967f-bf370f6e569a,2aa79675-d335-4f64-8c5a-a381b216a2d4"
        ),
    ));
    /*curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);*/

    //curl_setopt($ch, CURLOPT_HTTPHEADER, array('text/plain;charset=iso-8859-1'));
    //curl_setopt($ch, CURLOPT_ENCODING, 'windows-874');
    $dirty_html = curl_exec($ch);
    curl_close($ch);


    //$clean_html = $this->purifierSuperQuick->purify($dirty_html);

    $clean_html = $dirty_html;
    //$clean_html = iconv("tis620","UTF-8",$dirty_html);
    //echo "kata".$clean_html."kata";
    // Save to file for future verification
    // $file = './k.html';
    // file_put_contents($file, $clean_html);

    libxml_use_internal_errors(true);
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHtml($clean_html);
    $xpath = new DOMXPath($dom);

    return $xpath;
}
function buildKeyValueData($dataNodes, $key, $verifyNumeric = true, $isFinanStatementReleased = true)
{
    $dataArray = array();
    $count = 0;

    $n;
    if ($isFinanStatementReleased) {
        $n = $dataNodes->length - 1;
    } else {
        $n = $dataNodes->length - 2;
    }

    for (; $n > 0; --$n) {
        $dataNode = $dataNodes->item($n);
        $data = htmlTrim($dataNode->nodeValue);

        // Strip comma from money values
        if ($verifyNumeric) {
            $data = str_replace(",", "", $data);
        }

        if (((!$verifyNumeric) || is_numeric($data)) && (!isNullOrEmptyString($data))) {
            $dataArray[$key . '_' . $count] = $data;
        }
        $count++;
    }
    return $dataArray;
}

function htmlTrim($string)
{
    return trim(utf8_decode($string), chr(0xC2) . chr(0xA0));
}
function isNullOrEmptyString($question)
{
    return (!isset($question) || trim($question) === '');
}