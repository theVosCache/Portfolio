<?php

namespace App\Auth\Application\EventListener;

use App\Auth\Domain\Enums\ControllerStatusEnum;
use App\Auth\Infrastructure\Controller\AbstractBasePostController;
use Exception;
use JsonException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PostControllerDataListener implements EventSubscriberInterface
{
    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();
        $request = $event->getRequest();

        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof AbstractBasePostController) {
            if ($request->isMethod('POST')) {
                try {
                    $dataFields = $controller->getDataFields();
                    $data = json_decode(
                        json: $request->getContent(),
                        associative: true,
                        flags: JSON_THROW_ON_ERROR
                    );

                    $this->validatedata(data: $data, dataFields: $dataFields);

                    $controller->setData(data: $data);
                } catch (JsonException|Exception $e) {
                    $event->setController(function () use ($e) {
                        return new JsonResponse(data: [
                            'Status' => ControllerStatusEnum::ERROR,
                            'Message' => $e->getMessage()
                        ]);
                    });

                    $event->stopPropagation();
                }
            }
        }
    }

    /** @throws Exception */
    private function validateData(array $data, array $dataFields): void
    {
        foreach ($dataFields as $dataField) {
            if (!array_key_exists($dataField, $data)) {
                throw new Exception(
                    message: sprintf(
                        "Incomplete Request, Missing %s",
                        $dataField
                    )
                );
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}