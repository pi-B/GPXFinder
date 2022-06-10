<?php
    class PointsGPX{

        public $time_code;
        public $longitude;
        public $latitude;
        public $elevation;
        
        public function __construct($time, $lat,$lon,$ele){
            $this->time_code=$this->standardiseTime($time);
            $this->longitude=$lon;
            $this->latitude=$lat;
            $this->elevation=$ele;
        }

        public function standardiseTime($time): string
        {
            $str = "";
            $str = str_replace('T',' ',$time);
            $str= str_replace('Z','',$str);

            return $str;

        }
        public function getTime(): string
        {
            return $this->time_code;
        }

        public function getLong(): string
        {
            return $this->longitude;
        }

        public function getLat(): string
        {
            return $this->latitude;
        }

        public function getEle(): string
        {
            return $this->elevation;
        }

        public function toString(): string
        {
            $string = $this->getTime()." Lat: ".$this->getLat()." Long: ".$this->getLong()."<br>Ele :".$this->getEle();
            return $string;
        }
    }
?>