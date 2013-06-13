<?php



class Store {



    private $stores;

    private $url;

    private $apprlCodes = array();

    private $name;

    private $apparel;



    public function __construct($name, $apparel)

    {

        include('storeCodes.php');



        $this->name = $name;

        $this->apparel = $apparel;

        $this->url = $storeUrls[$name];



        foreach ($apparel as $apprl) {

            if (in_array($apprl, array_keys($apprlCodesArray[$this->name]))) {
                array_push($this->apprlCodes, $apprlCodesArray[$name][$apprl]);

            }

        }

    }



    public function get_name(){

        return $this->name;

    }



/*    public function get_link($str, $apprl) {   

        if($str == 'jcrew'){

            if($apprl == 'longsleeve'){

                $apprl = 'shirts';

            }

            $find = '/.';

            $pos = strpos($this->strs[$str], $find);

            return substr_replace($this->strs[$str], $apprl, $pos+1, 0);

        }else if($str == 'gap'){            

            return $this->strs[$str];

        }else if($str == 'banana'){

            return $this->strs[$str];

        }else if($str == 'urban'){

            return $this->strs[$str];

        }else{

            return false;

        }

    }*/



    public function get_apprl_codes(){

        return $this->apprlCodes;

    }

    public function get_url(){
        return $this->url;
    }



    public function get_a_links(){

        if($this->name == 'jcrew'){

            $searchText = './/td[@class="arrayImg"]/a';

        }else if($this->name == 'urbanOutfitters'){

            $searchText = './/p[contains(concat(" ",normalize-space(@class)," "),"category-product-image")]/a';

        }

        return $searchText;  

    }



    public function get_src_links(){

        if($this->name == 'jcrew'){

            $searchText = './/td[@class="arrayImg"]//a/img';

        }else if($this->name == 'urbanOutfitters'){

            $searchText = './/p[contains(concat(" ",normalize-space(@class)," "),"category-product-image")]//a/img';

        }

        return $searchText;

    }

}

?>

