<?php

namespace App\Validator\Application\EventListener;

use App\Validator\Application\Manager\RequestValidatorManager;
use App\Validator\Domain\Enums\RequestStatusEnum;
use App\Validator\Domain\PostControllerInterface;
use Exception;
use JsonException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PostDataListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly RequestValidatorManager $requestValidatorManager
    ) {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        $request = $event->getRequest();

        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof PostControllerInterface){
            if ($request->isMethod('POST')) {
                try {
                    $data = json_decode(
                        json: $request->getContent(),
                        associative: true,
                        flags: JSON_THROW_ON_ERROR
                    );

                    $requestValidator = $this->requestValidatorManager->validate(
                        requestName: $data['type'],
                        data: $data['data']
                    );

                    if ($requestValidator !== false){
                        $controller->setData($requestValidator);
                    } else {
                        $event->setController(function () {
                            return new JsonResponse(data: [
                                'Status' => RequestStatusEnum::ERROR,
                                'Message' => 'Request validation failed'
                            ], status: JsonResponse::HTTP_BAD_REQUEST);
                        });
                    }
                } catch (JsonException|Exception $e) {
                    $event->setController(function () use ($e) {
                        return new JsonResponse(data: [
                            'Status' => RequestStatusEnum::ERROR,
                            'Message' => $e->getMessage()
                        ], status: JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                    });

                    $event->stopPropagation();
                }
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