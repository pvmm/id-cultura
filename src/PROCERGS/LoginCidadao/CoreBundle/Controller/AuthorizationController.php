<?php

namespace PROCERGS\LoginCidadao\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use PROCERGS\LoginCidadao\CoreBundle\Entity\ClientSuggestion;

class AuthorizationController extends Controller
{

    /**
     * @Route("/authorizations", name="lc_apps")
     * @Template()
     */
    public function userAuthorizationsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $clients = $em->getRepository('PROCERGSOAuthBundle:Client');

        $user = $this->getUser();
        $allApps = $clients->findAll();

        $apps = array();
        // Filtering off authorized apps
        foreach ($allApps as $app) {
            if ($user->hasAuthorization($app)) {
                continue;
            }
            $apps[] = $app;
        }
        $sugg = new ClientSuggestion();        
        $formBuilder = $this->createFormBuilder($sugg);
        $formBuilder->add('text', 'textarea');
        $form = $formBuilder->getForm();
        
        $em = $this->getDoctrine()->getEntityManager();        
        $suggs = $em->getRepository('PROCERGSLoginCidadaoCoreBundle:ClientSuggestion')->findBy(array(), array('createdAt' => 'desc'), 6);        
        $form = $form->createView();
        return compact('user', 'apps', 'form', 'suggs');
    }
    
    /**
     * @Route("/authorizations/suggestion", name="lc_apps_sugg")
     * @Template()
     */
    public function suggestionAction(Request $request)
    {
        $sugg = new ClientSuggestion();
        $formBuilder = $this->createFormBuilder($sugg);
        $formBuilder->add('text', 'textarea');
        $form = $formBuilder->getForm();
                
        $em = $this->getDoctrine()->getEntityManager();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $sugg->setPerson($this->getUser());
                $em->persist($sugg);
                $em->flush();
                $bag = $request->getSession()->getFlashBag();
                $translator = $this->get('translator');
                $bag->add('success', $translator->trans('client.suggestion.registered'));
        
            }
        }
        return $this->redirect($this->generateUrl('lc_apps'));
    }

}
