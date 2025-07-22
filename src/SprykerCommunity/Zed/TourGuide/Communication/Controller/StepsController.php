<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Communication\Controller;

use Generated\Shared\Transfer\TourGuideStepTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class StepsController extends AbstractController
{
    public function indexAction(Request $request): array
    {
        $idTourGuide = $this->castId($request->query->get('id-tour-guide'));

        $tourGuideTransfer = $this->getFacade()->findTourGuideById($idTourGuide);

        if ($tourGuideTransfer === null) {
            $this->addErrorMessage('Tour guide not found.');

            return $this->viewResponse([]);
        }

        $tourGuideStepCollectionTransfer = $this->getFacade()->getTourGuideStepsByTourGuideId($idTourGuide);

        return $this->viewResponse([
            'tourGuide' => $tourGuideTransfer,
            'tourGuideSteps' => $tourGuideStepCollectionTransfer->getTourGuideSteps(),
        ]);
    }

    public function createAction(Request $request): array|RedirectResponse
    {
        $tourGuideStepTransfer = new TourGuideStepTransfer();

        // Get the fk-tour-guide parameter from the URL and set it on the transfer object
        $fkTourGuide = $request->query->getInt('fk-tour-guide');
        if ($fkTourGuide) {
            $tourGuideStepTransfer->setFkTourGuide($fkTourGuide);
        }

        $tourGuideStepForm = $this->getFactory()->createTourGuideStepForm($tourGuideStepTransfer)->handleRequest($request);

        if ($tourGuideStepForm->isSubmitted() && $tourGuideStepForm->isValid()) {
            /** @var \Generated\Shared\Transfer\TourGuideStepTransfer $tourGuideStepTransfer */
            $tourGuideStepTransfer = $tourGuideStepForm->getData();

            if (!$tourGuideStepTransfer->getFkTourGuide() && $fkTourGuide) {
                $tourGuideStepTransfer->setFkTourGuide($fkTourGuide);
            }

            $this->getFacade()->createTourGuideStep($tourGuideStepTransfer);

            $this->addSuccessMessage('Tour guide step created successfully.');

            return $this->redirectResponse('/tour-guide/steps?id-tour-guide=' . $fkTourGuide);
        }

        return $this->viewResponse([
            'tourGuideStepForm' => $tourGuideStepForm->createView(),
        ]);
    }

    public function editAction(Request $request): array|RedirectResponse
    {
        $idTourGuideStep = $this->castId($request->query->get('id-tour-guide-step'));

        $tourGuideStepTransfer = $this->getFacade()->findTourGuideStepById($idTourGuideStep);

        if ($tourGuideStepTransfer === null) {
            $this->addErrorMessage('Tour guide step not found.');

            return $this->redirectResponse('/tour-guide/tour');
        }

        $tourGuideStepForm = $this->getFactory()->createTourGuideStepForm($tourGuideStepTransfer)->handleRequest($request);

        if ($tourGuideStepForm->isSubmitted() && $tourGuideStepForm->isValid()) {
            /** @var \Generated\Shared\Transfer\TourGuideStepTransfer $tourGuideStepTransfer */
            $tourGuideStepTransfer = $tourGuideStepForm->getData();

            $this->getFacade()->updateTourGuideStep($tourGuideStepTransfer);

            $this->addSuccessMessage('Tour guide step updated successfully.');

            return $this->redirectResponse('/tour-guide/tour');
        }

        return $this->viewResponse([
            'tourGuideStepForm' => $tourGuideStepForm->createView(),
            'idTourGuideStep' => $idTourGuideStep,
        ]);
    }

    public function deleteAction(Request $request): RedirectResponse
    {
        $idTourGuideStep = $this->castId($request->query->get('id-tour-guide-step'));

        $tourGuideStepTransfer = $this->getFacade()->findTourGuideStepById($idTourGuideStep);
        $idTourGuide = null;

        if ($tourGuideStepTransfer !== null) {
            $idTourGuide = $tourGuideStepTransfer->getFkTourGuide();
        }

        $deleted = $this->getFacade()->deleteTourGuideStep($idTourGuideStep);

        if ($deleted) {
            $this->addSuccessMessage('Tour guide step deleted successfully.');
        } else {
            $this->addErrorMessage('Tour guide step not found.');
        }

        if ($idTourGuide !== null) {
            return $this->redirectResponse('/tour-guide/steps?id-tour-guide=' . $idTourGuide);
        }

        return $this->redirectResponse('/tour-guide/tour');
    }
}
