<?php

namespace Nmn\MultiUserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\ResettingController as BaseController;
use Nmn\MultiUserBundle\Event\ManualLoginEvent;

class ResettingController extends BaseController
{
    /**
     * Reset user password
     */
    public function resetAction($token)
    {
        $return = parent::resetAction($token);
        
        if ($return instanceof RedirectResponse) {
            $user = $this->container->get('security.context')->getToken()->getUser();            
            if ( $user ) {
                $dispatcher = $this->container->get('event_dispatcher');
                $event = new ManualLoginEvent($user);
                $dispatcher->dispatch('security.manual_login', $event);
            }            
        }
        
        return $return;
    }
}