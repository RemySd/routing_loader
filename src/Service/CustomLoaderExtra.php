<?php

namespace App\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class CustomLoaderExtra extends Loader
{
    private $isLoaded = false;

    /**
     * @inheritDoc
     */
    public function load($resource, $type = null)
    {
        if (true === $this->isLoaded) {
            throw new \RuntimeException('Do not add the "extra" loader twice');
        }

        $allAnnotations = [];

        $annotationReader = new AnnotationReader();

        $finder = new Finder();
        $finder->files()->in(__DIR__ . '/../Controller');

        foreach ($finder as $file) {
            $className = str_replace(".php", "", $file->getRelativePathname());
            $class = new \ReflectionClass('App\\Controller\\' . $className);
            foreach($class->getMethods() as $method) {
                if ($annotation = ($annotationReader->getMethodAnnotations($method))) {
                    $allAnnotations[$className] = array_merge($annotation, [
                        'methodName' => $method->getName()
                    ]);
                }
            }
        }

        $routes = new RouteCollection();

        foreach($allAnnotations as $key => $value) {
            foreach(['fr', 'en'] as $locale) {
                $defaults = [
                    '_controller' => 'App\\Controller\\' . $key . ':' . $value['methodName'],
                ];
                $route = new Route('/' . $locale . $value[0]->getPath(), $defaults);
                $routeName = $value[0]->getName() . '_' . $locale;
                $routes->add($routeName, $route);
            }
        }

        $this->isLoaded = true;

        return $routes;
    }

    /**
     * @inheritDoc
     */
    public function supports($resource, $type = null)
    {
        return 'extra' === $type;
    }
}
