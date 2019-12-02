<?php
namespace AppBundle\Helper;

use AppBundle\Helper\SessionHelper;

class RedeemHelper
{
    private $em;
    private $sessionHelper;
    private $conectionBagName;
    private $conectionRechargeAccount;
    private $errorCanjeSms;

    public function __construct(
        \Doctrine\ORM\EntityManager $em,
        $conectionBagName,
        $conectionRechargeAccount,
        $errorCanjeSms) {
        $this->em = $em;
        $this->conectionBagName =  $conectionBagName;
        $this->conectionRechargeAccount = $conectionRechargeAccount;
        $this->errorCanjeSms = $errorCanjeSms;
        $this->sessionHelper = new SessionHelper();
    }

    /**
     * After the prize was redeem at a database label, send SMS with exchange information
     *
     * @param array $exchangeInfo [status: 200 (success)| 500 (error), code: prize code if applicable]
     * @param Staff $staff staff who made the exchange
     * @param Prize $prize prize that was exchanged
     * @param string $channel channel where the exchange was made
     * @param string $connection connection where SMS was received
     */
    public function redeemSms ($exchangeInfo, $staff, $prize, $channel, $connection){
        $status = $exchangeInfo['status'];
        if ($status == 200) {
            // Success on prize exchange
            $smsResponse = $prize->getSmsResponse();

            if ($prize->getTypeExchange() == 'RECARGA') {
                // Add random end to string
                $smsResponse .= " r." . rand(1,1500);
            }
            else {
                $code = $exchangeInfo['code'];

                // Every other type of exchange, asume it requires and has code
                $smsResponse = str_replace("[CODE]" , $code, $smsResponse);
            }
        }
        else {
            // Error while exchanging prize
            $smsResponse = $this->errorCanjeSms;
        }

        // Send sms by the correct channel
        $phone = $staff->getAreaCode() . $staff->getPhoneMain();
        if($channel == "SMS"){
            $this->sessionHelper->send_sms($phone, $smsResponse , $connection);
        }else {
            $this->sessionHelper->send_sms_televida($phone , $smsResponse);
        }
    }

    /**
     * After the prize was redeem by a promo. Send SMS
     *
     * @param array $exchangeInfo [status: 200 (success)| 500 (error), code: prize code if applicable]
     * @param string $phone phone who made the exchange
     * @param Prize $prize prize to give
     */
    public function redeemPromoSms ($exchangeInfo, $phone, $prize){
        $status = $exchangeInfo['status'];
        if ($status == 200) {
            // Success on prize exchange
            $smsResponse = 'Felicidades CEMPRO te regala ' . $prize->getName();

            if ($prize->getTypeExchange() == 'RECARGA') {
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
            $smsResponse = 'CEMPRO Loyalty te informa que ocurrio un error al darte el premio';
        }

        // Send sms
        $this->sessionHelper->send_sms_televida($phone, $smsResponse);
    }

    /**
     * Consumes web service to make exchange and makes necessary database changes
     *
     * @param string $phone staff making the exchange
     * @param int $id id of staff to set pin.used_by
     * @param string $name name of staff making the exchange
     * @param Prize $prize prize to exchange
     * @return array [status: 200 (success) | 500 (error), code: code of prize if applicable]
     */
    public function redeem($phone, $id, $name, $prize) {
        if ($prize->getTypeExchange () == 'RECARGA') {
            $exchangeStatus = $this->redeemReloads($phone, $prize);
        }
        else if ($prize->getTypeExchange() == 'INSTANTANEO-MAX') {
            $exchangeStatus = $this->redeemMax($phone, $prize);
        }
        else if ($prize->getTypeExchange() == 'INSTANTANEO-CONECTION'
            || $prize->getTypeExchange() == 'INSTANTANEO-HONDA') {
            $exchangeStatus = $this->redeemPin($phone, $id, $prize);
        }
        else {
            $exchangeStatus = $this->redeemTransferTo($phone, $name, $prize);
        }

        return $exchangeStatus;
    }

    /**
     * Consumes web service to make exchange and makes necessary database changes
     *
     * @param Staff $staff staff making the exchange
     * @param Prize $prize prize to exchange
     * @param float $value value (cost) of exchage
     * @param string $channel channel of exchange (SMS|WEB)
     * @return array [status: 200 (success) | 500 (error), code: code of prize if applicable]
     */
    public function redeemDb($staff, $prize, $value, $channel) {
        $phone = $staff->getAreaCode() . $staff->getPhoneMain();

        $exchangeStatus = $this->redeem(
            $phone, $staff->getStaffId(), $staff->getName(), $prize
        );

        if ($exchangeStatus['status'] == 200) {
            $prizeExchange = $this->em->getRepository ( 'AppBundle:PrizeExchange' )
                ->redeemPrize ($prize->getId(), $staff->getStaffId(), $value, $channel);
        }

        return $exchangeStatus;
    }

    /**
     * Redemm prizes that are reloads of phone
     *
     * @param string $phone phone who is redeeming the prize
     * @param Prize $prize prize to redeem
     * @return array status(200: success, 500: error), code(only if success)
     */
    public function redeemReloads($phone, $prize) {
        // Only do conection recharges
        if ($prize->getRedemptionPartner () == 'CONECTION') {
            // Continue to make recharge
            $amount = ( int ) $prize->getValue ();
            // Get conection info
            $conAccount = $this->conectionRechargeAccount;
            $bagName = $this->conectionBagName;
            $url = "http://innovacion.ebfuture.net/premios/frontend_dev.php/call/service/name/recharge.php/phone/{$phone}/amount/{$amount}/account/{$conAccount}/shortcode/{$bagName}";

            // Use session helper for general functions
            $res = $this->sessionHelper->open_url ( $url );
            // Answer expected to be a json, inside a print_r
            $resObj = $this->sessionHelper->findJSONObject ( $res );
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
    public function redeemMax($phone, $prize) {
        $postArray = array(
            'promo_name' => 'CEMPRO',
            'subsidiary' => 'sin_sucursal',
            'prize' => $prize->getName(),
            'code' => 'LYTMB',
            // El servidor recibe ese c�digo y lo termina, agrega timestamp o algo
            'phone' => $phone,
            'client_sku' => 'LOYALTY_CEMPRO'
        );

        // Consume url, using post
        $response = $this->sessionHelper->openPostUrl(
            'http://dev.ebfuture.net/canje_premios/settlement/createPromo',
            $postArray
        );

        // Extract code from response
        $code = $this->sessionHelper->getMaxCode($response);
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
     * @param int $staffId id of staff to set as used by
     * @param Prize $prize prize to redeem
     * @return array status(200: success, 500: error), code(only if success)
     */
    public function redeemPin($phone, $staffId, $prize) {
        // Validate if there is a code to give (codes left in inventory)
        $pinCode = $this->em->getRepository('AppBundle:PrizePin')
            ->findOneBy(
                array(
                    'usedBy' => null,
                    'prizeId' => $prize->getId(),
                )
            );

        if (! $pinCode) {
            // Error on create code from conection, no codes available
            return array(
                'status' => 500
            );
        }

        // Success
        // Get code from conection
        $code = $pinCode->getCode();

        // Change info of conectionPin
        $pinCode->setUsedBy($staffId);
        $pinCode->setUsedAt(new \DateTime());
        $pinCode->setPhoneDeliveredTo($phone);

        // Store in database
        $this->em->persist($pinCode);
        $this->em->flush();

        return array(
            'status' => 200,
            'code' => $code
        );
    }

    /**
     * Redeem transferto prizes
     *
     * @param string $phone phone who is redeeming the prize
     * @param string $name name of who is redeeming the prize
     * @param Prize $prize prize to redeem
     * @return array status(200: success, 500: error), code(only if success)
     */
    public function redeemTransferTo($phone, $name, $prize) {
        $secret = md5(date("Y-m-d"));

        // Use url to get code
        $url = "http://innovacion.ebfuture.net/premios/frontend_dev.php/call/service/name/consume_voucher.php/product_id/{$prize->getProductId()}/first_name/{".urlencode($name)."}/last_name/NA/phone/{$phone}/secret/{$secret}/account/2";

        // Consume url
        $res = $this->sessionHelper->open_url($url);
        // Default value for is successful response
        $ts = false;

        // Try to read JSON from response
        $resObj = $this->sessionHelper->findJSONObject($res);
        if ($resObj !== null) {
            // Check that exchange was succesful
            $ts = $this->sessionHelper->isTransactionSuccessful($resObj);
        }

        if ($ts === false) {
            // Error
            return array(
                'status' => 500
            );
        }

        // Get code from exchange response
        $code = $this->sessionHelper->getExchangeCode($resObj);

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
}
?>