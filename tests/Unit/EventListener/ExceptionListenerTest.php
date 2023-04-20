<?php

namespace Unit\EventListener;

use App\EventListener\ExceptionListener;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\DBAL\Exception\DriverException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class ExceptionListenerTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     * @param $exceptionObject
     * @param $expectedStatusCode
     * @param $expectedContent
     * @return void
     */
    public function testHandlesExceptions($exceptionObject, $expectedStatusCode, $expectedContent): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $listener = new ExceptionListener($container);

        $event = new ExceptionEvent(
            $this->createMock(KernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MAIN_REQUEST,
            $exceptionObject,
        );

        $listener->onKernelException($event);

        $response = $event->getResponse();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertStringContainsString($expectedContent, $response->getContent());
    }

    /**
     * @return array[]
     */
    public function dataProvider(): array
    {
        return  [
            [
                'exception' => new HttpException(JsonResponse::HTTP_BAD_REQUEST, 'Bad Request'),
                'expectedStatusCode' => JsonResponse::HTTP_BAD_REQUEST,
                'expectedContent' => 'Bad Request',
            ],
            [
                'exception' => new ConstraintViolationException(self::createMock(DriverException::class), null),
                'expectedStatusCode' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'expectedContent' => '{"error":"You are trying to create duplicate"}',
            ],
        ];
    }
}
