<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\SiteContent;
use AppBundle\Form\SiteContentType;
use AppBundle\Repository\EbClosion;

/**
 * SiteContent controller.
 *
 */
class SiteContentController extends Controller
{
    private $moduleId = 17;
    
    
    /**
	 * @Route("/backend/site-content", name="site-content_index")
	 */
    public function indexAction(Request $request)
    {
    	$this->get ( "session" )->set ( "module_id", 17 );
    	$siteContent = new SiteContent();
    	$form = $this->createForm ( new SiteContentType(), $siteContent );
    	$form->handleRequest ( $request );
    	$userData = $this->get ( "session" )->get ( "userData" );
    	
    	// Validar formulario
    	if ($form->isSubmitted ()) {
    		if ($form->isValid ()) {
    			// save
    			$this->addFlash ( 'success_message', $this->getParameter ( 'exito' ) );
    			return $this->redirectToRoute ( "backend_site_content_news" );
    		} else {
    			$this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
    		}
    	}
    	
    	$query = $this->getDoctrine ()->getRepository ( 'AppBundle:SiteContent' )->findAll ();
    	$paginator = $this->get ( 'knp_paginator' );
    	
    	$pagination = $paginator->paginate ( $query, $request->query->getInt ( 'page', 1 ), $this->getParameter ( "number_of_rows" ) );
    	
    	return $this->render ( '@App/Backend/SiteContent/index_news.html.twig', array (
    			"form" => $form->createView (),
    			"list" => $pagination,
    			"action" => "backend_user_roles"
    	) );
    	

    }
    
    /**
     * @Route("/backend/news", name="backend_site_content_news")
     */
    public function newsAction(Request $request)
    {
	    $em = $this->getDoctrine ()->getManager ();

    	$this->get ( "session" )->set ( "module_id", $this->moduleId );
    	$siteContent = new SiteContent();
    	$form = $this->createForm ( new SiteContentType(), $siteContent );
    	$form->handleRequest ( $request );
    	$userData = $this->get ( "session" )->get ( "userData" );
    	
    	
    	$user = $em->getRepository("AppBundle:User")->findOneBy(array("id" => $this->getUser()->getId()));
    	// Validar formulario
    	if ($form->isSubmitted ()) {
    		if ($form->isValid()) {
    			$file = $siteContent->getImageFile();
				
    			// Generate a unique name for the file before saving it
    			$fileName = md5(uniqid()).'.'.$file->guessExtension();
    			// Move the file to the directory where brochures are stored
    			$file->move(
    				$this->getParameter('news_image_path'),
    				$fileName
    			);

    			$siteContent->setImage($fileName);
    			$siteContent->setOrderContent("1");
    			$country = $this->getDoctrine ()->getRepository ( 'AppBundle:Country' )->findOneBy(array("id" => 1));
    			$siteContent->setCountry($country);
    			$siteContent->setCreatedAt(new \DateTime());
    			$siteContent->setCreatedBy($user);
    			$siteContent->setIsNews(1);
    			$siteContent->setKeywords("news");
    			
    			// save
                $em = $this->getDoctrine()->getManager();
                $em->persist($siteContent);
                $em->flush();
                
    			$this->addFlash ( 'success_message', $this->getParameter ( 'exito' ) );
    			return $this->redirectToRoute ( "backend_site_content_news" );
    		} else {
	    		
	    		echo $form->getErrorsAsString(); exit;
    			$this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
    		}
    	}
    	 
    	$query = $this->getDoctrine ()->getRepository ( 'AppBundle:SiteContent' )->findAll();
    	
    	$paginator = $this->get ( 'knp_paginator' );
    	 
    	$pagination = $paginator->paginate ( $query, $request->query->getInt ( 'page', 1 ), $this->getParameter ( "number_of_rows" ) );
    	 
    	
    	$mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));
    	return $this->render ( '@App/Backend/SiteContent/index_news.html.twig', array (
    			"form" => $form->createView (),
    			"list" => $pagination,
    			"action" => "backend_user_roles",
    	        "permits" => $mp
    	) );
    	 
    
    }

    /**
	 * @Route("/backend/site-content", name="site-content_new")
	 */
    public function newAction(Request $request)
    {
        $siteContent = new SiteContent();
        $form = $this->createForm('AppBundle\Form\SiteContentType', $siteContent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($siteContent);
            $em->flush();

            return $this->redirectToRoute('site-content_show', array('id' => $siteContent->getId()));
        }

        return $this->render('sitecontent/new.html.twig', array(
            'siteContent' => $siteContent,
            'form' => $form->createView(),
        ));
    }

    /**
	 * @Route("/backend/site-content", name="site-content_show")
	 */
    public function showAction(SiteContent $siteContent)
    {
        $deleteForm = $this->createDeleteForm($siteContent);

        return $this->render('sitecontent/show.html.twig', array(
            'siteContent' => $siteContent,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
	 * @Route("/backend/news/edit/{id}",
     *  name="backend_site_content_news_edit",
     *  requirements={"id": "\d+"}
     * )
	 */
    public function editAction(Request $request, SiteContent $siteContent)
    {
        $editForm = $this->createForm('AppBundle\Form\SiteContentType', $siteContent);
        $oldImage = $siteContent->getImage();
        $editForm->handleRequest($request);
        
		$em = $this->getDoctrine ()->getManager ();
		$userData = $this->get ( "session" )->get ( "userData" );
		$user = $em->getRepository("AppBundle:User")->findOneBy(array("id" => $this->getUser()->getId()));

        if ($editForm->isSubmitted()){
            if ($editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $file = $siteContent->getImageFile();

                if ($file) {
                    // Generate a unique name for the file before saving it
                    $fileName = md5(uniqid()).'.'.$file->guessExtension();
                    // Move the file to the directory where brochures are stored
                    $file->move(
                        $this->getParameter('news_image_path'),
                        $fileName
                    );
                    $siteContent->setImage($fileName);
                }
                else {
                    $siteContent->setImage($oldImage);
                }
                
                $siteContent->setOrderContent("1");
                $country = $this->getDoctrine ()->getRepository ( 'AppBundle:Country' )->findOneBy(array("id" => 1));
                $siteContent->setCountry($country);
                $siteContent->setIsNews(1);
                $siteContent->setUpdatedBy($user);
                $siteContent->setKeywords("news");
                
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($siteContent);
                $em->flush();

                $this->addFlash ( 'success_message', $this->getParameter ( 'exito' ) );
                return $this->redirectToRoute('backend_site_content_news');
            }
            else {
                $this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
            }
        }

        return $this->render('@App/Backend/SiteContent/edit.html.twig', array(
            'form' => $editForm->createView()
        ));
    }

    /**
     * @Route("/backend/site-content/delete/{id}", name="backend_site_content_news_delete", requirements={"id": "\d+"})
     */
    public function deleteAction(Request $request, SiteContent $siteContent) {
        if ($siteContent) {
            // Try to delete
            try {
                $em = $this->getDoctrine ()->getManager ();

                $em->remove( $siteContent );
                $em->flush();

                $this->addFlash ( 'success_message', $this->getParameter ( 'exito_eliminar' ) );
            }
            catch (\Exception $e) {
                $this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
            }
        } else {
            $this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
        }
        
        return $this->redirectToRoute ( "backend_site_content_news" );
    }

    /**
     * Creates a form to delete a SiteContent entity.
     *
     * @param SiteContent $siteContent The SiteContent entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SiteContent $siteContent)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backend_site_content_delete', array('id' => $siteContent->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * @Route("/backend/site-content/upload-files", name="site-content_upload_files")
     */
    public function uploadFileAction(Request $request)
    {
    	$eb = new EbClosion();    	
    	$files = $_FILES;    	
    	$path = $this->getParameter('upload_files');
    	$rsImages = $eb->uploadImages($files, false, $path);
    	// TODO:: Realizar el guardado de la imagen, $rsImages se un arreglo en donde viene el nombre de la imagen.
    	
    	
    	echo json_encode ( array (
    			'status' => "Success",
    			'file_name' => $rsImages[0]
    	) );
    	
    	die;
    
    	
    }
}
