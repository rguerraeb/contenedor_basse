<?php
namespace AppBundle\Helper;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiHelper
{
      
    public function connectServices($url, $method, $tokenApp, $postdata)
    {
        //try {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER,
                    array(
                        'Content-Type: application/json',
                        'cache-control: no-cache',
                        'tokenapp: '.$tokenApp
                    )
                );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

            $result = curl_exec($ch);
            $curlError = curl_error($ch);
            $curlInfo = curl_getinfo($ch);
                        
            if ($curlError) {
                //throw new Exception($curlError, curl_errno($ch));
                /*return json_encode(array(
                    //'status' => $curlInfo['http_code'],
                    //'msg' => $curlError,
                    /*'response' => $result
                ));*/
                return $result;
            }
    
            curl_close($ch);
            
            /*return json_encode(array(
                //'status' => $curlInfo['http_code'],
                //'msg' => "success",
                /*'response' => $result
            ));*/
            return $result;

        /*} catch (Exception $e) {
            $exception = trigger_error(sprintf('Curl failed with error #%d: %s',$e->getCode(), $e->getMessage()),E_USER_ERROR);
            return false;
        }*/
    }
    
}
?>