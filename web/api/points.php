<?php
ini_set("display_errors", true);

include 'redeemHelper.php';

function open_url ($phone, $msg)
{       
    
    $ch = curl_init(); // initiate curl
    $msg .= " r." . substr(md5(microtime()),rand(0,26), 3);
    $token = "NTQ5ODg1MTYzMA";
    $url = "http://192.168.10.221/service-container/call/18/amount/1/phone/{$phone}/token/{$token}/msg/".urlencode($msg)."/";
    //$url = "http://innovacion.ebfuture.net/premios/frontend_dev.php/call/service/name/sendsms.php/phone/502$phone/rest/true/account/6/msg/" . urlencode($msg);
    curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_POST, false); // tell curl you want to post
    // something
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the output in
                                                    // string format
    $output = curl_exec($ch); // execute
    
    curl_close($ch); // close curl handle
                          // echo $output;
                          // var_dump($output); // show output
                            
                           
    
    return true;
}

function updateThis ($id, $text, $conn, $alreadyParsed = 1)
{
    $query = "UPDATE sms_incoming SET parse_result = '$text', already_parsed = $alreadyParsed  WHERE sms_incoming_id = '$id'";
    
    if ($result = mysql_query($query,$conn)) {
        return true;
    } else {
        return false;
    }
    
    return true;
   
}

function getPoints ($jobPositionId, $cc, $conn, $filterGroupId = false, $quantity = false)
{
   
    if ($filterGroupId) {
        $query = "SELECT * FROM reward_criteria WHERE filter_group_id = '$filterGroupId'";
    
    } else {
        $query = "SELECT * FROM reward_criteria WHERE job_position_id = '$jobPositionId'";
    }
    
    $result = mysql_query($query,$conn) or die(mysql_error());
    $row = mysql_fetch_array($result);
    
    $calculated = 0;
    if (strlen($row['mathematical_operator']) > 0) {
        // REDONDEAR HACIA ABAJO
        if ($quantity)
            $calculated = floor($quantity * $row['mathematical_operator']);
        else
            $calculated = floor($cc * $row['mathematical_operator']);
            
    }
    
    return $calculated;
}

/*
 * Agregar suma de puntos promocionales
 *
 */
function getCurrentPoints ($staffId, $conn)
{
    $query = "SELECT sum(s.points) as sale_sum,
		if((SELECT sum(p.redeem_points) FROM prize_exchange p WHERE p.staff_id = s.staff_id),  (SELECT sum(p.redeem_points) FROM prize_exchange p WHERE p.staff_id = s.staff_id), 0) as redeem_sum
		FROM sale_staff s WHERE s.staff_id = '$staffId'";
    $result = mysql_query($query,$conn) or die(mysql_error());
    $array = array();
    $row  = mysql_fetch_assoc($result);
    
    $sale_sum = $row['sale_sum'];
    $redeem_sum = $row['redeem_sum'];
    
    $result = $sale_sum - $redeem_sum;
    if ($result < 0) {
        $result = 0;
    }
    
    return $result;
}

/**
 * Get staff from related points of sale of a staff
 *
 * @param int $staffId
 *            id of staff to look into his points of sale
 * @param array $higerJobPos
 *            job_poistion.id above in the hierarchy of the staff
 * @return array staff table information
 */
function getStaffPointOfSale ($staffId, $higherJobPos, $conn)
{
    // Check that array is not empty
    if (! $higherJobPos || sizeof($higherJobPos) == 0) {
        // Job position that doesn't exist, won't be found
        $higherJobPos = array(
                - 1
        );
    }
    
    $query = "
      SELECT
        s.*
      FROM
        staff_point_of_sale p, staff s
      WHERE
        p.point_of_sale_id IN (
          SELECT
            point_of_sale_id
          FROM
            staff_point_of_sale 
          WHERE
            staff_id = $staffId
            #AND status = 'ACTIVE'
        )
        AND s.staff_id = p.staff_id
        #AND p.status = 'ACTIVE'
        AND (
          s.job_position_id IN (" .
             join(', ', $higherJobPos) . ")
          OR s.staff_id = $staffId
        )";
    
             
             
    $result = mysql_query($query,$conn) or die(mysql_error());
    $array = array();
    while($row  = mysql_fetch_assoc($result)) {
        $array[] = $row;
    }
    
    return $array;
}

/**
 * Get all the job positions that are higher in the hierarchy
 *
 * @param int $jobPositionId
 *            job position to look for parents
 * @param MySqlConnection $conn
 *            connection to database
 * @return array job position ids who are higher in the hierarchy
 */
function getHigherJobPositions ($jobPositionId, $conn)
{
    $query = "
      SELECT
        parent_id as id
      FROM
        job_position_parent
      WHERE
        child_id = " . $jobPositionId . "
    ";
    
    // Make query and get results
    $result = mysql_query($query,$conn) or die(mysql_error());
    $return = array();
    while($row  = mysql_fetch_assoc($result)) {
        $jpId = $row['id'];
        $return[] = $jpId;
        
        // Look for this job position's parents
        $jpIds = getHigherJobPositions($jpId, $conn);
        
        $return = array_unique(array_merge($return, $jpIds));
    }
    
    return $return;
}

/**
 * Get points of sale information of a staff_id
 *
 * @param integer $staffId
 *            integer to use as staff_id
 * @param MySqlConnection $conn
 *            connection to database
 * @return array result of query
 */
function getPointsOfSale ($staffId, $conn)
{
    $query = "
        SELECT
            pos.point_of_sale_id, pos.sale_channel_id, pos.state_id, pos.city_id
        FROM
            point_of_sale pos
            INNER JOIN staff_point_of_sale spos ON (spos.point_of_sale_id = pos.point_of_sale_id)
        WHERE
            spos.staff_id = $staffId
            #AND spos.status = 'ACTIVE';
    ";
    
    // Make query and get results
    $result = mysql_query($query,$conn) or die(mysql_error());
    $return = array();
    while($row  = mysql_fetch_assoc($result)) {
        $return[] = $row;
    }
    
    return $return;
}

/**
 * Groups array of array keys
 *
 * @param array(array()) $arrays
 *            array of arrays to separate
 * @return array(array()) grouped keys of array
 */
function separateArrays ($arrays)
{
    $separated = array();
    
    foreach ($arrays as $array) {
        foreach ($array as $key => $value) {
            if (! array_key_exists($key, $separated)) {
                // Create array for this key
                $separated[$key] = array();
            }
            
            // Add value to key in separated array
            array_push($separated[$key], $value);
        }
    }
    
    return $separated;
}

/**
 * Gets the attribute of a promo, looking into another table
 *
 * @param string $table
 *            table name
 * @param string $column
 *            column that represents attribute
 * @param integer $promoId
 *            id of promo
 * @param MySqlConnection $conn
 *            connection to database
 * @return array query result
 */
function getPromoAttrs ($table, $column, $promoId, $conn)
{
    $query = "
        SELECT
            $column
        FROM
            $table
        WHERE
            promo_id = $promoId
    ";
    
    // Make query and get results
    $result = mysql_query($query,$conn) or die(mysql_error());
    $attrs = array();
    while($row  = mysql_fetch_assoc($result)) {
        $attrs[] = $row[$column];
    }
    
    return $attrs;
}

/**
 * Get the 'promo_category.promo_category_id's that are used in this file
 *
 * @return array ids of promo_category that should be used in this file
 */
function getValidPromoCategories ()
{
    // Based on a table that doesn't change
    return array(
            1,
            8
    );
}

/**
 * Get active promos based on staff
 *
 * @param MySqlConnection $conn
 *            connection to database
 * @param string $date
 *            date when the promo should be active
 * @return array promos info
 */
function getPromos ($conn, $date)
{
    // Get ids of promo_category(s) to look for
    $catIds = getValidPromoCategories();
    
    // Find promos with primary fields
    $query = "
        SELECT
            p.promo_id, p.name, p.status, p.start_date, p.end_date, p.promo_category_id
        FROM
            promo p
        WHERE
            p.status = 'ACTIVE'
            AND '" . $date . "' BETWEEN p.start_date AND p.end_date
            AND p.promo_category_id IN (" .
             join(', ', $catIds) . ")
    ";
    
    $result = mysql_query($query,$conn) or die(mysql_error());
    $promos = array();
    while($row  = mysql_fetch_assoc($result)) {
        $promos[] = $row;
    }
    
    return $promos;
}

/**
 * Apply filters to a group of promos
 *
 * @param array $promos
 *            promo_id, name, status, start_date, end_date, promo_category_id
 * @param int $staffId
 *            id of staff
 * @param int $jobPositionId
 *            id of job_position of staff
 * @param string $cc
 *            cc of sale made
 * @param MySqlConnection $conn
 *            connection to database
 * @return array promo that remain after applying the filters
 */
function applyFilters ($promos, $staffId, $jobPositionId, $cc, $conn)
{
    // Get points of sale info
    $pointsOfSaleInfo = getPointsOfSale($staffId, $conn);
    ;
    
    // Separate points of sale info
    $sPosInfo = separateArrays($pointsOfSaleInfo);
    $posIds = $sPosInfo['point_of_sale_id'];
    $scIds = $sPosInfo['sale_channel_id'];
    
    // Apply other filters
    $validPromos = $promos;
    foreach ($promos as $key => $promo) {
        $removed = false;
        
        // Points of sale
        if (! $removed) {
            // Try to apply filter of points of sale
            $poss = getPromoAttrs('promo_point_of_sale', 'point_of_sale_id',
                    $promo['promo_id'], $conn);
            if (sizeof($poss) > 0) {
                // Apply poss filter
                $intersection = array_intersect($posIds, $poss);
                if (sizeof($intersection) == 0) {
                    // If no intersection, promo is not a valid for this
                    // staff_sale
                    unset($validPromos[$key]);
                    $removed = true;
                }
            }
        }
        
        // Sale channels
        if (! $removed) {
            // Try to apply filter of sale channels
            $saleChannels = getPromoAttrs('promo_sale_channel',
                    'sale_channel_id', $promo['promo_id'], $conn);
            if (sizeof($saleChannels) > 0) {
                // Apply saleChannels filter
                $intersection = array_intersect($scIds, $saleChannels);
                if (sizeof($intersection) == 0) {
                    // If no intersection, promo is not a valid for this
                    // staff_sale
                    unset($validPromos[$key]);
                    $removed = true;
                }
            }
        }
        
        // Get cc
        if (! $removed) {
            $ccs = getPromoAttrs('promo_cc', 'cc', $promo['promo_id'], $conn);
            if (sizeof($ccs) > 0) {
                // Apply cc filter
                if (! in_array($cc, $ccs)) {
                    // If not in array, promo is not a valid for this staff_sale
                    unset($validPromos[$key]);
                    $removed = true;
                }
            }
        }
        
        // City
        if (! $removed) {
            // Try to apply filter of city
            $posCities = $sPosInfo['city_id'];
            $cities = getPromoAttrs('promo_city', 'city_id', $promo['promo_id'],
                    $conn);
            if (sizeof($cities) > 0) {
                // Apply cities filter
                $intersection = array_intersect($posCities, $cities);
                if (sizeof($intersection) == 0) {
                    // If no intersection, promo is not a valid for this
                    // staff_sale
                    unset($validPromos[$key]);
                    $removed = true;
                }
            }
        }
        
        // State
        if (! $removed) {
            // Try to apply filter of state
            $posStates = $sPosInfo['state_id'];
            $states = getPromoAttrs('promo_state', 'state_id',
                    $promo['promo_id'], $conn);
            if (sizeof($states) > 0) {
                // Apply states filter
                $intersection = array_intersect($posStates, $states);
                if (sizeof($intersection) == 0) {
                    // If no intersection, promo is not a valid for this
                    // staff_sale
                    unset($validPromos[$key]);
                    $removed = true;
                }
            }
        }
        
        // Job Position
        if (! $removed) {
            // Try to apply filter of job_position
            $jps = getPromoAttrs('promo_job_position', 'job_position_id',
                    $promo['promo_id'], $conn);
            if (sizeof($jps) > 0) {
                // Apply job_position filter
                if (! in_array($jobPositionId, $jps)) {
                    // If no intersection, promo is not a valid for this
                    // staff_sale
                    unset($validPromos[$key]);
                    $removed = true;
                }
            }
        }
    }
    
    return $validPromos;
}

/**
 * Inserts a staffs promo points
 *
 * @param integer $staffId
 *            id of staff
 * @param integer $promoId
 *            id of promo
 * @param integer $saleId
 *            id of sale
 * @param integer $points
 *            points to give staff
 * @param MySqlConnection $conn
 *            connection to database
 */
function insertPromoPoints ($staffId, $promoId, $saleId, $points, $conn)
{
    $query = "
      INSERT INTO staff_promo_points
        (staff_id, promo_id, sale_id, points, created_at)
      VALUES
        ($staffId, $promoId, $saleId, $points, NOW())
    ";
    
    $result = mysql_query($query,$conn);
}

/**
 * Sends a SMS to a staff notifing him that he just won promotional points
 * Assumes that the points have already been given to the staff
 *
 * @param string $phone
 *            phone of staff
 * @param int $pPoints
 *            points added by promotion
 * @param string $skuCode
 *            database's sale.sku_code of sale registered to staff
 * @param MySqlConnection $conn
 *            connection to database
 */
function promoPointsSms ($phone, $pPoints, $skuCode, $conn)
{
    // Check that required information is received
    if (! $phone || ! $pPoints || ! $skuCode) {
        // Insuficient information to send promo message
        return;
    }
    
    // Fixed message to send
    //$sms = 'Felicidades, has ganado [POINTS] puntos promocionales, con la venta registrada No. [SKU_CODE] !';
    $sms = 'CEMENTOS PROGRESO, Club Del Constuctor: Felicidades, has ganado [POINTS] puntos promocionales, con la venta registrada No. [SKU_CODE] !';
    
    // Replace information received in fixed message
    $sms = str_replace('[POINTS]', $pPoints, $sms);
    $sms = str_replace('[SKU_CODE]', $skuCode, $sms);
    
    // Send message
    open_url($phone, $sms);
}

/**
 * Get from database the prizes for a promo
 *
 * @param int $promoId
 *            id of promo to get the prizes
 * @param MySqlConnection $conn
 *            connection to database
 * @return array all columns from found promo_prize
 */
function getPromoPrizes ($promoId, $conn)
{
    // Find promos with primary fields
    $query = "
        SELECT
          *
        FROM
          promo_prize pp
        WHERE
          pp.promo_id = " . $promoId . "
          AND pp.promo_prize_status_id = 1
    ";
    
    $result = mysql_query($query,$conn) or die(mysql_error());
    
    // Store prizes in array
    $promoPrizes = array();
    while($row  = mysql_fetch_assoc($result)) {
        $promoPrizes[] = $row;
    }
    
    return $promoPrizes;
}

/**
 * Give prize related to a promo to a staff
 *
 * @param int $staffId
 *            id of staff to give prize to
 * @param float $points
 *            points to use as base of prize
 * @param int $promoId
 *            id of promo to get what prize to give
 * @param int $saleId
 *            id of sale that gave this prize
 * @param MySqlConnection $conn
 *            connection to database
 * @return int points given to staff. -1 if no points given
 */
function givePromoStaffPrize ($staffId, $points, $promoId, $saleId, $conn, $quantity)
{
    // Look for promo prizes in the database
    $promoPrizes = getPromoPrizes($promoId, $conn);
    
    foreach ($promoPrizes as $promoPrize) {
        // Apply points of promo
        $promoType = $promoPrize['promo_prize_type_id'];
        
        $pPoints = 0;
        if ($promoType == 1) {
            // Add unit points
            $pPoints = (int) $promoPrize['points'];
        } else 
            if ($promoType == 2) {
                // Expected a factor
                /*
                $ceilPoints = ceil($points);
                $pPoints = ((int) $promoPrize['factor']) * $ceilPoints -
                         $ceilPoints;
                */
                $pPoints = $promoPrize['factor'] * $quantity;
                
            }
        
        // Insert promotional points with pPoints
        if ($pPoints > 0) {
            insertPromoPoints($staffId, $promoId, $saleId, $pPoints, $conn);
            
            return $pPoints;
        }
    }
    
    return - 1;
}

/**
 * Groups promos by their categoryId
 *
 * @param array $promos
 *            promo information, needs to have at least 'promo_category_id'
 * @return array promos grouped by category_id. The of the array is the
 *         'promo_category_id'
 *         array(
 *         1 => array(
 *         promo1,
 *         promo2
 *         )
 *         )
 */
function splitPromosByCategory ($promos)
{
    $splittedPromos = array();
    
    foreach ($promos as $promo) {
        $catId = $promo['promo_category_id'];
        
        if (array_key_exists($catId, $splittedPromos)) {
            array_push($splittedPromos[$catId], $promo);
        } else {
            $splittedPromos[$catId] = array(
                    $promo
            );
        }
    }
    
    return $splittedPromos;
}

/**
 * Give prize related to a promo to a staff
 *
 * @param int $promoId
 *            id of promo to get what prize to give
 * @param int $saleId
 *            id of sale to store in client_promo_prize
 * @param string $clientPhone
 *            phone to send prize to
 * @param string $clientName
 *            name client's name to give prize
 * @param MySqlConnection $conn
 *            connection to database
 */
function giveClientPrize ($promoId, $saleId, $clientPhone, $clientName, $conn)
{
    // Use prizes probabilities to pick a prize
    $promoPrize = getProbabilityPrize($promoId, $conn);
    
    if (! $promoPrize) {
        return;
    }
    
    // Check if client hasn't reached max quantity of prize
    $max = $promoPrize['max_quantity'];
    if (clientReachedMax($max, $clientPhone, $promoPrize['promo_prize_id'],
            $conn)) {
        return;
    }
    
    // Notify client of the prize that was choosen
    open_url($clientPhone, $promoPrize['notification_message']);
    
    // Decide how to give prize
    $prizeStatus = 0;
    $promoPrizeTypeId = $promoPrize['promo_prize_type_id'];
    
    if ($promoPrizeTypeId == 3) {
        // Just send SMS
        $message = $promoPrize['name'];
        
        // Add random end to string
        $message .= " r." . rand(1, 1500);
        
        // Send message
        open_url($clientPhone, $message);
        $prizeStatus = 2;
    } else 
        if ($promoPrizeTypeId == 4) {
            // Get prize information
            $prize = getPrize($promoPrize['prize_id'], $conn);
            if ($prize) {
                // Give the prize to client
                // Send phone with area code
                $phone = "502" . $clientPhone;
                $status = clientRedeem($phone, $prize, $conn, $clientName);
                
                // Send sms of given prize
                redeemSms($status, $phone, $prize);
                
                // Store what was given
                if ($status['status'] == 200) {
                    // Prize was given
                    $prizeStatus = 2;
                } else {
                    // Error while givin prize
                    $prizeStatus = 3;
                }
            }
        }
    
    // If there was prize to give, store in database
    if ($prizeStatus != 0) {
        insertClientPrize($promoId, $clientPhone, $promoPrize, $prizeStatus,
                $saleId, $conn);
    }
}

/**
 * Gets a prize information from database
 *
 * @param int $prizeId
 *            id of prize to get
 * @param MySqlConnection $conn
 *            connection to database
 * @return array prize information
 */
function getPrize ($prizeId, $conn)
{
    $query = "
        SELECT
            *
        FROM
            prize
        WHERE
            id = " . $prizeId . "
        LIMIT 1
    ";
    
    $result = mysql_query($query,$conn) or die(mysql_error());
    $array = array();
    while ($row  = mysql_fetch_assoc($result)) {
        return $row;
    }
    
    return NULL;
}

/**
 * Gets a prize information from database
 *
 * @param int $promoId
 *            id of promo
 * @param string $clientPhone
 *            phone of client who received the prize
 * @param array $promoPrize
 *            promo_prize information
 * @param int $prizeStatusId
 *            id of the status of the prize
 * @param int $saleId
 *            id of sale
 * @param MySqlConnection $conn
 *            connection to database
 * @return array prize information
 */
function insertClientPrize ($promoId, $clientPhone, $promoPrize, $prizeStatusId,
        $saleId, $conn)
{
    // Transform prize_id and name
    if ($promoPrize['prize_id'] == NULL) {
        $promoPrize['prize_id'] = 'NULL';
    }
    
    if ($promoPrize['name'] == NULL) {
        $promoPrize['name'] = 'NULL';
    } else {
        $promoPrize['name'] = "'" . $promoPrize['name'] . "'";
    }
    
    $query = "
      INSERT INTO client_promo_prize
        (client_phone, promo_id, prize_id, sale_id, promo_prize_id, message,
          client_promo_prize_status_id, created_at)
      VALUES
        (
          '" . $clientPhone . "',
          " . $promoId . ",
          " . $promoPrize['prize_id'] . ",
          " . $saleId . ",
          " . $promoPrize['promo_prize_id'] . ",
          " . $promoPrize['name'] . ",
          " . $prizeStatusId . ",
          NOW()
        )
    ";
    
    $result = mysql_query($query, $conn);
}

/**
 * Checks if a client has reached a maximum of a promo_prize
 *
 * @param int $max
 *            max quantity of prizes by client
 * @param string $phone
 *            phone of the client to get prizes won
 * @param int $promoPrizeId
 *            id promo prize to check quantity
 * @param MySqlConnection $conn
 *            connection to database
 * @return boolean if max has been reached by phone
 */
function clientReachedMax ($max, $phone, $promoPrizeId, $conn)
{
    // Get a count of all the prizes won by the client
    $wonPrizes = countClientPrizes($phone, $promoPrizeId, $conn);
    
    return $wonPrizes >= $max;
}

function countClientPrizes ($phone, $promoPrizeId, $conn)
{
    // Find promos with primary fields
    $query = "
        SELECT
          COUNT(client_promo_prize_id) as count
        FROM
          client_promo_prize
        WHERE
          client_phone = '" . $phone . "'
          AND promo_prize_id = " . $promoPrizeId . "
    ";
    
    $result = mysql_query($query, $conn) or die(mysql_error());
    
    // Get just the number
    while ($row = mysql_fetch_assoc($result)) {
        return (int) $row['count'];
    }
    
    return 0;
}

/**
 * Get from database a prize for promo using their probability
 *
 * @param int $promoId
 *            id of promo to get the prizes
 * @param MySqlConnection $conn
 *            connection to database
 * @return array a row from promo_prize. NULL if no prize was picked
 */
function getProbabilityPrize ($promoId, $conn)
{
    // Get all the prizes for promo
    $promoPrizes = getPromoPrizes($promoId, $conn);
    
    // Get a random number
    $random = randomFloat();
    
    // Check probability to pick a prize
    $min = 0;
    $max = 0;
    foreach ($promoPrizes as $promoPrize) {
        $probability = $promoPrize['probability'];
        $max += $probability;
        
        if ($random >= $min && $random <= $max) {
            return $promoPrize;
        }
        
        // Move inferior limit
        $min += $probability;
    }
    
    return NULL;
}

/**
 * Generate a random number between 0 and 1
 *
 * @return float random number
 */
function randomFloat ()
{
    // returns random number with flat distribution from 0 to 1
    return (float) rand() / (float) getrandmax();
}

/*
$conn = mysql_connect('127.0.0.1','root','');
mysql_select_db('loyalty_cempro');
*/


$conn = mysql_connect('127.0.0.1','lcempro_usr','loyalty_cempro_pwd');
  mysql_select_db('loyalty_cempro');
  

$query = "
    SELECT
      t.phone_main, t.job_position_id, s.*
    FROM
      sms_incoming s, staff t
    WHERE
      t.staff_id = s.staff_id
      AND already_parsed = 0
  ";
$result = mysql_query($query,$conn);

while ($row = mysql_fetch_array($result)) {    
    
    $points = 0;
    // Revisamos si existe el numero de motor
    $select = "SELECT * FROM sale WHERE sku_code = '" . $row['sms_string'] . "'";
    $resultSKU = mysql_query($select,$conn);
    $num    = mysql_num_rows($resultSKU);
    if ($num > 0) {
        // Si existe el SKU entonces se procede a revisar si alguien mas no lo
        // ha reportado
        $row_select = mysql_fetch_array($resultSKU);
        $saleId = $row_select['sale_id'];
        $filterGroupId = $row_select['filter_group_id'];
        $quantitySale = $row_select['quantity'];
        $invoiceSale = $row_select['invoice_number'];
        
        $select2 = "
        SELECT
          *
        FROM
          sale_staff
        WHERE
          sale_id = '" . $saleId . "'
          AND is_cancelled = 0
        ";
        
        
        
        $result2 = mysql_query($select2, $conn);
        $num2 = mysql_num_rows($result2);
        
        if ($num2 > 0) { 
            $phone = $row['phone_main'];
            $msg = 'LICORERA DE GUATEMALA: La factura reportada ya existe en el sistema. Para mas informacion llama al 0000-0000';
            open_url($phone, $msg);
            
            // Ya existe venta reportada
            //echo "Ya existe una venta con este SKU\n";
            $text = "Ya existe una venta reportada con este numero de factura";
            updateThis($row['sms_incoming_id'], $text, $conn);
            continue;
        } else {
            
            $select3 = "SELECT * FROM sku WHERE sku_id = '" . $row_select['sku_id'] . "'";                                 
                     
            $result3 = mysql_query($select3, $conn);
            $num3 = mysql_num_rows($result3);
            
            if ($num3 > 0) {
                                
                $sku_info = mysql_fetch_array($result3);
                
                // Si existe el SKU se procede con el registro
                $staffId = $row['staff_id'];
                $skuId = $sku_info['sku_id'];
                $cc = $sku_info['cc'];
                
                // Get the higher job positions in the hierarchy
                $jpId = $row['job_position_id'];
                                
                
                $jpIds = getHigherJobPositions($jpId, $conn);                               
                                                
                $list = getStaffPointOfSale($staffId, $jpIds, $conn);                                                         
                
                $count = count($list) - 1;
                
                
                
                // Get all promos available
                $promos = getPromos($conn, $row['created_at']);
                                
                
                for ($i = 0; $i <= $count; $i ++) {
                    $staff = $list[$i]['staff_id'];
                    $wasSeller = 0;
                    
                    if ($staffId == $staff) {
                        $wasSeller = 1;
                    }
                                        
                    
                    $points = getPoints($list[$i]['job_position_id'], $cc, $conn, $filterGroupId, $quantitySale);
                    
                    
                    $query_points = "INSERT INTO sale_staff (staff_id,sale_id,sku_id,points,was_seller,created_at,created_by)
						VALUES('$staff','$saleId','$skuId','$points','$wasSeller',now(),'$staffId')";
                    $result_points = mysql_query($query_points,$conn);
                    
                    // Based on promos apply filters
                    $validPromos = applyFilters($promos, $staff,
                            $list[$i]['job_position_id'], $cc, $conn);
                    
                    foreach ($validPromos as $promo) {
                        $catId = $promo['promo_category_id'];
                        
                        if ($catId == 1) {
                            // Give prizes of promo
                            $pointsGiven = givePromoStaffPrize($staff, 0,
                                    $promo['promo_id'], $saleId, $conn, $quantitySale);
                            
                            // If prize was given to staff, send sms
                            if ($pointsGiven != - 1) {
                                // Information to send message
                                $phone = $list[$i]['phone_main'];
                                //$skuCode = $row['sms_string'];
                                $skuCode = $invoiceSale;        
                                
                                // agregar puntos en AccruedPointDetails    
                                $query_accrued = "INSERT INTO accrued_point_details (sale_id, accrued_points, available_points, staff_id, created_at, point_type_id)
						              VALUES('$saleId','$pointsGiven','$pointsGiven','$staff', now(), 7)";
                                $result_accrued = mysql_query($query_accrued, $conn);
                                
                                // Send SMS notifing that staff just won promo
                                // points
                                promoPointsSms($phone, $pointsGiven, $skuCode,
                                        $conn);
                            }
                        } else 
                            if ($wasSeller == 1 && $catId == 8) {
                                // Can only give prize if the client has a phone
                                if ($row_select['client_phone'] != NULL) {
                                    // Give the client the prize, only when
                                    // staff is seller
                                    giveClientPrize($promo['promo_id'], $saleId,
                                            $row_select['client_phone'],
                                            $row_select['client_name'], $conn);
                                }
                            }
                    }
                    
                    // agregar puntos en AccruedPointDetails                                                
                    
                    $query_accrued = "INSERT INTO accrued_point_details (sale_id, accrued_points, available_points, staff_id, created_at, point_type_id)
						VALUES('$saleId','$points','$points','$staff', now(), 3)";
                    
                    $result_accrued = mysql_query($query_accrued, $conn);
                                                           
                    if ($staffId == $staff) {
                        $phone = $row['phone_main'];
                        $current = getCurrentPoints($staffId, $conn);
                        $msg = 'CEMENTOS PROGRESO: Tu compra Mixto Listo No. ' .
                                 $invoiceSale .
                                 ' ha sido confirmada. Acumulaste ' .
                                 $points . ' puntos. Tu nuevo saldo es ' .
                                 $current .
                                 ' puntos. Mas informacion llama al 1-801-426-5515.';
                        // echo $msg;
                        open_url($phone, $msg);
                        
                        // MANDAR URL;
                    }
                }
                
                $material_number = $row_select['sku_filter_string'];
                $brand = $sku_info["brand"];
                updateThis($row['sms_incoming_id'], 'OK - ' . $points . ' - ' . $brand, $conn);
                
                
            } else {
                $phone = $row['phone_main'];
                // $msg = 'LOYALTY CEMPRO: La venta de la moto
                // '.substr($row['sms_string'],0,15).' no pudo confirmarse. Para
                // mas informacion llama al 2429-2945.';
                // open_url($phone,$msg);
                
                $text = "SKU no existe";
                echo $text . "\n";
                updateThis($row['sms_incoming_id'], $text, $conn, 0);
                continue;
            }
        }
    } else {
        $phone = $row['phone_main'];
        // $msg = 'LOYALTY CEMPRO: La venta de la moto
        // '.substr($row['sms_string'],0,15).' no pudo confirmarse. Para mas
        // informacion llama al 2429-2945.';
        // open_url($phone,$msg);
        
        //echo "No existe venta reportada (SKU CODE)\n";
        $text = "No existe venta reportada";
        updateThis($row['sms_incoming_id'], $text, $conn, 0);
        continue;
    }
}
;
?>