<?php 

    /* 
     * Title: TelegrUman
     * Author: Matteo Enna
     * License: GPL V3 
     */

    class telegrUmanClass {
        
        public $array = array();
        public $id;
        public $conf = array();
        public $temp = array();
        
        public function __construct($id = false) {
            if(!$id)
                return false;  
            $this->id = $id;
            $this->getConf();    
            $this->user(); 
        }

        /* Load configuration file */
        public function getConf(){
            $conf = simplexml_load_file('library/telegrUman/config.xml');
            foreach ($conf as $s_conf) {
                $temp = array(
                    'name'      => (string) $s_conf->name,
                    'default'   => (string) $s_conf->default,
                    'temporany' => (string) $s_conf->temporany,
                    
                );
                if($s_conf->temporany && $s_conf->temporany!='') $this->temp[] = (string) $s_conf->name;
                $this->conf[(string) $s_conf->name] = $temp;
                $this->array[(string) $s_conf->name] = (string) $s_conf->temporany;
            }
        }
        /* Load or create user attributes */
        public function user () {
            $nome_file = 'library/telegrUman/user/'.$this->id.'.json';
            if (file_exists($nome_file)){
                $myfile = file_get_contents($nome_file);
                $this->array = (array) json_decode($myfile);
            } else {
                touch($nome_file);
                chmod($nome_file, 0777);
                $this->save();
            }
            return $this->array;
        }
        
        /* Restores all the attributes */
        public function resetAll () {
            $keys = array_keys($this->array);
            foreach ($keys as $key) {
                $this->array[$key] = $this->conf[$key]['default'];
            }
        }
        
        /* Delete all the attributes */
        public function deleteAll () {
            $keys = array_keys($this->array);
            foreach ($keys as $key) {
                $this->array[$key] = '';
            }
        }
        
        /* Delete all temporary attributes */
        public function clearTemporary () {
            $keys = array_keys($this->temp);
            foreach ($keys as $key) {
                $this->array[$key] = '';
            }
        }

        /* Save user attributes*/
        public function save () {
            $nome_file = 'library/telegrUman/user/'.$this->id.'.json';
            $myfile = fopen($nome_file, "w+");
            $txt = json_encode($this->array);
            fwrite($myfile, $txt);
            fclose($myfile);
        }
    }
