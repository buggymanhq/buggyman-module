<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace BuggymanModule;


use Buggyman\Buggyman;
use Exception;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\ApplicationInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\InlineScript;
use Zend\View\HelperPluginManager;

class Module implements BootstrapListenerInterface, ConfigProviderInterface, ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var ApplicationInterface $app */
        $app = $e->getTarget();
        $serviceManager = $app->getServiceManager();
        /** @var Options $options */
        $options = $serviceManager->get('BuggymanOptions');
        if ($options->getEnabled() && $options->getToken()) {
            Buggyman::setToken($options->getToken());
            Buggyman::setErrorLevel($options->getErrorLevel());
            Buggyman::setRoot(getcwd());
            Buggyman::init();

            $app->getEventManager()->attach(
                [MvcEvent::EVENT_DISPATCH_ERROR, MvcEvent::EVENT_RENDER_ERROR],
                function (MvcEvent $event) use ($serviceManager) {
                    if ($event->getParam('exception') instanceof Exception) {
                        Buggyman::reportException($event->getParam('exception'));
                    }
                }
            );

            if ($options->getPublicToken() && (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS'])) {
                /** @var HelperPluginManager $pluginManager */
                $pluginManager = $serviceManager->get('ViewHelperManager');
                /** @var InlineScript $inline */
                $inline = $pluginManager->get('InlineScript');

                if ($options->getAsync()) {
                    $script = '(function(e,t,n,r){var i=e.createElement("script"),s=e.getElementsByTagName(n)[0];i.async=1;i.src=r;s.parentNode.insertBefore(i,s)})(document,window,"script","http://cdn.buggyman.io/v1/js/%s/collector.js")';
                    $script = sprintf($script, $options->getPublicToken());
                    $inline($inline::SCRIPT, $script);
                } else {
                    $url = 'http://cdn.buggyman.io/v1/js/' . $options->getPublicToken() . '/collector.js';
                    $inline($inline::FILE, $url);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return include __DIR__ . "/../../config/module.config.php";
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'BuggymanOptions' => function (ServiceManager $sm) {
                    $config = $sm->get('Config');
                    return new Options(isset($config['buggyman']) ? $config['buggyman'] : []);
                }
            )
        );
    }
}