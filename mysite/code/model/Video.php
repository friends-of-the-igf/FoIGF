<?php
class Video extends DataObject {

	public static $db = array(
		'YouTubeID' => 'VarChar'
	);

	public static $has_one = array(
		'MeetingSession' => 'MeetingSession'
	);

	public function getCMSFields() {
		$fields = new FieldList();
		$fields->push(new TextField('YouTubeID', 'YouTube ID (can be ID or full URL)'));
		return $fields;
	}

	public function onAfterWrite(){
		parent::onAfterWrite();

		$this->formatYouTubeID();
	}

	public function formatYouTubeID(){

		$url = $this->YouTubeID;
         
        $params = explode('?',$url);

        if(count($params) > 1) {

            $paras = explode('&',$params[1]);
            foreach($paras as $para) {
                $type = substr($para,0,2);
                if($type == 'v=') {
                    $str = explode('=',$para);
                    $v = $str[1];
                    if($v != $url) {
                 
                        $this->YouTubeID = $v;
                        $this->write();
                    }
                }
            }
        }
        $params = explode('be/',$url);
            if(count($params) > 1) {
            $paras = explode('?',$params[1]);
            if(count($paras) == 1){
                if($paras[0] != $url){
                	
                    $this->YouTubeID = $paras[0];
                    $this->write();
                }
            } 
        }  
	}

	public function getVideo(){
        if($this->YouTubeID != null){
            return '<iframe width="100%" height="100%" src="http://www.youtube.com/v/'.$this->YouTubeID.'?controls=0&showinfo=0" frameborder="0"></iframe>';
        }
    }
}
