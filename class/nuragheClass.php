<?php 
    
    class nuragheClass {

        public $nuragheray = array();

        public function __construct(){
            $file = file_get_contents("data/nuraghe.csv");
            $file = str_replace('"','',$file);
            $righe = explode(chr(10),$file);
            foreach($righe as $riga){
                $lol = explode(';',$riga);
                $this->nuragheray[] = $lol;
            }
            $colonne = explode(';',$righe[1]);
            $this->nuragheray[] = $colonne[1];
        }
        
        public function getElByText($search, $pagina) {
            $array_res = array();
            $nuragheray=$this->nuragheray;
            unset($nuragheray[0]);
            $i = 0;
            $start = 0*4;
            $end = 10;
            if(DEBUG) $search="Abbasanta";
            foreach($nuragheray as $nur){
                if(strtolower($nur[6])==strtolower($search)){
                    if($nur[8]=='') continue;
                    if($i<$start) continue;
                    $lat   =  str_replace('"', '', $nur[3]);
                    $long  =  str_replace('"', '', $nur[10]);
                    $response = array (
                        'id'=>  str_replace('"', '', $nur[0]),
                        'tipo'=>  str_replace('"', '', $nur[9]),
                        'comune'=>  str_replace('"', '', $nur[6]),
                        'lat'=>  $lat,
                        'long'=>  $long,
                        'nome'=>  str_replace('"', '', $nur[8]),
                        'provincia'=>  str_replace('"', '', $nur[5]),
                        'zona'=>  str_replace('"', '', $nur[2]),   
                        //'gmaps'=>  'http://maps.google.com/?ll='.str_replace('"', '', $col[10]).','.str_replace('"', '', $col[3]),
                        //'gmaps'=>  'http://maps.google.com/maps?q=loc:='.str_replace('"', '', $col[10]).','.str_replace('"', '', $col[3])
                        'gmaps'=>  'https://www.google.com/maps/place/'.$long.'+'.$lat.'/@'.$long.','.$lat.',15z'
                    );
                    $array_res[] = $response;
                    $i++ ;
                    if($end<=$i) return $array_res;
                }    
            }
                        
            return $array_res;
        }
        
        public function getElByAttachment($array) {
            $array_res = array();
            $nuragheray=$this->nuragheray;
            unset($nuragheray[0]);
            $i = 0;
            $start = 0*4;
            $end = 10;
            if(DEBUG) $search="Abbasanta";
            foreach($nuragheray as $nur){
                if(($nur[10]<$array["lat"]+0.02 && $nur[10]>$array["lat"] - 0.02) && ($nur[3]<$array["long"]+0.02 && $nur[3]>$array["long"] - 0.02)){ /*|| stripos($col[2],$bidda) || stripos($col[5],$bidda)*/

                    if($nur[8]=='') continue;
                    if($i<$start) continue;
                    $lat   =  str_replace('"', '', $nur[3]);
                    $long  =  str_replace('"', '', $nur[10]);
                    $response = array (
                        'id'=>  str_replace('"', '', $nur[0]),
                        'tipo'=>  str_replace('"', '', $nur[9]),
                        'comune'=>  str_replace('"', '', $nur[6]),
                        'lat'=>  $lat,
                        'long'=>  $long,
                        'nome'=>  str_replace('"', '', $nur[8]),
                        'provincia'=>  str_replace('"', '', $nur[5]),
                        'zona'=>  str_replace('"', '', $nur[2]),   
                        //'gmaps'=>  'http://maps.google.com/?ll='.str_replace('"', '', $col[10]).','.str_replace('"', '', $col[3]),
                        //'gmaps'=>  'http://maps.google.com/maps?q=loc:='.str_replace('"', '', $col[10]).','.str_replace('"', '', $col[3])
                        'gmaps'=>  'https://www.google.com/maps/place/'.$long.'+'.$lat.'/@'.$long.','.$lat.',15z'
                    );
                    $array_res[] = $response;
                    $i++ ;
                    if($end<=$i) return $array_res;
                }    
            }
                        
            return $array_res;
        }
        
        public function getElByCode($search) {
            $nuragheray=$this->nuragheray;
            foreach($nuragheray as $nur){
                if($nur[0]==$search){
                    return $nur[8];
                }    
            }
        }
        
        public function getElByCord($search) {
            $nuragheray=$this->nuragheray;
            foreach($nuragheray as $nur){
                if($nur[0]==$search){
                    return $nur[8];
                }    
            }
        }

    }
