<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Business;

use SprykerCommunity\Zed\TourGuide\Business\Reader\TourGuideReaderInterface;
use SprykerCommunity\Zed\TourGuide\Business\Reader\TourGuideReader;
use SprykerCommunity\Zed\TourGuide\Business\Validator\RouteValidator;
use SprykerCommunity\Zed\TourGuide\Business\Validator\RouteValidatorInterface;
use SprykerCommunity\Zed\TourGuide\Business\Writer\TourGuideWriterInterface;
use SprykerCommunity\Zed\TourGuide\Business\Writer\TourGuideWriter;
use SprykerCommunity\Zed\TourGuide\Business\Collector\ZedRouteCollector;
use SprykerCommunity\Zed\TourGuide\Business\Collector\ZedRouteCollectorInterface;
use SprykerCommunity\Zed\TourGuide\TourGuideDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Symfony\Component\Routing\RouterInterface;

/**
 * @method \SprykerCommunity\Zed\TourGuide\Persistence\TourGuideRepositoryInterface getRepository()
 * @method \SprykerCommunity\Zed\TourGuide\Persistence\TourGuideEntityManagerInterface getEntityManager()
 */
final class TourGuideBusinessFactory extends AbstractBusinessFactory
{
    public function createTourGuideReader(): TourGuideReaderInterface
    {
        return new TourGuideReader(
            $this->getRepository()
        );
    }

    public function createTourGuideWriter(): TourGuideWriterInterface
    {
        return new TourGuideWriter(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    public function createRouteValidator(): RouteValidatorInterface
    {
        return new RouteValidator(
            $this->createZedRouteCollector()
        );
    }

    public function createZedRouteCollector(): ZedRouteCollectorInterface
    {
        return new ZedRouteCollector(
            $this->getRouter()
        );
    }

    public function getRouter(): RouterInterface
    {
        return $this->getProvidedDependency(TourGuideDependencyProvider::SERVICE_ROUTER);
    }
}
