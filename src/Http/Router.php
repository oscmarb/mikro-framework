<?php

declare(strict_types=1);

namespace Mikro\Http;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Mikro\Container\MikroContainer;
use Psr\Container\ContainerInterface;
use function FastRoute\simpleDispatcher;

class Router
{
    private static self $instance;
    private Dispatcher $dispatcher;

    public static function instance(): self
    {
        return self::$instance;
    }

    public static function load(): self
    {
        return self::$instance ??= new self(MikroContainer::instance()->container());
    }

    public function __construct(private ContainerInterface $container)
    {
        $this->loadRoutes();
    }

    public function loadRoutes(): void
    {
        /** @var AbstractControllersFactory $controllersFactory */
        $controllersFactory = $this->container->get(AbstractControllersFactory::class);
        $controllers = $controllersFactory->controllers();

        $this->dispatcher = simpleDispatcher(
            function (RouteCollector $routeCollector) use ($controllers) {
                /** @var AbstractController $controller */
                foreach ($controllers as $controller) {
                    $route = $this->getControllerRoute($controller);
                    $methods = empty($route->methods()) ? Request::allMethods() : $route->methods();
                    $routeCollector->addRoute($methods, $route->path(), $controller::class);
                }
            }
        );
    }

    public function sendResponse(): void
    {
        $httpResponse = $this->httpResponse();
        $httpResponse->send();
    }

    protected function httpResponse(): Response
    {
        try {
            $httpMethod = $_SERVER['REQUEST_METHOD'];
            $uri = $_SERVER['REQUEST_URI'];

            if (false !== $pos = strpos($uri, '?')) {
                $uri = substr($uri, 0, $pos);
            }
            $uri = rawurldecode($uri);
            $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);
            $routeDispatchResult = $routeInfo[0];

            if (Dispatcher::NOT_FOUND === $routeDispatchResult) {
                return Response::notFound();
            }

            if (Dispatcher::METHOD_NOT_ALLOWED === $routeDispatchResult) {
                return Response::methodNotAllowed();
            }

            if (Dispatcher::FOUND !== $routeDispatchResult) {
                return Response::internalServerError();
            }

            [$class, $controllerMethodVariables] = $routeInfo;

            $controller = $this->container->get($class);

            /** @phpstan-ignore-next-line */
            return call_user_func_array([$controller, '__invoke'], $controllerMethodVariables);
        } catch (\Throwable $exception) {
            return new Response($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getControllerRoute(AbstractController $controller): Route
    {
        $reflectionClass = new \ReflectionClass($controller);
        $attributes = $reflectionClass->getMethod('__invoke')->getAttributes();
        /** @var Route $route */
        $route = $attributes[0]->newInstance();

        return $route;
    }
}