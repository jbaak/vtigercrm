<?php

namespace Vtiger\Modules\Services;

class ClientConekta
{
    private $url = '';
    private $privateKey = '';
    private static $ch = null;

    public function __construct($url, $privateKey)
    {
      $this->url = $url;
      $this->privateKey = $privateKey;
    
    }

    public function createRecord($data, $module)
    {  
        $this->url = $this->url.$module;

        $response = $this->doRequest($this->url, "POST", json_encode($data));
        return $response;
    }

    public function updateRecord($id, $data, $module)
    {   
        $this->url = $this->url.$module.'/'.$id;

        $customer = $this->doRequest($this->url, "GET");

        if (!isset($customer['id'])) {
            throw new \Exception(sprintf("No existe un cliente con el ID %s de Conekta ", $id));  
        }  
        
        $response = $this->doRequest($this->url, "PUT", json_encode($data));
        return $response;
    }

   private function doRequest($url, $method = 'GET', $params =  null)
   {
        if (self::$ch === null) {
            self::$ch = curl_init();
        }

        if ($method === "GET" && $params) {
        
            $url = $url . "?" . http_build_query($params);
            
        }

        curl_setopt(self::$ch, CURLOPT_URL, $url);

        if ($method === "POST") {
        
            curl_setopt(self::$ch, CURLOPT_POST, true);
            curl_setopt(self::$ch, CURLOPT_POSTFIELDS, $params);
        
        } elseif ($method === "PUT") {
            curl_setopt(self::$ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt(self::$ch, CURLOPT_POSTFIELDS, $params);
        
        } else {
            curl_setopt(self::$ch, CURLOPT_HTTPGET, true);
        }

        curl_setopt(self::$ch, CURLOPT_HTTPHEADER, [
            'Accept: application/vnd.conekta-v2.0.0+json',
            'Authorization: Bearer '.$this->privateKey,
            'Content-Type: application/json'
        ]);

        curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, false);

        $data = curl_exec(self::$ch);

        if ($data === false) {
            throw new \Exception("Curl Error: " . curl_error(self::$ch));
        }

        return json_decode($data,true);
   } 
}