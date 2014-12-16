<?php

namespace HB\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext as SC;

/**
 * @author hb
 */
class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        // on récupère la session
        $session = $request->getSession();

        // on cherche une erreur
        if ($request->attributes->has(SC::AUTHENTICATION_ERROR) ) {
            $error = $request->attributes->get(SC::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SC::AUTHENTICATION_ERROR);
            $session->remove(SC::AUTHENTICATION_ERROR);
        }

        return array( 
            'dernier_pseudo' => $session->get(SC::LAST_USERNAME),
            'erreur' => $error
        );
    }   
}