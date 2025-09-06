<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Communication\Plugin\Application;

use Generated\Shared\Transfer\TourGuideStepCollectionTransfer;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \SprykerCommunity\Zed\TourGuide\Business\TourGuideFacadeInterface getFacade()
 * @method \SprykerCommunity\Zed\TourGuide\Communication\TourGuideCommunicationFactory getFactory()
 */
class TourGuideTwigFunctionPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addFunction($this->getRenderTourGuideAssetsFunction());

        return $twig;
    }

    protected function getRenderTourGuideAssetsFunction(): TwigFunction
    {
        return new TwigFunction('renderTourGuideAssets', function (?string $route = null) {
            $request = $this->getFactory()->getRequestStack()->getCurrentRequest();
            $route = $request->getPathInfo();

            $basePath = '/assets';
            $cssPath = $basePath . '/css/tour-guide.css';
            $jsPath = $basePath . '/js/tour-guide.js';

            $tourGuideTransfer = $this->getFacade()->findTourGuideByRoute($route);

            if ($tourGuideTransfer === null) {
                return '';
            }

            $tourGuideStepCollection = $this->getFacade()
                ->getTourGuideStepsByTourGuideId($tourGuideTransfer->getIdTourGuide());

            $steps = $this->normalizeTourSteps($tourGuideStepCollection);

            $currentUrl = $request->getSchemeAndHttpHost() . $request->getRequestUri();
            $requestData = [
                'url' => $currentUrl,
                'path' => $route,
                'method' => $request->getMethod(),
                'query' => $request->query->all(),
                'headers' => $request->headers->all(),
            ];

            $encodedConfig = json_encode([
                'idTourGuide' => $tourGuideTransfer->getIdTourGuide(),
                'route' => $route,
                'version' => $tourGuideTransfer->getVersion(),
                'steps' => $steps,
                'defaultStepOptions' => [],
                'currentUrl' => $currentUrl,
                'currentPath' => $route,
                'request' => $requestData,
            ]);

            return sprintf(
                '<link rel="stylesheet" href="%s">' . PHP_EOL .
                '<script type="module" src="%s"></script>' . PHP_EOL .
                '<script>' . PHP_EOL .
                '  window.tourConfig = %s;' . PHP_EOL .
                '  document.addEventListener("DOMContentLoaded", function() {' . PHP_EOL .
                '    if (window.autoStartTourGuide) {' . PHP_EOL .
                '      window.autoStartTourGuide(window.tourConfig);' . PHP_EOL .
                '    }' . PHP_EOL .
                '  });' . PHP_EOL .
                '</script>',
                $cssPath,
                $jsPath,
                $encodedConfig,
            );
        }, ['is_safe' => ['html']]);
    }

    protected function normalizeTourSteps(TourGuideStepCollectionTransfer $collection): array
    {
        $steps = [];

        foreach ($collection->getTourGuideSteps() as $stepTransfer) {
            if (!$stepTransfer->getIsActive()) {
                continue;
            }

            $step = [
                'id' => $stepTransfer->getIdTourGuideStep(),
                'title' => $stepTransfer->getTitle(),
                'text' => $stepTransfer->getText(),
                'attachTo' => [
                    'element' => $stepTransfer->getAttachToElement(),
                    'on' => $stepTransfer->getAttachToPosition(),
                ],
            ];

            $steps[] = $step;
        }

        return $steps;
    }
}
