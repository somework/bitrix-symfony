<?php


namespace BitrixBundle\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class UrlRewrite
 * @package BitrixBundle\Subscribers
 */
class UrlRewrite implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $root;

    /**
     * @var array
     */
    private $indexFiles;

    /**
     * UrlRewrite constructor.
     *
     * @param string $root
     * @param array  $indexFiles
     */
    public function __construct(string $root, array $indexFiles)
    {
        $this->root = $root;
        $this->indexFiles = $indexFiles;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', -129],
        ];
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        /**
         * @var \BitrixBundle\Controller\BitrixPageController
         */
        $request = $event->getRequest();
        $event->getRequest()->attributes->set('bitrix_file', $this->getRealFile($this->root . $request->getPathInfo()));
    }

    /** @noinspection MultipleReturnStatementsInspection */
    /**
     * @param string $realPath
     *
     * @return string
     */
    public function getRealFile(string $realPath): string
    {
        if (is_dir($realPath)) {
            foreach ($this->indexFiles as $indexFile) {
                $filePath = rtrim($realPath, '/') . '/' . $indexFile;
                if (is_file($filePath) && file_exists($filePath)) {
                    return $filePath;
                }
            }
        }
        if (is_file($realPath) && file_exists($realPath)) {
            return $realPath;
        }

        return '';
    }
}