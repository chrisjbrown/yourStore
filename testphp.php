<?php
include 'store.php';
include 'util.php';

libxml_use_internal_errors(TRUE);
 
function get_url_contents($url, $timeout = 10, $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:18.0) Gecko/20100101 Firefox/18.0'){
    
    $rawhtml = curl_init();  //create our handler
    curl_setopt ($rawhtml, CURLOPT_URL,$url);  //set the url
    curl_setopt ($rawhtml, CURLOPT_RETURNTRANSFER, 1);  //return result as string rather than direct output
    curl_setopt ($rawhtml, CURLOPT_CONNECTTIMEOUT, $timeout);  //set the timeout
    curl_setopt ($rawhtml, CURLOPT_USERAGENT, $userAgent);  //set our 'user agent'
    $output = curl_exec($rawhtml);  //execute the curl call
    curl_close($rawhtml);  //close our connection
    if (!$output) {
        return -1;  //if nothing was obtained, return '-1'
    }
    return $output;
}

//get stores from jsp
$stores = $_POST['stores'];
$apparel = $_POST['apparel'];

$storeLinks = array();
foreach ($stores as $str){
    $obj = new store();
    foreach($apparel as $apprl){
        $storeLinks[] = $obj->get_link($str, $apprl);
    }
}

foreach ($storeLinks as $link) {
    //get raw html
    $html_string = get_url_contents($link);
 
    //load html into our DOM object
    //ref: http://www.php.net/manual/en/domdocument.loadhtml.php
    //note: html does not have to be well formed with this function
    $dom_object = new DOMDocument();
    $dom_object->loadHTML($html_string);
     
    //now let's peform some xpath queries on our DOM
    //ref: http://www.php.net/manual/en/domxpath.query.php
    $xpath = new DOMXPath($dom_object);
     
    //STEP 1: get a links
    $aLinks = array();
    $vRes = $xpath->query('.//td[@class="arrayImg"]/a');
    foreach ($vRes as $obj) {
      $aLinks[] = $obj->getAttribute('href');
    }

    //STEP 2: get img src & alt
    $sLinks = array();
    $altLinks = array();
    $vRes = $xpath->query('.//td[@class="arrayImg"]//a/img');
    foreach ($vRes as $obj) {
      $sLinks[] = $obj->getAttribute('src');
      $altLinks[] = $obj->getAttribute('alt');
    }

    echo '<div id="prodArray">';
    echo '<table width="768" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff">';
    echo '<tr><td class="arrayProdCell" align="left" valign="top" style="padding-left:0px;padding-right:9px">';
    echo '<table border="0" cellpadding="0" cellspacing="0" width="250"><tr>';

    $i = 0;
    foreach ($aLinks as $a){
        echo <<<EOF
            <td><a href="{$a}" border="0">
            <img src="{$sLinks[$i]}" width="250" border="0" alt="{$altLinks[$i]}"></a></td>
EOF;
        $i++;
        if($i % 4 == 0){
            echo '</tr></tr>';
        }

    }

    echo '</tr></table></div>';
}
?>