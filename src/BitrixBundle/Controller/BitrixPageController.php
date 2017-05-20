<?php

namespace BitrixBundle\Controller;

use BitrixBundle\Proxy\Container;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BitrixPageController
 * @package BitrixBundle\Controller
 */
class BitrixPageController extends Controller
{
    /**
     * @Route("/{path}", name="bitrix", requirements={"path" = ".*"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param                                           $path
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function totalAction(Request $request, $path)
    {
        if ($file = $request->get('bitrix_file')) {
            Container::setInstance($this->container);
            ob_start();
            define('DEMO', false);
            include $file;
            $content = ob_get_clean();
            return new Response($content);
        }

        throw $this->createNotFoundException();
    }
}
