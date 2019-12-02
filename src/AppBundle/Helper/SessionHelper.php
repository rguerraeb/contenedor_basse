<?php
namespace AppBundle\Helper;

class SessionHelper
{
	
    public function open_url($url = "", $parameters = false) {
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        if ($parameters) {
            curl_setopt ( $ch, CURLOPT_POST, true);
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $parameters);
        }
        
        ob_start ();
        curl_exec ( $ch );
        curl_close ( $ch );
        $string = ob_get_contents ();
        ob_end_clean ();
        return $string;
    }
    
    public function send_sms( $phone = "" , $message = "" , $token = "" , $country = 0 ){
		
		if($country == 1 ){
			if(strlen($phone) < 11){
				$phone = "502".$phone;
			}
			$message .= " r." . substr(md5(microtime()),rand(0,26), 3);
			$url = "http://192.168.10.221/service-container/call/18/amount/1/phone/{$phone}/token/{$token}/msg/".urlencode($message);
			$response_url = $this->open_url($url);
			return $response_url;
		}else{
			if(strlen($phone) < 11){
				
				$phone = "503".$phone;
			}
			
			$url = "http://192.168.10.221/service-container/call/29/amount/1/msisdn/{$phone}/msg/".urlencode($message)."/token/{$token}/phone/{$phone}";
			$response_url = $this->open_url($url);
			return "ok";
		}	
	}
	
	public function send_sms_televida( $phone = "" , $message = "", $token = "" ){
	    $message .= " r." . substr(md5(microtime()),rand(0,26), 3);
		$url = "http://192.168.10.221/service-container/call/18/amount/1/phone/{$phone}/token/{$token}/msg/".urlencode($message);
		$response_url = $this->open_url($url);
		//$response_url = false;
		return $response_url;
	}


	public function send_sms_claro( $phone = "" , $message = "", $token = "" ){
		if(strlen($phone) < 11){
			$phone = "502".$phone;
		}
	    //$message .= " r." . substr(md5(microtime()),rand(0,26), 3);
		$url = "http://192.168.10.221/service-container/call/18/amount/1/phone/{$phone}/token/{$token}/msg/".urlencode($message);
		$response_url = $this->open_url($url);
		//$response_url = false;
		return $response_url;
	}
			
    
    /**
     * From a max response get the code
     *
     * @param string $response response given by max 'web service'
     * @return string code found. Null if error
     */
    public function getMaxCode($response) {
        $resultArray = explode(" ", $response);
        if (sizeof($resultArray) > 1 && strtolower($resultArray[0]) == "guardado") {
            // If it was saved
            // Code is expected to be the second word
            $code = $resultArray[1];

            // Check not empty code
            if ($code != '') {
                return $code;
            }
        }

        // Default is error
        return null;
    }
    
    /**
     * Opens a URL by post method
     *
     * @param string $url url to consume
     * @param array $params parameters for url
     * @return string response given by consuming url
     */
    public function openPostUrl($url, $params){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
    
    /**
     * With the object expected from transferTo check if it was successful
     *
     * @param Object $transferToRes the response in an objecto of transferTo
     * @return boolean if it was succesful or not the transaction
     */
    public function isTransactionSuccessful($transferToRes) {
        if (isset($transferToRes->status_message) && $transferToRes->status_message == "Transaction successful") {
            return true;
        }

        return false;
    }
    
    /**
     * Gets the code of an exchange from and object of a transferTo response
     *
     * @param Object $transferToRes Object from the JSON in a transferto response
     * @return string code of exchange. Null if the code wasn't found
     */
    public function getExchangeCode($transferToRes) {
        // Check if it has the correct attribute
        if (isset($transferToRes->voucher->pin)) {
            return $transferToRes->voucher->pin;
        }

        // Default value to return
        return null;
    }
    
    /**
     * From a string tries to find a JSON substring
     *
     * @param string $string string which may hold a json inside
     * @return Object the json object found or null
     */
    public function findJSONObject($text) {
        $textLen = strlen( $text );
        $startIndex = -1;
        $endIndex = -1;

        // Count every time a { apperas
        $contOp = 0;

        // Lopp through each character
        for( $i = 0; $i <= $textLen; $i++ ) {
            $char = substr( $text, $i, 1 );

            if ($char == "{") {
                if ($contOp == 0) {
                    // Start of JSON
                    $startIndex = $i;
                }

                $contOp ++;
            }
            else if ($char == "}") {
                $contOp--;

                if ($contOp == 0) {
                    // Close of JSON found
                    $endIndex = $i;
                    break;
                }
            }
        }

        if ($startIndex != -1 && $endIndex != -1) {
            // Start and end found, try create json
            $jsonSubstring = substr($text, $startIndex, $endIndex - $startIndex + 1);

            // Try creating object
            return json_decode($jsonSubstring);
        }

        // Default value to return
        return null;
    }
}
?>