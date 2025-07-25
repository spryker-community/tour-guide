<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerCommunity\Zed\TourGuide\Communication\TourGuideCommunicationFactory getFactory()
 * @method \SprykerCommunity\Zed\TourGuide\Business\TourGuideFacadeInterface getFacade()
 */
final class EventController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function startAction(Request $request): JsonResponse
    {
        $idTourGuide = (int)$request->request->get('idTourGuide');
        $tourVersion = (int)$request->request->get('tourVersion', 1);

        $tourGuideEventTransfer = $this->getFacade()->trackTourGuideEvent($idTourGuide, 'start', $tourVersion);

        return $this->jsonResponse([
            'success' => true,
            'idTourGuideEvent' => $tourGuideEventTransfer->getIdTourGuideEvent(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function pauseAction(Request $request): JsonResponse
    {
        $idTourGuide = $this->castId($request->request->get('idTourGuide'));
        $tourVersion = (int)$request->request->get('tourVersion', 1);

        $tourGuideEventTransfer = $this->getFacade()->trackTourGuideEvent($idTourGuide, 'pause', $tourVersion);

        return $this->jsonResponse([
            'success' => true,
            'idTourGuideEvent' => $tourGuideEventTransfer->getIdTourGuideEvent(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function finishAction(Request $request): JsonResponse
    {
        $idTourGuide = $this->castId($request->request->get('idTourGuide'));
        $tourVersion = (int)$request->request->get('tourVersion', 1);

        $tourGuideEventTransfer = $this->getFacade()->trackTourGuideEvent($idTourGuide, 'finish', $tourVersion);

        return $this->jsonResponse([
            'success' => true,
            'idTourGuideEvent' => $tourGuideEventTransfer->getIdTourGuideEvent(),
        ]);
    }
}
