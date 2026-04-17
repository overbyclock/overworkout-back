<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AsEventListener(event: 'kernel.exception', priority: 10)]
readonly class ApiExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        if (!$this->isApiRequest($event)) {
            return;
        }

        $throwable = $event->getThrowable();
        $response = $this->createResponse($throwable);

        $event->setResponse($response);
    }

    private function isApiRequest(ExceptionEvent $event): bool
    {
        $request = $event->getRequest();

        // Consider API requests those accepting JSON or with JSON content-type
        $accept = $request->headers->get('Accept', '');
        $contentType = $request->headers->get('Content-Type', '');

        return str_contains($accept, 'application/json')
            || str_contains($contentType, 'application/json');
    }

    private function createResponse(\Throwable $throwable): JsonResponse
    {
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $data = [
            'error' => 'Internal Server Error',
            'message' => 'An unexpected error occurred.',
        ];

        if ($throwable instanceof ValidationFailedException) {
            $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
            $data['error'] = 'Validation Failed';
            $data['message'] = 'The submitted data is invalid.';
            $data['violations'] = [];

            foreach ($throwable->getViolations() as $violation) {
                $data['violations'][] = [
                    'property' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                ];
            }
        } elseif ($throwable instanceof AccessDeniedException) {
            $statusCode = Response::HTTP_FORBIDDEN;
            $data['error'] = 'Access Denied';
            $data['message'] = 'You do not have permission to access this resource.';
        } elseif ($throwable instanceof HttpExceptionInterface) {
            $statusCode = $throwable->getStatusCode();
            $data['error'] = Response::$statusTexts[$statusCode] ?? 'Error';
            $data['message'] = $throwable->getMessage() ?: $data['error'];
        } elseif ($throwable instanceof \InvalidArgumentException) {
            $statusCode = Response::HTTP_BAD_REQUEST;
            $data['error'] = 'Bad Request';
            $data['message'] = $throwable->getMessage() ?: 'Invalid argument provided.';
        }

        $data['code'] = $statusCode;

        $response = new JsonResponse($data, $statusCode);

        if ($throwable instanceof HttpExceptionInterface) {
            $response->headers->add($throwable->getHeaders());
        }

        return $response;
    }
}
