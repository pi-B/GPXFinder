<?php
    class PointsGPX{

        public $time_code;
        public $longitude;
        public $latitude;
        public $elevation;
        
        public function __construct($lat,$lon){
           
            $this->longitude=$lon;
            $this->latitude=$lat;
            
        }

        public function standardiseTime($time): string
        {
            $str = "";
            $str = str_replace('T',' ',$time);
            $str= str_replace('Z','',$str);
            
            return $str;

        }

        public function addTime($time){
            $this->time_code=$this->standardiseTime($time);
        }

        public function getTime(): string
        {
            if(isset($this->time_code)){
                return $this->time_code;
            } else {
                return '0';
            }
        }

        public function addEle($ele){
            $this->elevation=$ele;
        }

        public function getEle(): string
        {
            if(isset($this->elevation)){
                return $this->elevation;
            } else {
                return '0';
            }
        }

        public function getLong(): string
        {
            return $this->longitude;
        }

        public function getLat(): string
        {
            return $this->latitude;
        }

        public function toString(): string
        {
            $string = $this->getTime()." Lat: ".$this->getLat()." Long: ".$this->getLong()."<br>Ele :".$this->getEle();
            return $string;
        }
    }
?>