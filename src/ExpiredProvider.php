<?php

namespace Muraveiko\DNS;

use \Exception;
use \yswery\DNS\AbstractStorageProvider;
use \yswery\DNS\RecordTypeEnum;

class ExpiredProvider extends AbstractStorageProvider {
    use LoadJson;

    /**
     * @var mixed
     */
    private $dns_records;
    private $DS_TTL;
    private $arpa;
    private $name_server;

    public function __construct($record_file, $default_ttl = 300)
    {
        
        if(!is_int($default_ttl)) {
            throw new Exception('Default TTL must be an integer.');
        }
        $this->DS_TTL = $default_ttl;

        $this->dns_records = $this->loadJson($record_file,'dns record');

        $this->arpa = implode('.',array_reverse(explode('.',($this->dns_records['server']['ip'])))).'.in-addr.arpa';
        $this->name_server =  $this->dns_records['server']['name'];
    }

    /**
     * @param $question
     * @return array
     */
    public function get_answer($question)
    {
        $answer = array();
        $domain = trim($question[0]['qname'], '.');
        $type = RecordTypeEnum::get_name($question[0]['qtype']);

       // добавляем ответ об имени сервера
       if($type == 'PTR' && $domain==$this->arpa) {

                $answer[] = array('name' => $question[0]['qname'], 'class' => $question[0]['qclass'], 'ttl' => $this->DS_TTL, 'data' => array('type' => $question[0]['qtype'], 'value' => $this->name_server));

        } elseif(isset($this->dns_records['defaults']) &&isset($this->dns_records['defaults'][$type])) {

            if(is_array($this->dns_records['defaults'][$type]) && $type != 'SOA') {
                foreach($this->dns_records['defaults'][$type] as $ip) {
                    $answer[] = array('name' => $question[0]['qname'], 'class' => $question[0]['qclass'], 'ttl' => $this->DS_TTL, 'data' => array('type' => $question[0]['qtype'], 'value' => $ip));
                }

            } else {
                $answer[] = array('name' => $question[0]['qname'], 'class' => $question[0]['qclass'], 'ttl' => $this->DS_TTL, 'data' => array('type' => $question[0]['qtype'], 'value' => $this->dns_records['defaults'][$type]));
            }
        }
        return $answer;
    }

}

