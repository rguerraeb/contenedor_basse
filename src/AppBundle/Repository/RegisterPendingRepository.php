<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\RegisterPending;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class RegisterPendingRepository extends EntityRepository 
{
    /**
     * Gets Active list of registers pending
     *
     * @param integer $status status of register
     * @param string $phoneMain phoneMain of registers
     * @param string $citizenId citizen id of registers
     * @param string $name name of register
     * @return array() List of active users
     */
    public function getList($status, $phoneMain, $citizenId, $name){
        $query = "SELECT 
        			(  SELECT count(*) FROM register_pending t2 WHERE t2.staff_id = t1.staff_id  AND date(t2.created_at) >= date(NOW() - interval 2 day) ) As controlCount,
        			t1.register_pending_id as registerPendingId,
        			p.business_name as pointOfSale, t1.device_uuid as deviceUuid,
        			t1.staff_id as staffId, t1.name as Name, t1.first_name, t1.last_name, t1.tax_identifier as taxIdentifier,
        			t1.citizen_id as citizenId, t1.gender as Gender, t1.birthdate as Birthdate, t1.phone_secondary as phoneSecondary, 
        			t1.phone_main as phoneMain, t1.email as Email, t1.state_id as stateId, t1.city_id as cityId, t1.country_id as countryId, 
        			t1.zone as Zone, t1.address_1 as Address1, t1.job_position_id as jobPositionId, t1.profession as Profession, 
        			t1.other_profession as otherProfession, t1.specialty as Specialty, t1.experience_years as experienceYears, 
        			t1.cempro_club as cemproClub, t1.marital_status as maritalStatus, t1.child_num as childNum, t1.child_age as childAge, 
        			t1.religion as Religion, t1.phone_thrid as phoneThrid, t1.social as Social, t1.free_time as freeTime, t1.passwd as Passwd, 
        			t1.status as Status, t1.comments as Comments, t1.business_city as businessCity, t1.business_state as businessState, 
        			t1.business_zone as businessZone, t1.business_address as businessAddress, t1.group_name as groupName, t1.business_name as businessName,         			
        			t1.cupon as Cupon, 
        			t1.register_type as registerType, t1.created_by as createdBy, t1.created_at as createdAt, t1.updated_at as updatedAt, t1.updated_by as updatedBy        			
        			FROM register_pending as t1 
        			left join point_of_sale as p on p.point_of_sale_id = t1.point_of_sale_id
        			WHERE t1.register_pending_id > 0
        			";
        
        
        //$em = $this->getEntityManager();
		//$repo = $em->getRepository('AppBundle:RegisterPending');
        //$query = $repo->createQueryBuilder('rp');

        if ($status != NULL || $status != '') {
            // Default query for status
           // $query->andWhere("rp.status = $status");
           $query .= " and t1.status = '$status' ";
        }

        if ($phoneMain != NULL || $phoneMain != '') {
            //$query->andWhere('rp.phoneMain LIKE :phoneMain')
            //    ->setParameter('phoneMain', '%' . $phoneMain . '%');
            $query .= " and t1.phone_main like '%$phoneMain%' ";
        }

        if ($citizenId != NULL || $citizenId != '') {
            //$query->andWhere('rp.citizenId LIKE :citizenId')
            //    ->setParameter('citizenId', '%' . $citizenId . '%');
            $query .= " and t1.citizen_id like '%$citizenId%' ";
        }

        if ($name != NULL || $name != '') {
            //$query->andWhere('rp.name LIKE :name')
            //    ->setParameter('name', '%' . $name . '%');
            $query .= " and t1.name like '%$name%' ";
        }
        
        $query .= " order by  t1.created_at desc";
        
        $res = $this->getEntityManager ()->getConnection ()->prepare ( $query );	
		$res->execute ();
		return $res->fetchAll();        
		//$query->orderBy('rp.createdAt','DESC');
        //return $query->getQuery();
	}
	
	/**
	 * Update all register staff  pendings registers
	 *
	 * @param string $staff_id id of staff
	 * @return array() query result
	 */
	public function UpdateAllStaffIdRegisterPending($newStaff, $registerPending){
		$query = "
			UPDATE register_pending SET 
			staff_id = :staffId,
			name = :name,
			tax_identifier = :taxIdentifier,
			gender = :gender,
			phone_secondary = :phoneSecondary,
			phone_main = :phoneMain,
			email = :email,
			address_1 = :address1,
			profession = :profession,
			other_profession = :otherProfession,
			specialty = :specialty,
			birthdate = :birthdate,
			state_id = :state,
			city_id = :city,
			country_id = :country,
			experience_years = :experienceYears,
			cempro_club = :cemproClub,
			marital_status = :maritalStatus,
			child_num = :childNum,
			child_age = :childAge,
			religion = :religion,
			phone_thrid = :phoneThrid,
			social = :social,
			free_time = :freeTime,
			zone = :zone
			where citizen_id = :citizenId 						
						 
						
		";

		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->bindValue ( "staffId", $newStaff->getStaffId(), \PDO::PARAM_INT );
		$res->bindValue ( "name", $registerPending->getName(), \PDO::PARAM_STR );
		$res->bindValue ( "taxIdentifier", $registerPending->getTaxIdentifier(), \PDO::PARAM_STR );
		$res->bindValue ( "gender", $registerPending->getGender(), \PDO::PARAM_INT );
		$res->bindValue ( "phoneSecondary", $registerPending->getPhoneSecondary(), \PDO::PARAM_STR );
		$res->bindValue ( "phoneMain", $registerPending->getPhoneMain(), \PDO::PARAM_STR );
		$res->bindValue ( "email", $registerPending->getEmail(), \PDO::PARAM_STR );
		$res->bindValue ( "address1", $registerPending->getAddress1(), \PDO::PARAM_STR );
		$res->bindValue ( "profession", $registerPending->getProfession(), \PDO::PARAM_STR );
		$res->bindValue ( "otherProfession", $registerPending->getOtherProfession(), \PDO::PARAM_STR );
		$res->bindValue ( "specialty", $registerPending->getSpecialty(), \PDO::PARAM_STR );
		$res->bindValue ( "birthdate", date_format($registerPending->getBirthdate(), 'Y-m-d H:i:s') , \PDO::PARAM_STR );
		$res->bindValue ( "state", $registerPending->getState()->getStateId(), \PDO::PARAM_INT );
		$res->bindValue ( "city", $registerPending->getCity()->getId(), \PDO::PARAM_INT );
		$res->bindValue ( "country", $registerPending->getCountry()->getId(), \PDO::PARAM_INT );
		$res->bindValue ( "experienceYears", $registerPending->getExperienceYears(), \PDO::PARAM_INT );
		$res->bindValue ( "cemproClub", $registerPending->getCemproClub(), \PDO::PARAM_STR );
		$res->bindValue ( "maritalStatus", $registerPending->getMaritalStatus(), \PDO::PARAM_STR );
		$res->bindValue ( "childNum", $registerPending->getChildNum(), \PDO::PARAM_INT );
		$res->bindValue ( "childAge", $registerPending->getChildAge(), \PDO::PARAM_STR );
		$res->bindValue ( "religion", $registerPending->getReligion(), \PDO::PARAM_STR );
		$res->bindValue ( "phoneThrid", $registerPending->getPhoneThrid(), \PDO::PARAM_STR );
		$res->bindValue ( "social", $registerPending->getSocial(), \PDO::PARAM_STR );
		$res->bindValue ( "freeTime", $registerPending->getFreeTime(), \PDO::PARAM_STR );
		$res->bindValue ( "zone", $registerPending->getZone(), \PDO::PARAM_INT );
		$res->bindValue ( "citizenId", $registerPending->getCitizenId(), \PDO::PARAM_INT );
		
								
		$res->execute ();

		return $res;
	}



	public function UpdateUuidRegisterPending($uuid, $pointOfSaleId){
		$query = "
			UPDATE register_pending SET 
			point_of_sale_id = :pointOfSaleId
			where device_uuid = :deviceUuid 						
		";

		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->bindValue ( "pointOfSaleId", $pointOfSaleId, \PDO::PARAM_INT );
		$res->bindValue ( "deviceUuid", $uuid, \PDO::PARAM_INT );
		$res->execute ();

		return $res;
	}

	public function validateUniqueRegister($pointOfSaleId,$invoiceNumber){
		$invoiceNumber = substr( trim($invoiceNumber."/",0), 0,-1);
		$query = "SELECT * FROM register_pending WHERE  
						point_of_sale_id = $pointOfSaleId and status = 2 
						and trim(LEADING '0' FROM  invoice_number) =  '$invoiceNumber'";
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );		
		$res->execute ();
		return $res->fetchAll ();
	}	
	
	public function getWorks(){
			
		$query = "SELECT * FROM construction_category WHERE display = 1";

		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );		
		$res->execute ();
		return $res->fetchAll ();
		
	}
	
}
