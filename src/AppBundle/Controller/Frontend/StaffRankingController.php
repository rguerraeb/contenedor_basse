<?php
namespace AppBundle\Controller\Frontend;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Helper\SessionHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Repository\EbClosion;
use AppBundle\Entity\Staff;
use Doctrine\DBAL\Types\JsonArrayType;
use AppBundle\Entity\InvoicePending;

class StaffRankingController extends Controller
{

    private $moduleId = 32;

    /**
     *
     * @Route("/staff/ranking", name="staff_ranking")
     */
    public function registerSaleAction (Request $request)
    {
        
        $this->get("session")->set("module_id", $this->moduleId);
        $em = $this->getDoctrine()->getEntityManager();
        
        $posId = ($_POST)? $request->get("pos", 0): 0;
        $jobPostionId = $this->getUser()->getJobPosition()->getId();
        $showSelect = (in_array ($jobPostionId, array(1,2)))?true:false;        
        
        if ($showSelect) {
            // unicamente cuando venga por post            
            $ranking = $this->getDoctrine()->getRepository("AppBundle:Staff")->getRankingList($posId);
            $pos = false;
            $posList = $em->getRepository("AppBundle:PointOfSale")->getStaffsPos($this->getUser()->getStaffId());
            $isBarman = false;
        } else {
            // point of sale del barman
            $posList = false;
            $pos = $em->getRepository("AppBundle:StaffPointOfSale")->findOneBy(
                    array(
                            "staff" => $this->getUser()
                            ->getStaffId(),
                            "status" => "ACTIVE"
                    ), array(
                            "createdAt" => "DESC"
                    ));
                                   
            
            if ($pos && $pos->getPointOfSale()) {                
                $ranking = $this->getDoctrine()
                ->getRepository("AppBundle:Staff")->getRankingList($pos->getPointOfSale()->getPointOfSaleId());
                                
            } else {
                $ranking = array();
            }
            $isBarman = true;
        }
        
        
        
               
        
        // replace this example code with whatever you need
        return $this->render ( '@App/Frontend/Staff/ranking.html.twig', array (
                "list" => $ranking,
                "pos" => $pos,
                "posList" => $posList,
                "posId" => $posId,
                "showSelect" => $showSelect,
                "isBarman" => $isBarman
        ) );
    }


}
