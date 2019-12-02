<?php

namespace AppBundle\EventListener;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

class AuthenticationEventListener implements AuthenticationSuccessHandlerInterface
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
                ->login($user);

            $modules = $this->em->getRepository ('AppBundle:User')
                ->getModuleCatalog($user->getUserRole()->getId());

            foreach ($modules as $key => $module) {
                if ($module['count_children'] > 0) {
                    // Get children
                    $children = $this->em->getRepository ('AppBundle:User')
                        ->getModuleChildren($module['mcid'], $user->getUserRole()->getId());

                    $modules[$key]['children'] = $children;
                }
            }
            
                // main module
            $mm = $this->em->getRepository ( 'AppBundle:UserRoleModulePermission' )
                ->findOneBy(array("userRole"=>$user->getUserRole()->getId(), "mainModule" => 1));

            // Create session variables
            $session->set("userModules", $modules);
           
            $response = new RedirectResponse($this->router->generate($mm->getModule()->getUrlAccess()));
            return $response;
        }
    }

}

?>