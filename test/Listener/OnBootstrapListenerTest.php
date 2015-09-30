<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\ModuleManager\Listener;

use Zend\EventManager\EventManager;
use Zend\EventManager\SharedEventManager;
use Zend\ModuleManager\Listener\ModuleResolverListener;
use Zend\ModuleManager\Listener\OnBootstrapListener;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleEvent;
use Zend\Mvc\Application;
use ZendTest\ModuleManager\TestAsset\MockApplication;

/**
 * @covers Zend\ModuleManager\Listener\AbstractListener
 * @covers Zend\ModuleManager\Listener\OnBootstrapListener
 */
class OnBootstrapListenerTest extends AbstractListenerTestCase
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * @var ModuleManager
     */
    protected $moduleManager;

    public function setUp()
    {
        $sharedEvents = new SharedEventManager();
        $events       = new EventManager($sharedEvents);
        $this->moduleManager = new ModuleManager([]);
        $this->moduleManager->setEventManager($events);

        $events->attach(ModuleEvent::EVENT_LOAD_MODULE_RESOLVE, new ModuleResolverListener, 1000);
        $events->attach(ModuleEvent::EVENT_LOAD_MODULE, new OnBootstrapListener, 1000);

        $this->application = new MockApplication;
        $appEvents         = new EventManager(
            $sharedEvents,
            ['Zend\Mvc\Application', 'ZendTest\Module\TestAsset\MockApplication', 'application']
        );
        $this->application->setEventManager($appEvents);
    }

    public function testOnBootstrapMethodCalledByOnBootstrapListener()
    {
        $moduleManager = $this->moduleManager;
        $moduleManager->setModules(['ListenerTestModule']);
        $moduleManager->loadModules();
        $this->application->bootstrap();
        $modules = $moduleManager->getLoadedModules();
        $this->assertTrue($modules['ListenerTestModule']->onBootstrapCalled);
    }
}
