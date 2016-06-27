<?php namespace MCS;

use Exception;

class OpenCartRestClient{

    private $apiUser;
    private $apiPass;
    private $apiUrl;
    private $cookie = null;
    
    public function __construct($user, $pass, $url)
    {
        $this->apiUser = $user;
        $this->apiPass = $pass;
        $this->apiUrl = $url . 'index.php?';
    }
    
    private function request($query, $post = null)
    {
        
        $array = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $this->apiUrl . http_build_query($query),
            CURLOPT_POSTFIELDS => http_build_query([
                'api_user' => $this->apiUser,
                'api_key' => $this->apiPass
            ])
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, $array);
        $result = curl_exec($ch);
        curl_close($ch);
        
        if ($result == 'Unauthorised') {
            throw new Exception('Unauthorised');    
            return false;
        }
        
        return json_decode($result, true);
        
    }
    
    public function getorders()
    {

        $query = [
            'action' => 'orders'
        ];
        return $this->request($query);
    }
    
    public function getOrderItems($order_id)
    {

        $query = [
            'id' => $order_id,
            'action' => 'order_products'
        ];
        return $this->request($query);
    }
   
}