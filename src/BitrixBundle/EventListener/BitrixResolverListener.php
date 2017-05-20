<?php


namespace BitrixBundle\EventListener;

use BitrixBundle\Path\Resolver\BitrixResolver;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;


class BitrixResolverListener implements EventSubscriberInterface
{

    /**
     * @var string
     */
    private $bitrixRoot;

    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    private $kernel;
    /**
     * @var \BitrixBundle\Path\Resolver\BitrixResolver
     */
    private $bitrixResolver;

    /**
     * @param \BitrixBundle\Path\Resolver\BitrixResolver    $bitrixResolver
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     * @param string                                        $bitrixRoot
     */
    public function __construct(BitrixResolver $bitrixResolver, KernelInterface $kernel, string $bitrixRoot)
    {
        $this->bitrixRoot = $bitrixRoot;
        $this->kernel = $kernel;
        $this->bitrixResolver = $bitrixResolver;
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
            KernelEvents::RESPONSE   => ['onKernelResponse', -129],
        ];
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @throws \InvalidArgumentException
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $_SERVER['DOCUMENT_ROOT'] = $this->bitrixRoot;
        /**
         * @var \BitrixBundle\Controller\BitrixPageController
         */
        $request = $event->getRequest();
        try {
            $request
                ->attributes
                ->set(
                    'bitrix_file',
                    $this->bitrixResolver->locate($request->getPathInfo())
                );
        } catch (\Exception $exception) {
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $_SERVER['DOCUMENT_ROOT'] = $this->kernel->getRootDir();
    }
}