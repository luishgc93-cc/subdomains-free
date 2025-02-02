<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Controller\AccountUserController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\SecurityBundle\Security;

final class EmailVerificationSubscriber implements EventSubscriberInterface
{
	private $security;
	private $twig;
	private $accountUserController;

	public function __construct(Security $security, \Twig\Environment $twig, AccountUserController $accountUserController)
	{
		$this->security = $security;
		$this->twig = $twig;
		$this->accountUserController = $accountUserController;
	}

	public static function getSubscribedEvents()
	{
		return [
			KernelEvents::REQUEST => 'onKernelRequest',
		];
	}

	public function onKernelRequest(RequestEvent $event)
	{
		$user = $this->security->getUser();
		$request = $event->getRequest();

		if ( !str_contains($request->getPathInfo(), '/verify/email') && $user && !$user->isVerified()) {
			$content = $this->twig->render(
				'User/Registration/veryAccount.html.twig',
				[
					'title' => 'Verificar Email'
				]
			);

			$response = new Response($content);
			$event->setResponse($response);
		}
 
	}
}