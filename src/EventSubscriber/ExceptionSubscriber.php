<?php
namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(private LoggerInterface $logger) {}

    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        // 🔐 Log les erreurs serveur (500)
        if (!$exception instanceof HttpExceptionInterface) {
            $this->logger->error($exception->getMessage(), [
                'trace' => $exception->getTraceAsString(),
            ]);
        }

        $statusCode = $exception instanceof HttpExceptionInterface
            ? $exception->getStatusCode()
            : JsonResponse::HTTP_INTERNAL_SERVER_ERROR;

        $response = new JsonResponse([
            'error' => $this->getErrorLabel($statusCode),
            'message' => $exception->getMessage(),
        ], $statusCode);

        $event->setResponse($response);
    }

    private function getErrorLabel(int $statusCode): string
    {
        return match ($statusCode) {
            400 => 'Requête invalide',
            401 => 'Non autorisé',
            403 => 'Accès interdit',
            404 => 'Ressource non trouvée',
            409 => 'Conflit',
            422 => 'Erreur de validation',
            500 => 'Erreur interne du serveur',
            default => 'Erreur',
        };
    }
}
