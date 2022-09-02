<?php
declare(strict_types=1);

namespace Aqua\Base;

use Aqua\Base\Exception\BaseBadMethodCallException;
use Aqua\Base\Exception\BaseInvalidArgumentException;
use Aqua\Base\Exception\BaseLogicException;
use Aqua\Base\BadeView;
use http\Env\Response;

class BaseController
{
    /** @var array */
    protected array $routeParams;

    /** @var object */
    protected object $twig;

    /**
     * Main class constructor
     *
     * @param array $routeParams
     * @return void
     */
    public function __construct(array $routeParams)
    {
        $this->routeParams = $routeParams;
        $this->twig = new BadeView();
    }

    /**
     * Renders a view template from sub controller classes
     *
     * @param string $template
     * @param array $context
     * @return Response
     */
    public function render(string $template, array $context = []): Response
    {
        if ($this->twig === null) {
            throw new BaseLogicException("You cannot use the render method if the twig bundle is not available.");
        }
        return $this->twig->twigRender($template, $context);
    }

    /**
     * Magic method called when a non-existent or inaccessible method is
     * called on an object of this class. Used to execute before and after
     * filter methods on action methods. Action methods need to be named
     * with an "Action" suffix, e.g. indexAction, showAction etc.
     *
     * @param $name
     * @param $arguments
     * @return void
     * @throws BaseBadMethodCallException
     */
    public function __call($name, $arguments): void
    {
        $method = $name . "Action";
        if (!method_exists($this, $method)) {
            throw new BaseBadMethodCallException("Method $method does not exist in" . get_class($this));
        }
        if ($this->befor() !== false) {
            call_user_func_array([$this, $method], $arguments);
            $this->after();
        }
    }

    /**
     * Before method which is called before a controller method.
     *
     * @return void
     */
    protected function befor()
    {
    }

    /**
     * After method which is called after a controller method.
     *
     * @return void
     */
    protected function after()
    {
    }

    /**
     * Method for allowing child controller class to dependency inject other objects
     *
     * @param array $args
     * @return object
     * @throws BaseInvalidArgumentException
     * @throws \ReflectionException
     */
    public function create(array $args)
    {
        if (!is_array($args)) {
            throw new BaseInvalidArgumentException("Invalid argument");
        }
        $args = func_get_args();
        if ($args) {
            $output = '';
            foreach ($args as $arg) {
                foreach ($arg as $key => $class) {
                    if (strpos($class, $arg[$key]) !== false) {
                        if ($class) {
                            $output = ($key === 'dataColumns' || $key === 'column') ? $this->$key = $class : $this->$key = $this->getReflection($class);
                        }
                    }
                }
            }
            return $output;
        }
    }

    /**
     * simple Dependency injection
     *
     * @param $className
     * @return object
     * @throws \ReflectionException
     */
    public function getReflection($className)
    {
        try {
            $reflector = new \ReflectionClass($className);
            $constructor = $reflector->getConstructor();
            if (!$constructor) return $reflector->newInstance();

            $dependencies = [];
            $params = $constructor->getParameters();
            foreach ($params as $param) {
                $paramType = $param->getClass();
                if ($paramType) {
                    $dependencies[] = $this->getReflection($paramType->name);
                }
            }
            return $reflector->newInstanceArgs($dependencies);
        } catch (\ReflectionException $exception) {
            throw $exception;
        }
    }
}