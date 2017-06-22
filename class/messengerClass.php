<?php 
    
    class messengerClass {

        public $message;
        public $sender;
        public $url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.TOKEN;
        public $message_to_reply;
        public $choice_to_reply;
        public $user;
        public $tipe_message = '';
        public $attachments = array();

        public function __construct(){
            $content = file_get_contents('php://input');
    
            
            $content_txt = file_get_contents('php://input');
            $input = json_decode($content_txt, true);
            $this->sender = $input['entry'][0]['messaging'][0]['sender']['id'];
            

            if(isset($input['entry'][0]['messaging'][0]['message']['attachments'][0]['payload']['coordinates'])) {
                $this->message = array(
                    'text'=>$input['entry'][0]['messaging'][0]['message']['attachments'][0]['title']
                    );
                $this->tipe_message  = "coordinates";
                $this->attachments = array(
                    'lat'   =>  $input['entry'][0]['messaging'][0]['message']['attachments'][0]['payload']['coordinates']['lat'],
                    'long'  =>  $input['entry'][0]['messaging'][0]['message']['attachments'][0]['payload']['coordinates']['long']
                        );
                $subject = "Start - New FB coordinates - NuragheBot starter";
                $message = $content;                
            } elseif(isset($input['entry'][0]['messaging'][0]['message']['text'])) {
                $this->message = array(
                    'text'=>$input['entry'][0]['messaging'][0]['message']['text']
                );
                $this->tipe_message  = "text";
                
                $subject = "Start - New FB text - NuragheBot starter";
                $message = $content;
                mail('matteo.enna89@gmail.com', $subject, $message);
                
            } elseif(isset($input['entry'][0]['messaging'][0]['message'])) {
                $this->message = array(
                    'text'=>'non riconosciuto'
                );
                $this->tipe_message  = "null";
                
            }
            
            $nome_file = 'tmp/1.txt';
            touch($nome_file);
            chmod($nome_file, 0777);
            $myfile = fopen($nome_file, "w+");
            $txt = json_encode($content_txt);
            fwrite($myfile, $content_txt);
            fclose($myfile);
        }
        
        public function setUser($user) {
            $this->user = $user;
        }

        public function createReply() {
            $this->message_to_reply = 'hai detto: '.$this->message['text'];
            
            $nuraghe = new nuragheClass();
            $el = $nuraghe->getEl();
            $this->choice_to_reply = '"quick_replies":[
                  {
                    "content_type":"'.$el.'",
                    "title":"Red",
                    "payload":"DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_RED"
                  },
                  {
                    "content_type":"text",
                    "title":"Green",
                    "payload":"DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_GREEN"
                  }
                ]';
        }

        public function getSender() {
            return $this->sender;
        }

        public function setMessageReply($testo){
            $this->message_to_reply = $testo;
        }

        public function setArrayReply($array){
            $this->choice_to_reply =   $array;
            
        }

        public function oneAttachment() {
            
            $answer = ["attachment"=>[       
                "type"=>"template",       
                "payload"=>[         
                "template_type"=>"generic",        
                 "elements"=>[[             
                "title"=>"Migrate your symfony application",
                "item_url"=>"https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
                "image_url"=>"https://www.cloudways.com/blog/wp-content/uploads/Migrating-Your-Symfony-Website-To-Cloudways-Banner.jpg",             
                "subtitle"=>"Migrate your symfony application from Cpanel to Cloud.",             
                "buttons"=>[[                
                 "type"=>"web_url",                 
                 "url"=>"www.cloudways.com",                 
                 "title"=>"View Website"               
                ],[                 
                "type"=>"postback",                 
                "title"=>"Start Chatting",                 
                "payload"=>"Even want some more? say me!"               ]                           ]]]       
                ]     
            ]];
            
            return $answer;
        }
        
        public function moreAttachment() {
            
            $el = array();
                            
            foreach($this->choice_to_reply as $k=>$ss) {
                $el[] = [
                    "title"=>$ss['nome']." - ".$ss['comune']." - ".$ss['id'],
                    "item_url"=>$ss['gmaps'],
                    "image_url"=>"http://matteoenna.it/wp-content/uploads/2016/08/Nurage1rp-150x150.jpg",
                    "subtitle"=>$ss['tipo']." del ".$ss['zona'].": ".$ss['nome']." ".$ss['comune']." (".$ss['provincia'].")",
                    "buttons"=>[
                      [
                        "type"=>"web_url",
                        "url"=>$ss['gmaps'],
                        "title"=>'Mappa'
                      ],
                    ]
                  ];
                
            }
            $answer = ["attachment"=>[
                            "type"=>"template",
                            "payload"=>[
                                "template_type"=>"generic",
                                "elements"=>$el
                            ]
                        ]
                    ];
            
            return $answer;
        }
        
        public function addChoices($message) {
            $resp_array = array();
            
            $n = count($this->choice_to_reply);
            $n = $n/4;
            $n = intval($n);
            
            for ($i=0; $i<5; $i++) {
                if($i!=4) {
                    $resp_array[] = [
                        "content_type"  =>  "text",
                        "title"         =>  ($i+1),
                        "payload"       =>  "DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_RED"
                    ];
                } else {
                    $resp_array[] = [
                        "content_type"  =>  "text",
                        "title"         =>  "Avanti",
                        "payload"       =>  "DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_RED"
                    ]; 
                    return [
                        "text"=>$message,
                        "quick_replies"=>$resp_array
                    ];                   
                }
            }
            return [
                "text"=>$message,
                "quick_replies"=>$resp_array
                ];
            
        }
        
        public function send() {
            $ch = curl_init($this->url);
            
            $step = $this->user->getValue('step');
            if($this->choice_to_reply){
                if(!$step) {
                    $step = 0;
                    $answer = $this->moreAttachment();
                } elseif($step == 1) {
                    $answer = $this->moreAttachment();
                }
            } else  {
                $answer = array(
                    'text'=>  $this->message_to_reply         
                );
            }
            
            
            $response = [
                'recipient' => ['id' => $this->sender], 
                'message' => $answer, 
                'access_token' => TOKEN
            ];
            $jsonData = json_encode($response);
            $jsonDataEncoded = $jsonData;
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            if(!empty($this->message)){
                $result = curl_exec($ch);
            }
        }


    }
