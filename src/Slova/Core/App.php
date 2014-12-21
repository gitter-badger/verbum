<?php

namespace Slova\Core;

/**
 * Class App
 *
 * Main application class
 *
 * @package Slova\Core
 */
class App
{
    /**
     * Stores application config
     *
     * @var array
     */
    public $config = array();

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * Prepares application to be executed
     *
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->config = $config;
        $this->setIncludePath();
        $this->registerAutoloader();
        $this->defineGlobalConstants();
    }

    /**
     * Adds src directory to the include path
     */
    protected function setIncludePath()
    {
        set_include_path(
            implode(
                PATH_SEPARATOR,
                array_merge(
                    [$this->config['dir']['base'] . '/src', $this->config['dir']['base'] . '/phpunit'],
                    explode(PATH_SEPARATOR, get_include_path())
                )
            )
        );
    }

    /**
     * Registers application autoloader
     *
     * @throws Exception
     */
    protected function registerAutoloader()
    {
        if (!spl_autoload_register(array(new Autoloader(), 'doIt'))) {
            throw new Exception("Failed to register autoloader.");
        }
    }

    protected function defineGlobalConstants()
    {
        define('DS', DIRECTORY_SEPARATOR);
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher($dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return Dispatcher
     */
    public function getDispatcher()
    {
        if (!$this->dispatcher) {
            $this->dispatcher = new Dispatcher($this);
        }

        return $this->dispatcher;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        if (!$this->request) {
            $this->request = new Request();
        }
        return $this->request;
    }

    /**
     * @param Response $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        if (!$this->response) {
            $this->response = new Response();
        }
        return $this->response;
    }

    /**
     * Serves the agent request
     */
    public function serve()
    {
        $this->getDispatcher()->dispatch();
        $this->getResponse()->send();
    }
}
