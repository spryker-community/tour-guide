<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Communication\Controller;

use Generated\Shared\Transfer\TourGuideTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerCommunity\Zed\TourGuide\Communication\TourGuideCommunicationFactory getFactory()
 * @method \SprykerCommunity\Zed\TourGuide\Business\TourGuideFacadeInterface getFacade()
 */
class TourController extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    public function indexAction(): array
    {
        $tourGuideTable = $this->getFactory()->createTourGuideTable();

        return $this->viewResponse([
            'tourGuideTable' => $tourGuideTable->render(),
        ]);
    }

    public function tableAction(): JsonResponse
    {
        $tourGuideTable = $this->getFactory()->createTourGuideTable();

        return $this->jsonResponse(
            $tourGuideTable->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request): array|RedirectResponse
    {
        $tourGuideTransfer = new TourGuideTransfer();
        $tourGuideForm = $this->getFactory()->createTourGuideForm($tourGuideTransfer)->handleRequest($request);

        if ($tourGuideForm->isSubmitted() && $tourGuideForm->isValid()) {
            /** @var \Generated\Shared\Transfer\TourGuideTransfer $tourGuideTransfer */
            $tourGuideTransfer = $tourGuideForm->getData();
            $this->getFacade()->createTourGuide($tourGuideTransfer);

            $this->addSuccessMessage('Tour guide created successfully.');

            return $this->redirectResponse('/tour-guide/tour');
        }

        return $this->viewResponse([
            'tourGuideForm' => $tourGuideForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request): array|RedirectResponse
    {
        $idTourGuide = $this->castId($request->query->get('id-tour-guide'));

        $tourGuideTransfer = $this->getFacade()->findTourGuideById($idTourGuide);

        if ($tourGuideTransfer === null) {
            $this->addErrorMessage('Tour guide not found.');

            return $this->redirectResponse('/tour-guide/tour');
        }

        $tourGuideForm = $this->getFactory()->createTourGuideForm($tourGuideTransfer)->handleRequest($request);

        if ($tourGuideForm->isSubmitted() && $tourGuideForm->isValid()) {
            /** @var \Generated\Shared\Transfer\TourGuideTransfer $tourGuideTransfer */
            $tourGuideTransfer = $tourGuideForm->getData();
            $this->getFacade()->updateTourGuide($tourGuideTransfer);

            $this->addSuccessMessage('Tour guide updated successfully.');

            return $this->redirectResponse('/tour-guide/tour');
        }

        return $this->viewResponse([
            'tourGuideForm' => $tourGuideForm->createView(),
            'idTourGuide' => $idTourGuide,
        ]);
    }

    public function deleteAction(Request $request): RedirectResponse
    {
        $idTourGuide = $this->castId($request->query->get('id-tour-guide'));

        $result = $this->getFacade()->deleteTourGuide($idTourGuide);

        if ($result) {
            $this->addSuccessMessage('Tour guide deleted successfully.');
        } else {
            $this->addErrorMessage('Tour guide could not be deleted.');
        }

        return $this->redirectResponse('/tour-guide/tour');
    }
}
