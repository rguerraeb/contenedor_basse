<?php
namespace AppBundle\Controller\Frontend;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Helper\SessionHelper;
use AppBundle\Repository\EbClosion;


class StaffPosController extends Controller
{

    private $moduleId = 33;

    /**
     *
     * @Route("/staff/PointOfSale", name="staff_point_of_sales")
     */
    public function posAction (Request $request)
    {
        $this->get("session")->set("module_id", $this->moduleId);
        
        $em = $this->getDoctrine()->getManager();

        $userData = $this->get ( "session" )->get ( "userData" );

        $staff = $em->getRepository('AppBundle:Staff')->findBy(
            array(
                "staffId" => $userData['staff_id']
            )
        );
            
        $list = $em->getRepository('AppBundle:StaffPointOfSale')->findBy(
            array(
                "staff" => $staff,
                "status" => "ACTIVE"
            ));             

        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));

        return $this->render('@App/Frontend/Staff/points_of_sale.html.twig',
            array(
                "list" => $list,
                "permits" => $mp,
                "staff" => $staff
            ));
    }

}