<?php

namespace Fab\Classes;

use Fab\Classes\Exception\ActionNotFoundException;
use Fab\Classes\Exception\ControllerNotFoundException;

class Router
{

    /**
     * @var string controller short Name
     */
    private $controller;

    /**
     * @var string action (method) in controller
     */
    private $action;

    /**
     * @var array array of params to sending to action
     */
    private $params;

    /** Construct
     * 
     */
    public function __construct()
    {
        $this->controller = NAMESPACE_VENDOR.'\Controller\\' . ucfirst((Http::get('c')) ?? 'home') . 'Controller';

        $this->action = Http::get('a') ?? 'index';

        $this->generateParams();

        $this->validateRoute();
    }

    private function generateParams()
    {
        $this->params = [];
        $paramsFromUrl = Http::getAll(['a', 'c']);
        if (isset($paramsFromUrl['p'])) {
            $tmpParams = explode('/', $paramsFromUrl['p']);

            if (count($tmpParams) == 1)
                $this->params['id'] = $tmpParams[0];
            else {
                for ($j = 0; $j < count($tmpParams); $j = $j + 2) {
                    $value = $tmpParams[$j + 1];
                    if (is_numeric($value))
                        $value = (int)$value;
                    $this->params[$tmpParams[$j]] = $value;
                }
            }



            unset($paramsFromUrl['p']);
        }


        //$this->params = [...$this->params,...$paramsFromUrl];
    }

    /** Validate routing
     * 
     */
    private function validateRoute(string $controller = null, string $action = null)
    {
        $controller =  $controller ?? $this->controller;
        $action = $action ?? $this->action;
       
        if (!class_exists($this->controller))
            throw new ControllerNotFoundException("Le controller {$this->controller} n'a pas été trouvé !");

        if (!method_exists($this->controller, $this->action))
            throw new ActionNotFoundException("Le controller {$this->controller} n'a pas de méthode {$this->action} !");
    }

    /** Generate Path route to Controller  / Action / param 
     * @param string|null controller 
     * @param string|null action
     * @param array paramètre de la route
     */
    public function path(?string $controller = null, ?string $action = null, ?array $params = []): string
    {
        $url = URL;
        if (USE_REWRITE) {
            
            if ($controller !== null) {
                $url .= $controller;
            }
            else {
                $url .= 'home';
            }

            if ($action !== null) {
                $url .= '/' . $action;
            } else {
                $url .= '/index';
            }

            if ($params !== null) {
                foreach ($params as $index => $param) {
                    $url .= '/' . $param;
                }
            }
        } else {
            $url .= 'index.php?c=';
            if ($controller !== null) {
                $url .= $controller;
            }
            else {
                $url .= 'home';
            }

            if ($action !== null) {
                $url .= '&a=' . $action;
            } else {
                $url .= '&a=index';
            }

            if ($params !== null) {
                $i = 0;
                foreach ($params as $index => $param) {
                    $url .= '&params=' . $param;
                    if (++$i < count($params))
                        $url .= '/';
                }
            }
        }

        return $url;
    }


    /**
     * Get controller short Name
     *
     * @return  string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Set controller short Name
     *
     * @param  string  $controller  controller short Name
     *
     * @return  self
     */
    public function setController(string $controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Get action (method) in controller
     *
     * @return  string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set action (method) in controller
     *
     * @param  string  $action  action (method) in controller
     *
     * @return  self
     */
    public function setAction(string $action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get array of params to sending to action
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Set array of params to sending to action
     */
    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }
}
