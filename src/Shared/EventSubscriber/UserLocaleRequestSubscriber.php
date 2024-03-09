<?php

declare(strict_types=1);

namespace App\Shared\EventSubscriber;

use Negotiation\AcceptLanguage;
use Negotiation\LanguageNegotiator;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\EventListener\LocaleListener;
use Symfony\Component\HttpKernel\KernelEvents;

class UserLocaleRequestSubscriber implements EventSubscriberInterface
{
    public const NEGOTIABLE_LANGUAGES = [
        'en',
        'pl',
    ];

    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    /**
     * Priority set to before LocaleListener.
     *
     * @see https://symfony.com/doc/4.4/translation/locale.html
     * @see LocaleListener::onKernelRequest()
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 17],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (str_starts_with($request->getRequestUri(), '/api')) {
            return;
        }

        $negotiatedLanguage = $request->getLocale();
        $acceptLanguage = $request->headers->get('Accept-Language');

        $this->logger->debug('Client Accept language', [$acceptLanguage]);

        if (null !== $acceptLanguage) {
            /** @var AcceptLanguage $bestLanguage */
            $bestLanguage = (new LanguageNegotiator())->getBest(
                $acceptLanguage,
                self::NEGOTIABLE_LANGUAGES
            );

            if (null !== $bestLanguage) {
                $negotiatedLanguage = $bestLanguage->getBasePart();
            }
        }

        $this->logger->debug('Client Negotiated language', [$negotiatedLanguage]);

        $locale = $request->getSession()->get('_locale', $negotiatedLanguage);
        $request->setLocale($locale);
    }
}
