<?php

namespace AppBundle\EventListener;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

class AuthenticationStaffEventListener implements AuthenticationSuccessHandlerInterface
{    
    protected $router;
    protected $security;
    protected $container;
    protected $em;

    public function __construct(Router $router, SecurityContext $security, $container)
    {
        $this->router = $router;
        $this->security = $security;
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getEntityManager();
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {     	
    	
        // Get data for session info
        $user = $token->getUser();
        $session = $request->getSession();

        if ($user) {
            $userData = $this->em->getRepository ('AppBundle:User')
                ->loginStaff($user);
                            
            $modules = $this->em->getRepository ('AppBundle:User')
                ->getModuleCatalog($userData["user_role_id"]);

            // Create session variables
            $session->set("userModules", $modules);
            $session->set("userData", $userData);

            $response = new RedirectResponse('main');
            return $response;
        }
    }

}

?>