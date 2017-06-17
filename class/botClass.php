<?php 
    
    class botClass {
        public $messenger;
        public $user;
        public $messageReply = '';
        public $arrayReply = array();

        public function __construct(){
            $this->messenger = new messengerClass();
            $user_id = $this->messenger->getSender();
            $this->user = new telegrUman($user_id);
            $this->messenger->setUser($this->user);
        }

        public function go (){
            $step = $this->user->getValue('step');
            if(!$step) {
                $step = 0;
            }
            if(isset($this->messenger->message['text']) && $this->messenger->message['text']!=''){
                $text = $this->messenger->message['text'];
                if(strtolower($text) == 'avvio') {
                    $this->messenger->setMessageReply('Benebènnidos in Sardigna e Archeologia, cumponende su nùmene de unu comunu o imbiende su logu in ue t’agatas as a bìdere sa lista de totu is logos de importu istòricu e archeològicu. ');
                    $this->messenger->setArrayReply(null);
                } else {
                    $this->machine($step);
                    $this->messenger->setMessageReply($this->messageReply);
                    $this->messenger->setArrayReply($this->arrayReply);
                }
                
                $this->messenger->send();
            }
        }

        public function machine($step) {
            
            $tipe = $this->messenger->tipe_message;
            $nuraghe = new nuragheClass();
            if($tipe == 'coordinates') {
                $this->messageReply = 'coordinates';
                $this->arrayReply = $nuraghe->getElByAttachment($this->messenger->attachments);
            } elseif($tipe == 'text'){
                $text = $this->messenger->message['text'];
                
                $this->messageReply = $this->messenger->message['text'];
                $this->arrayReply = $nuraghe->getElByText($this->messenger->message['text'],$this->user->getValue('page'));              
            }else{
                $this->messageReply = 'Forma non suportada oppure custa forma no andat bene. Iscrie su nùmene de unu comunu sardu o imbia su logu in ue t’agatas';                
                //$this->arrayReply = $nuraghe->getElByText('NUR2892');
            }

        }

    }
