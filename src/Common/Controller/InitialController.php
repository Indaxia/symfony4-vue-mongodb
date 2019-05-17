<?php
    
namespace App\Common\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class InitialController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('@Common/index.html.twig');
    }
    
    /**
     * Frontend HTML5 history mode support
     * @see https://developer.mozilla.org/en-US/docs/Web/API/History_API#Adding_and_modifying_history_entries
     *
     * @Route("/{frontendPath}", requirements={"frontendPath"="^(?!api).+$"})
     */
    public function frontendHistoryModeSupport()
    {
        return $this->index();
    }
    
    /**
     * Provides initial data for the frontend
     * @Route("/api/initial")
     */
    public function initial(Request $request)
    {
        $locale = empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) 
            ? $request->getLocale() 
            : strtolower(str_split($_SERVER['HTTP_ACCEPT_LANGUAGE'], 2)[0]);
            
        return new JsonResponse([
            'clientLocale' => $locale
        ]);
    }
}