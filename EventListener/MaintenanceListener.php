<?php

namespace Arkanii\MaintenanceBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MaintenanceListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(ContainerInterface $container, Environment $twig)
    {
        $this->container = $container;
        $this->twig = $twig;
    }

    /**
     * @param RequestEvent $event
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function onKernelRequest(RequestEvent $event)
    {
        $maintenance = true;

        $request = $event->getRequest();

        $linkRequest = $request->getRequestUri();
        $currentIp = $request->getClientIp();

        $enabled = $this->container->getParameter('maintenance.enabled');
        $authorized_ips = $this->container->getParameter('maintenance.authorized_ips');
        $debug_urls = $this->container->getParameter('maintenance.debug_urls');
        $authorize_admin_panel = $this->container->getParameter('maintenance.authorize_admin_panel');
        $admin_url = $this->container->getParameter('maintenance.admin_url');

        if ($enabled) {

            if (in_array($currentIp, $authorized_ips)) {
                $maintenance = false;
            }

            foreach ($debug_urls as $debug_url) {
                if ((bool)preg_match('/\/' . $debug_url . '\//', $linkRequest)) {
                    $maintenance = false;
                }
            }

            if ($authorize_admin_panel) {
                if ((bool)preg_match('/\/' . $admin_url . '\//', $linkRequest)) {
                    $maintenance = false;
                }
            }
        }

        if ($maintenance) {
            $view = $this->twig->render('@Maintenance/maintenance.html.twig');
            $event->setResponse(new Response($view, Response::HTTP_SERVICE_UNAVAILABLE));
            $event->stopPropagation();
        }

    }
}