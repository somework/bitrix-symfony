<?php

namespace BitrixBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\User;

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
        $file = $request->get('bitrix_file');
        $_SERVER['DOCUMENT_ROOT'] = $this->getParameter('bitrix.root');
        if (!is_file($file) || !file_exists($file)) {
            $urlRewrite = $this->getParameter('bitrix.urlrewrite');
            global $DBType, $DBDebug, $DBDebugToFile, $DBHost, $DBName, $DBLogin, $DBPassword;
            /** @noinspection PhpIncludeInspection */
            if ($urlRewrite && ($urlRewriteFile = include $urlRewrite) && is_string($urlRewriteFile)) {
                $file = $this->get('bitrix.urlrewrite')->getRealFile($urlRewriteFile);
            }
        }

        if (is_file($file) && file_exists($file)) {
            ob_start();
            define('DEMO', false);
            include $file;
            $content = ob_get_clean();
            return new Response($content);
        }

        throw $this->createNotFoundException();
    }
}
