<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Business;

use Spryker\Zed\Acl\Business\AclFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\User\Business\UserFacadeInterface;
use SprykerCommunity\Zed\TourGuide\Business\Collector\ZedRouteCollector;
use SprykerCommunity\Zed\TourGuide\Business\Collector\ZedRouteCollectorInterface;
use SprykerCommunity\Zed\TourGuide\Business\Reader\TourGuideReader;
use SprykerCommunity\Zed\TourGuide\Business\Reader\TourGuideReaderInterface;
use SprykerCommunity\Zed\TourGuide\Business\Validator\RouteValidator;
use SprykerCommunity\Zed\TourGuide\Business\Validator\RouteValidatorInterface;
use SprykerCommunity\Zed\TourGuide\Business\Sanitizer\TourGuideSanitizer;
use SprykerCommunity\Zed\TourGuide\Business\Sanitizer\TourGuideSanitizerInterface;
use SprykerCommunity\Zed\TourGuide\Business\Writer\TourGuideEventWriter;
use SprykerCommunity\Zed\TourGuide\Business\Writer\TourGuideEventWriterInterface;
use SprykerCommunity\Zed\TourGuide\Business\Writer\TourGuideWriter;
use SprykerCommunity\Zed\TourGuide\Business\Writer\TourGuideWriterInterface;
use SprykerCommunity\Zed\TourGuide\TourGuideDependencyProvider;
use Symfony\Component\Routing\RouterInterface;

/**
 * @method \SprykerCommunity\Zed\TourGuide\Persistence\TourGuideRepositoryInterface getRepository()
 * @method \SprykerCommunity\Zed\TourGuide\Persistence\TourGuideEntityManagerInterface getEntityManager()
 */
class TourGuideBusinessFactory extends AbstractBusinessFactory
{
    public function createTourGuideReader(): TourGuideReaderInterface
    {
        return new TourGuideReader(
            $this->getRepository(),
            $this->getUserFacade(),
            $this->getAclFacade(),
        );
    }

    public function createTourGuideWriter(): TourGuideWriterInterface
    {
        return new TourGuideWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createTourGuideSanitizer(),
        );
    }

    public function createTourGuideSanitizer(): TourGuideSanitizerInterface
    {
        return new TourGuideSanitizer();
    }

    public function createRouteValidator(): RouteValidatorInterface
    {
        return new RouteValidator(
            $this->createZedRouteCollector(),
        );
    }

    public function createZedRouteCollector(): ZedRouteCollectorInterface
    {
        return new ZedRouteCollector(
            $this->getRouter(),
        );
    }

    public function getRouter(): RouterInterface
    {
        return $this->getProvidedDependency(TourGuideDependencyProvider::SERVICE_ROUTER);
    }

    public function getUserFacade(): UserFacadeInterface
    {
        return $this->getProvidedDependency(TourGuideDependencyProvider::FACADE_USER);
    }

    public function getAclFacade(): AclFacadeInterface
    {
        return $this->getProvidedDependency(TourGuideDependencyProvider::FACADE_ACL);
    }

    public function createTourGuideEventWriter(): TourGuideEventWriterInterface
    {
        return new TourGuideEventWriter(
            $this->getEntityManager(),
            $this->getRepository(),
        );
    }
}
