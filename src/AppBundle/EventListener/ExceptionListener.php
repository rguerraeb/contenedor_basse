<?php

// src/EventListener/ExceptionListener.php
namespace AppBundle\EventListener;

use AppBundle\Entity\ErrorLog;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Kernel;


class ExceptionListener
{
	
	private $kernel;
	private $isProd;
	
	public function __construct($kernel, $container)
    {        
        $this->container = $container;
		$this->kernel = $kernel;
        $this->em = $this->container->get('doctrine')->getEntityManager();
       

    }
	
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
       
         if (!$this->em->isOpen()) {
		    $this->em = $this->em->create(
		        $this->em->getConnection(),
		        $this->em->getConfiguration()
		    );
		}
		
		
        $exception = $event->getException();
	    $this->isProd = ($this->kernel->getEnvironment() == "dev") ? false : true;
	   
		if($this->isProd)
		{
	       $message = sprintf(
		        '%s: %s (uncaught exception) at %s line %s',
		        get_class($exception),
		        $exception->getMessage(),
		        $exception->getFile(),
		        $exception->getLine()
		    );
			
			$em = $this->em;
			$errorLog = new ErrorLog();
			$errorLog->setErrorDescription($message);
			$errorLog->setCreatedAt(new \DateTime());

			$em->persist($errorLog);
			$em->flush();
	
	        // Customize your response object to display the exception details
	        $response = new Response();
					
			$textHtml = "<div style='width:400px;margin:0 auto;padding:20px;text-align:center;font-family:Arial;box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);'><img src='http://dev.ebfuture.net/dcontreras/smsplatform/version3/web/images/warning_icon.png' style='height:100px;'><br><h1>¡Lo sentimos!</h1><br><h2>Ocurrio un error</h2><p>Nuestros ingenieros han sido notificados automaticamente y habilitarán el servicio en breve.<br>Por favor intente de nuevo más tarde.</p><p style='background-color:#3C8DBC;color:#fff;padding:10px;'><b>ID de reporte de error: #".$errorLog->getErrorLogId()."</b></p><small>Por favor, anote el número de arriba en caso nuestro departamento de soporte técnico se lo solicite.<br>Este es su número de seguimiento.<br></small></div>";
			
	        $response->setContent($textHtml);
	
	        // HttpExceptionInterface is a special type of exception that
	        // holds status code and header details
	        if ($exception instanceof HttpExceptionInterface) {
	            $response->setStatusCode($exception->getStatusCode());
	            $response->headers->replace($exception->getHeaders());
	        } else {
	            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
	        }
		        
	        $event->setResponse($response);
        }
    }
}

?>