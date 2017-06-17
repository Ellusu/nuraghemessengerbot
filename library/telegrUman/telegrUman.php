<?php 

    /* 
     * Title: TelegrUman
     * Author: Matteo Enna
     * License: GPL V3 
     */

    include_once 'telegrUmanClass.php';

    class telegrUman extends telegrUmanClass
    {
        
        /* Set value for single attribute */
        public function setValue($name, $value) {
            $this->array[$name] = $value;
        }
        
        /* Get value for single attribute */
        public function getValue($name) {
            return $this->array[$name];
        }
        
        /* Reset value for single attribue */
        public function resetValue($name) {
            $this->array[$name] = $this->conf[$name]['default'];
        }
        
        /* Delete value for single attribute */
        public function deleteValue($name) {
            $this->array[$name] = '';
        }
    }


?>
