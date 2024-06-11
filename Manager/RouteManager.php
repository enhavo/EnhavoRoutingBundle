<?php
/**
 * RouteManager.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\Manager;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\RoutingBundle\AutoGenerator\AutoGenerator;
use Sylius\Component\Resource\Factory\FactoryInterface;

class RouteManager
{
    /** @var AutoGenerator */
    private $autoGenerator;

    /** @var FactoryInterface */
    private $routeFactory;

    /** @var EntityManager */
    private $em;

    public function __construct(AutoGenerator $autoGenerator, FactoryInterface $routeFactory, EntityManager $em)
    {
        $this->autoGenerator = $autoGenerator;
        $this->routeFactory = $routeFactory;
        $this->em = $em;
    }

    public function update($resource)
    {
        if ($resource instanceof Routeable) {
            if ($resource->getRoute() === null) {
                /** @var Route $route */
                $route = $this->routeFactory->createNew();
                $resource->setRoute($route);
            }

            $route = $resource->getRoute();
            $route->setContent($resource);

            if (empty($route->getName())) {
                $route->generateRouteName();
            }
        }

        $this->autoGenerator->generate($resource);

        if ($resource instanceof Routeable) {
            $route = $resource->getRoute();
            if ($route && empty($route->getStaticPrefix())) {
                $resource->setRoute(null);
                $this->em->remove($route);
            }
        }
    }
}
