<?php
namespace AppBundle\Controller\Backend;
use AppBundle\Entity\Sale;
use AppBundle\Entity\SaleStaff;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\RegisterPending;
use AppBundle\Entity\Staff;
use AppBundle\Entity\StaffPointOfSale;
use AppBundle\Form\PreInscriptionType;
use AppBundle\Helper\SessionHelper;
use AppBundle\Repository\EbClosion;

class PreInscriptionController extends Controller
{

    private $moduleId = 27;

    /**
     * @Route("/backend/pre-inscripcion", name="backend_pre_inscription")
     */
    public function indexAction (Request $request)
    {
        $this->get("session")->set("module_id", $this->moduleId);
        $staff = new Staff();
        $userData = $this->get("session")->get("userData");

		$options = array("user_role_id"=>$this->getUser()->getUserRole()->getId());

        $form = $this->createForm(new PreInscriptionType(), $staff, $options);
        $form->handleRequest($request);
               
        
        $search = $request->get("search", false);
        $findStaff = false;
        if ($search) {
            $findStaff = $this->getDoctrine()->getRepository("AppBundle:Staff")->search(false, $search["dpi"], $search["phone"], $search["nit"])->execute();            
        }
        
        // Validar formulario
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                
                // find staff in db
                
                $newStaff = $this->getDoctrine()
                    ->getRepository('AppBundle:Staff')
                    ->findOneBy(
                        array(
                                "citizenId" => $staff->getCitizenId(),
                                "phoneMain" => $staff->getPhoneMain()
                        ));
                
                if (! $newStaff) {
                    
                    $saveError = false;
                    $profilePicture = $request->files->get("profileImage");
                    if ($profilePicture) {
                        if (exif_imagetype($profilePicture) != IMAGETYPE_JPEG &&
                                exif_imagetype($profilePicture) != IMAGETYPE_PNG) {
                                    
                                    $this->addFlash('error_message',
                                            $this->getParameter('extension_error_invoice'));
                                    $saveError = true;
                                } else {
                                    $fileName = md5(uniqid()) . '.' . $profilePicture->guessExtension();
                                    $profilePicture->move($this->getParameter('profile_image'),
                                            $fileName);
                                    $staff->setProfileImage($fileName);
                                }
                    }
                    if (!$saveError) {
                                        
                        // save
                        $em = $this->getDoctrine()->getManager();
                        
                        $staff->setCreatedAt(new \DateTime());
                        $staff->setCreatedBy(
                                $this->getUser()
                                    ->getId());
                        $userHelper = $this->get('user.helper');
                        $staff->setPasswd(
                                $userHelper->encodePassword($staff,
                                        $staff->getCitizenId()));
                        // Start as active staff                    
                        $em->persist($staff);
                        // save data                   
                        $em->flush();
                        
                        
                        $phone = "502" .
                                 $staff->getPhoneMain();
                        
                        $send_sms = new SessionHelper();
                        $token = $this->getParameter("api_token");
                        
                        $send_sms->send_sms_televida($phone,
                                urlencode(
                                        $this->getParameter(
                                                'registro_pendiente_confirmado')),
                                $token);
                        
                        $this->addFlash('success_message',
                                $this->getParameter('exito'));
                        // redirect to point of sale list
                        return $this->redirectToRoute("backend_staff_point_of_sale", array("staffId" => $staff->getStaffId()));
                    }
                    
                } else {
                    $this->addFlash('error_message',
                            $this->getParameter('usuario_existente_dpi_nit'));
                }
            } else {
                $this->addFlash('error_message',
                        $this->getParameter('error_form'));
            }
        }
        
        $mp = EbClosion::getModulePermission($this->moduleId,
                $this->get("session")->get("userModules"));
        
        return $this->render('@App/Backend/PreInscription/index.html.twig',
                array(
                        "form" => $form->createView(),
                        "permits" => $mp,
                        "findStaff" => $findStaff
                ));
    }

    
}
