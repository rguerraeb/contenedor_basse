<?php

namespace AppBundle\Handler;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserRoleHandler {
    protected $router;
    protected $securityContext;
    protected $em;
    protected $session;
    protected $errorMessage;

    public function __construct(
        RouterInterface $router,
        SecurityContextInterface $securityContext,
        EntityManager $em,
        SessionInterface $session,
        $errorMessage) {
        $this->router = $router;
        $this->securityContext = $securityContext;
        $this->em = $em;
        $this->session = $session;
        $this->errorMessage = $errorMessage;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        if (HttpKernelInterface::MASTER_REQUEST != $event->getRequestType()) {
            return;
        }

        // Get role id
        $roleId = $this->getRoleId();

        if ($roleId === NULL) {
            return;
        }

        // Check if it is in a open url
        $allRoleModules = $this->em
            ->getRepository('AppBundle:UserRoleModulePermission')
                ->findAll();

        // Hold the role modules that the logged user has
        $roleModules = array();

        // Get name of request
        $request = $event->getRequest();
        $requestName = $request->get('_route');

        // Variable tells if request module is on database
        $isStored = false;

        foreach ($allRoleModules as $roleModule) {
            $roleM = $roleModule->getUserRole();

            // Check name to see if the request module is on database
            $module = $roleModule->getModule();
            if ($module) {
                // Compare name
                $moduleName = $module->getUrlAccess();

                if (strpos($requestName, $moduleName) !== false) {
                    // Taking about same module
                    $isStored = true;
                }
            }

            // Check if role is same as logged user role
            if ($roleM && $roleM->getId() === $roleId) {
                // Add this role to check later
                array_push($roleModules, $roleModule);
            }
        }

        // If it's not stored anyone is allowed
        if (!$isStored) {
            return;
        }

        // Variable to see if module is in allowed modules list
        $isAllowed = false;

        /**
         * RULES
         *
         * What is going to be checked from user_role_module_permission information
         * edit_permission is module_catalog.name with "edit"
         * delete_permission is module_catalog.name with "delete"
         * read_permission is module_catalog.name or with "index", "dashboard", "view", "main", "show"
         * write_permission is module_cataloge.name with "upload"
         * the rest of permissions are included in the ones just mentioned
         */

        foreach ($roleModules as $roleModule) {
            $module = $roleModule->getModule();
            $moduleName = $module->getUrlAccess();

            // Strip the module name from request module name
            $strippedName = str_replace($moduleName, '_', $requestName);

            // Check if $module is same as request module
            if (strpos($requestName, $moduleName) !== false) {
                // Check for rules found in database
                $isAllowed = true;

                // Start by edit
                if (!$roleModule->getEditPermission()) {
                    // Doesn't have edit permission

                    // Look for "edit" in stripped name
                    if (strpos($strippedName, "edit") !== false) {
                        // Is in edit request, deny access
                        return $this->accessDeniedAction($event);
                    }
                }

                // Delete
                if (!$roleModule->getDeletePermission()) {
                    // Look for "delete" in stripped name
                    if (strpos($strippedName, "delete") !== false) {
                        // Is in delete request, deny access
                        return $this->accessDeniedAction($event);
                    }
                }

                // Read
                if (!$roleModule->getReadPermission()) {
                    if (
                        // Look just by name
                        $moduleName == $requestName
                        // Look for index
                        || strpos($strippedName, "index") !== false
                        // Look for view
                        || strpos($strippedName, "view") !== false
                        // Look for dashboard
                        || strpos($strippedName, "dashboard") !== false
                        // Look for main
                        || strpos($strippedName, "main") !== false
                        // Look for show
                        || strpos($strippedName, "show") !== false
                    ) {
                        // Is in read request, deny access
                        return $this->accessDeniedAction($event);
                    }
                }

                // Write
                if (!$roleModule->getWritePermission()) {
                    // Look for "upload" in stripped name
                    if (strpos($strippedName, "upload") !== false) {
                        // Is in upload request, deny access
                        return $this->accessDeniedAction($event);
                    }
                }
            }
        }

        if (!$isAllowed) {
            return $this->accessDeniedAction($event);
        }
    }

    /**
     * Action to take when found out user doesn't have permission to access request
     *
     * @param GetResponseEvent $event event handled by service (the request)
     * @return RedirectResponse response to redirect to, with flash message showing error
     */
    private function accessDeniedAction(GetResponseEvent $event) {
        // Function to call when user is in not allowed section

        // Add error message
        $this->session->getFlashBag()->add('login_error', $this->errorMessage);

        // Get role id
        $roleId = $this->getRoleId();

        // Check if backend login, of staff login
        if ($roleId == 2) {
            $url = 'staff_login';
        }
        else {
            $url = 'backend_login';
        }

        // Redirect to url of login
        return $event->setResponse(new RedirectResponse($this->router->generate($url)));
    }

    /**
     * Getss role id from user logged inf*
     *
     * @return int role id of user. NULL if no use found
     */
    public function getRoleId() {
        // Get token that has the user
        $token = $this->securityContext->getToken();

        if (!$token) {
            // Not logged in user
            return NULL;
        }

        // Get user
        $user = $token->getUser();
        if (!$user || !$this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            // User not found in token
            return NULL;
        }

        // Get role
        $role = $user->getUserRole();
        if (is_array($role)) {
            // Staff role
            $roleId = 2;
        }
        else {
            $roleId = $role->getId();
        }

        return $roleId;
    }
}