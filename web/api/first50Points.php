<?php
    /**
     * Sends a SMS using a resp API
     *
     * @param string $phone phone to send the sms to
     * @param string $message message to send
     * @return string response from API
     */
    function sendSmsTelevida($phone, $message){
        $url = "http://innovacion.ebfuture.net/premios/frontend_dev.php/call/service/name/sendsms.php/phone/$phone/msg/".urlencode($message)."/rest/true/account/6/";
        $response = openUrl($url);
        return $response;
    }

    /**
     * Sends a recharge to a phone
     *
     * @param string $phone phone to send the recharge to, with 502
     * @param integer $amount amount of rechage
     * @param integer $account account to take money for recharge?
     * @param string $bagName used in API
     * @return int if success sending recharge or not
     */
    function recharge($phone, $amount, $account = 3, $bagName = 'MASESA') {
        $url = "http://innovacion.ebfuture.net/premios/frontend_dev.php/call/service/name/recharge.php/phone/{$phone}/amount/{$amount}/account/{$account}/shortcode/{$bagName}";
        
        // Consume URL
        $res = openUrl ( $url );

        // Answer expected to be a json, inside a print_r
        $resObj = findJSONObject ( $res );
        if ($resObj && $resObj->status == 500) {
            // Error
            return -1;
        } else {
            return 1;
        }
    }

    function openUrl($url) {
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );

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

    // DEV
    // $conn = mysql_connect('192.168.10.36','loyalty_v2_user','l0y@ltyD3uV53r');
    // mysql_select_db('masesa_dev');
    
    // PROD
    $conn = mysql_connect('127.0.0.1','masesa_pusr','m@Mass_ltyD163uVSR');
    mysql_select_db('masesa_prod');

    // Check how first 50 prizes have been given
    $first50Q = "
        SELECT
            *
        FROM prize_exchange pe
        WHERE
            prize_id = 58;
    ";
    $first50R = mysql_query($first50Q, $conn);
    $cont = mysql_num_rows($first50R);
    $maxCont = 50;

    if ($cont > $maxCont) {
        // No need to give more
        exit;
    }

    // Get prize info
    $prizeQ = "
        SELECT
            *
        FROM
            prize
        WHERE
            id = 58
        LIMIT 1
    ";
    $sms = NULL;
    $prizeR = mysql_query($prizeQ, $conn);
    $prizeRows = mysql_num_rows($prizeR);
    if($prizeRows > 0) {
        $prizeRow = mysql_fetch_array($prizeR);
        $sms = $prizeRow['sms_response'];
    }

    $missing = $maxCont - $cont;

    // Else
    // Get all staff tha
    $staffQ = "
        SELECT
            s.*, sa.sku_code as sku_code
        FROM
            staff s
            INNER JOIN
                sale_staff ss ON (ss.staff_id = s.staff_id)
            INNER JOIN 
                sale sa ON (sa.sale_id = ss.sale_id)
        WHERE
            s.staff_id NOT IN (
                SELECT
                    s.staff_id
                FROM
                    staff s
                    INNER JOIN
                        prize_exchange pe ON (pe.staff_id = s.staff_id)
                WHERE
                    pe.prize_id = 58
            )
            AND ss.was_seller = 1
        ORDER BY
            ss.created_at
    ";

    $staffR = mysql_query($staffQ, $conn);
    $staffRows = mysql_num_rows($staffR);
    $hasPrize = array();
    if($staffRows > 0) {
        while($row = mysql_fetch_array($staffR)) {
            // Check it doesn't exceed max available
            if ($cont > $maxCont) {
                // No need to give more
                exit;
            }

            // For each staff found check if it doesn't have this prize
            $staffId = $row['staff_id'];

            if (!in_array($staffId, $hasPrize)) {
                // Variables
                $phone = "502" . $row['phone_main'];

                // Change sms to send
                $sms = str_replace('[CODE]', $row['sku_code'], $sms);

                // Send recharge
                $isRecharged = recharge($phone, 10);

                if ($isRecharged > 0) {
                    // Send SMS
                    sendSmsTelevida($phone, $sms);

                    // Insert new exchange
                    $insertQ = "
                        INSERT INTO prize_exchange
                            (prize_id, staff_id, redeem_points, channel_exchange, created_at)
                        VALUES
                            (58, $staffId, 0.0, 'WEB', NOW())
                    ";
                    mysql_query($insertQ,$conn);

                    // Add how many have been given the prize
                    $cont++;

                    echo "Recharged phone: " . $phone;
                }

                array_push($hasPrize, $staffId);
            }
        }
    }
    exit;