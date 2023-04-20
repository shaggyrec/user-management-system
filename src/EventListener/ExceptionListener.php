<?php

namespace App\EventListener;

use Doctrine\DBAL\Exception\ConstraintViolationException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * Class ExceptionListener
 *
 * @package App\EventListener
 */
class ExceptionListener
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ExceptionEvent $event
     * @return void
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = new JsonResponse();
        $response->setContent(
            json_encode(
                [
                    'error' => $this->generateMessage($exception),
                ],
            ),
        );

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }

    /**
     * @param Throwable $exception
     * @return string
     */
    private function generateMessage(Throwable $exception): string
    {
        if (
            $exception instanceof HttpExceptionInterface
            || $this->container->getParameter('kernel.environment') !== 'dev'
        ) {
            $exception->getMessage();
        }
        if ($exception instanceof ConstraintViolationException) {
            return 'You are trying to create duplicate';
        }

        return (string) $exception;
    }
}
