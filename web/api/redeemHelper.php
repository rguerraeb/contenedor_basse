<?php
/**
 * Uses a web service to send SMS through televida
 *
 * @param string $phone phone to send SMS to
 * @param string $message message to send
 * @return string $response of web service
 */
function sendSmsTelevida($phone, $message){
    $url = "http://innovacion.ebfuture.net/premios/frontend_dev.php/call/service/name/sendsms.php/phone/$phone/rest/true/account/6/msg/".urlencode($message)."/";
    $responseUrl = curlUrl($url);
    return $responseUrl;
}

/**
 * Opens a URL by post method
 *
 * @param string $url url to consume
 * @param array $params parameters for url
 * @return string response given by consuming url
 */
function openPostUrl($url, $params){
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
 * Use curl to consume a url
 *
 * @param string $url url to consume
 * @param array $parameters post parameters
 * @return string response of url
 */
function curlUrl($url = "", $parameters = false) {
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

/**
 * From a string tries to find a JSON substring
 *
 * @param string $string string which may hold a json inside
 * @return Object the json object found or null
 */
function findJSONObject($text) {
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

/**
 * From a max response get the code
 *
 * @param string $response response given by max 'web service'
 * @return string code found. Null if error
 */
function getMaxCode($response) {
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
 * Look for prize_pin that is not used and is of prize
 *
 * @param int $prizeId id of prize
 * @param MySqlConnection connection to database
 * @return array a single row of table prize_pin
 */
function notUsedPrizePin($prizeId, $conn) {
    $query = "
        SELECT
            *
        FROM
            prize_pin
        WHERE
            used_by IS NULL
            AND prize_id = " . $prizeId . "
        LIMIT 1
    ";

    $result = mysql_query($query,$conn) or die(mysql_error());
    $array = array();
    while($row  = mysql_fetch_assoc($result)) {
        return $row;
    }

    return NULL;
}

/**
 * Updates a prize_pin and puts it as used
 *
 * @param int $usedBy user who used the pin
 * @param string $usedAt date when the code was used
 * @param string $phone used phone
 * @param MySqlConnection connection to database
 * @param int $prizePinId id of prize_pin to update
 */
function updatePrizePin($usedBy, $usedAt, $phone, $prizePinId, $conn) {
    $query = "
        UPDATE
            prize_pin
        SET
            used_by = " . $usedBy . ",
            used_at = '". $usedAt . "',
            phone_delivered_to = '" . $phone . "'
        WHERE
            prize_pin_id = " . $prizePinId . "
    ";

    $result = mysql_query($query,$conn);
}

/**
 * With the object expected from transferTo check if it was successful
 *
 * @param Object $transferToRes the response in an objecto of transferTo
 * @return boolean if it was succesful or not the transaction
 */
function isTransactionSuccessful($transferToRes) {
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
function getExchangeCode($transferToRes) {
    // Check if it has the correct attribute
    if (isset($transferToRes->voucher->pin)) {
        return $transferToRes->voucher->pin;
    }

    // Default value to return
    return null;
}

/**
 * After the prize was redeem at a database label, send SMS with exchange information
 *
 * @param array $exchangeInfo [status: 200 (success)| 500 (error), code: prize code if applicable]
 * @param string $phone phone who made the exchange
 * @param Prize $prize prize row
 */
function redeemSms ($exchangeInfo, $phone, $prize){
    $status = $exchangeInfo['status'];
    if ($status == 200) {
        // Success on prize exchange
        $smsResponse = 'Felicidades CEMPRO te regala ' . $prize['name'];

        if ($prize['type_exchange'] == 'RECARGA') {
            // Add random end to string
            $smsResponse .= " r." . rand(1,1500);
        }
        else {
            $code = $exchangeInfo['code'];

            // Every other type of exchange, asume it requires and has code
            $smsResponse .= ". Presenta este codigo " . $code;
        }
    }
    else {
        // Error while exchanging prize
        $smsResponse = 'CEMPRO te informa que ocurrio un error al darte el premio';
    }

    // Send sms
    sendSmsTelevida($phone , $smsResponse);
}

/**
 * Consumes web service to make exchange and makes necessary database changes
 *
 * @param string $phone phone making the exchange
 * @param array $prize prize to exchange, a row
 * @param MySqlConnection connection to database
 * @return array [status: 200 (success) | 500 (error), code: code of prize if applicable]
 */
function clientRedeem($phone, $prize, $conn, $clientName) {
    if ($prize['type_exchange'] == 'RECARGA') {
        $exchangeStatus = redeemReloads($phone, $prize);
    }
    else if ($prize['type_exchange'] == 'INSTANTANEO-MAX') {
        $exchangeStatus = redeemMax($phone, $prize);
    }
    else if ($prize['type_exchange'] == 'INSTANTANEO-CONECTION'
        || $prize->getTypeExchange() == 'INSTANTANEO-HONDA') {
        $exchangeStatus = redeemPin($phone, $prize, $conn);
    }
    else {
        $exchangeStatus = redeemTransferTo($phone, $prize, $clientName);
    }

    return $exchangeStatus;
}

/**
 * Redemm prizes that are reloads of phone
 *
 * @param string $phone phone who is redeeming the prize
 * @param array $prize prize to redeem
 * @return array status(200: success, 500: error), code(only if success)
 */
function redeemReloads($phone, $prize) {
    // Only do conection recharges
    if ($prize['redemption_partner'] == 'CONECTION') {
        // Continue to make recharge
        $amount = ( int ) $prize['value'];
        // Get conection info
        $conAccount = 3;
        $bagName = 'CEMPRO';
        $url = "http://innovacion.ebfuture.net/premios/frontend_dev.php/call/service/name/recharge.php/phone/{$phone}/amount/{$amount}/account/{$conAccount}/shortcode/{$bagName}";

        // Use session helper for general functions
        $res = curlUrl($url);
        // Answer expected to be a json, inside a print_r
        $resObj = findJSONObject($res);
        if ($resObj && $resObj->status == 500) {
            // Error 
            return array(
                'status' => 500
            );
        }
        else {
            // Success
            return array(
                'status' => 200
            );
        }
    }

    // Error 
    return array(
        'status' => 500
    );
}

/**
 * Redemm max prizes
 *
 * @param string $phone phone who is redeeming the prize
 * @param Prize $prize prize to redeem
 * @return array status(200: success, 500: error), code(only if success)
 */
function redeemMax($phone, $prize) {
    $postArray = array(
        'promo_name' => 'CEMPRO',
        'subsidiary' => 'sin_sucursal',
        'prize' => $prize['name'],
        'code' => 'LYTMB',
        // The server receives the code and finishes it
        'phone' => $phone,
        'client_sku' => 'LOYALTY_CEMPRO'
    );

    // Consume url, using post
    $response = openPostUrl(
        'http://dev.ebfuture.net/canje_premios/settlement/createPromo',
        $postArray
    );

    // Extract code from response
    $code = getMaxCode($response);
    if ($code != null) {
        // Success, code received
        return array(
            'status' => 200,
            'code' => $code
        );
    }

    // Error
    return array(
        'status' => 500
    );
}

/**
 * Redemm prizes that use prize_pin table
 *
 * @param string $phone phone who is redeeming the prize
 * @param array $prize prize to redeem
 * @param MySqlConnection connection to database
 * @return array status(200: success, 500: error), code(only if success)
 */
function redeemPin($phone, $prize, $conn) {
    // Validate if there is a code to give (codes left in inventory)
    $pinCode = notUsedPrizePin($prize['id'], $conn);

    if (! $pinCode) {
        // Error on create code from conection, no codes available
        return array(
            'status' => 500
        );
    }

    // Success
    // Get code from conection
    $code = $pinCode['code'];

    // Store in database used pin
    updatePrizePin(-1, date('Y-m-d H:i'), $phone, $pinCode['prize_pin_id'], $conn);

    return array(
        'status' => 200,
        'code' => $code
    );
}

/**
 * Redeem transferto prizes
 *
 * @param string $phone phone who is redeeming the prize
 * @param array $prize prize to redeem
 * @return array status(200: success, 500: error), code(only if success)
 */
function redeemTransferTo($phone, $prize, $clientName) {
    $secret = md5(date("Y-m-d"));
    $productId = $prize['product_id'];

    // Use url to get code
    $url = "http://innovacion.ebfuture.net/premios/frontend_dev.php/call/service/name/consume_voucher.php/product_id/{$productId}/first_name/{".urlencode($clientName)."}/last_name/NA/phone/{$phone}/secret/{$secret}/account/2";

    // Consume url
    $res = curlUrl($url);
    // Default value for is successful response
    $ts = false;

    // Try to read JSON from response
    $resObj = findJSONObject($res);
    if ($resObj !== null) {
        // Check that exchange was succesful
        $ts = isTransactionSuccessful($resObj);
    }

    if ($ts === false) {
        // Error
        return array(
            'status' => 500
        );
    }

    // Get code from exchange response
    $code = getExchangeCode($resObj);

    // If no code was found, give error
    if ($code === null) {
        // Error
        return array(
            'status' => 500
        );
    }

    // Success
    return array(
        'status' => 200,
        'code' => $code
    );
}