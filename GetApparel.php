<?php



include 'store.php';

include 'util.php';

include 'simple_html_dom.php';



libxml_use_internal_errors(TRUE);

function get_apparel_array($yourStore, $code){

    if($yourStore->get_name() == 'jcrew'){

        $find = '/.';

        $pos = strpos($yourStore->get_url(), $find);

        $newUrl = substr_replace($yourStore->get_url(), $code, $pos+1, 0);

        $html = file_get_html($newUrl);

        foreach($html->find('td[class=arrayImg] a') as $a) {
            $item['src'] = $a->find('img', 0)->src;
            $item['alt'] = $a->find('img', 0)->alt;
            $item['href'] = $a->href;
            $apparelArray[] = $item;
        }

        return $apparelArray;

    }else{

        $url = $yourStore->get_url() . $code;

        $html = file_get_html($url);
        
        foreach($html->find('p[class=category-product-image] a') as $a) {
            $item['src'] = $a->find('img', 0)->src;
            $item['alt'] = $a->find('img', 0)->alt;
            $item['href'] = $a->href;
            $apparelArray[] = $item;
        }
        
        return $apparelArray;
    }

    return -1;

}



//get stores/apparel page

$stores = $_POST['stores'];

$apparel = $_POST['apparel'];

echo '<div id="storeTabs">';
    echo '<ul>';

foreach ($stores as $key => $store) {
    echo <<<EOF
        <li><a href="#storeFragment-{$key}"><span>{$store}</span></a></li>
EOF;
}

    echo '</ul>';

foreach ($stores as $key => $store) {

    echo <<<EOF
        <div id="storeFragment-{$key}">
EOF;

    $yourStore = new store($store, $apparel);  //create store obj for each store selected
    $j = 0;

    foreach ($yourStore->get_apprl_codes() as $key => $code) {
        
        $apparelArray = get_apparel_array($yourStore, $code);



/*
        //load html into DOM object

        $dom_object = new DOMDocument();

        $dom_object->loadHTML($html_string);

        //peform some xpath queries on DOM

        $xpath = new DOMXPath($dom_object);

        //STEP 1: get a links

        $aLinks = array();

        $vRes = $xpath->query($yourStore->get_a_links());

        foreach ($vRes as $obj) {

          $aLinks[] = $obj->getAttribute('href');

        }

        //STEP 2: get img src & alt

        $sLinks = array();

        $altLinks = array();

        $vRes = $xpath->query($yourStore->get_src_links());

        foreach ($vRes as $obj) {

          $sLinks[] = $obj->getAttribute('src');

          $altLinks[] = $obj->getAttribute('alt');

        }*/



        $i = 0;

        echo <<<EOF

        <div id="prodArray{$store}">

        <table width="80%">

        <thead><tr><th>{$store}: {$apparel[$j]}</th></tr><thead>

        <tr>
EOF;
        $j++;
        foreach ($apparelArray as $item){

            echo <<<EOF

               <td><a href="{$item['href']}" border="0">

               <img src="{$item['src']}" width="250" border="0" alt="{$item['alt']}"></a></td>

EOF;



            $i++;

            if($i % 4 == 0){

                echo '</tr>';

            }

        }

         echo '</tr></table></div>';

    }

    echo '</div>';

}



?>