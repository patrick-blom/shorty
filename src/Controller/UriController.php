<?php

declare(strict_types=1);

namespace App\Controller;

use App\Factory\PutUriRequestFactory;
use App\Service\Authentication\TokenAuthenticationInterface;
use App\Service\UriManager;
use App\Struct\GetUriRequest;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UriController extends AbstractController
{
    /**
     * @Route("/{short_code}", methods={"GET"})
     * @param Request $request
     * @param UriManager $manager
     *
     * @return RedirectResponse
     */
    public function getUri(Request $request, UriManager $manager): RedirectResponse
    {
        $shortCode = $request->attributes->get('short_code');
        if ($shortCode === null) {
            return $this->createRedirectResponseTo('/');
        }

        try {
            $uri = $manager->getGuaranteedUri(new GetUriRequest($shortCode));

            return $this->createRedirectResponseTo($uri->getOriginalUrl());
        } catch (NonUniqueResultException $exception) {
        }

        return $this->createRedirectResponseTo('/');
    }

    /**
     * @Route("/", methods={"PUT"})
     *
     * @param Request $request
     * @param UriManager $manager
     * @param TokenAuthenticationInterface $basicPutAuthentication
     *
     * @return Response
     */
    public function putUri(
        Request $request,
        UriManager $manager,
        TokenAuthenticationInterface $basicPutAuthentication
    ): Response {
        $token = $request->headers->get('authorization', '');
        if (false === $basicPutAuthentication->validateTokenAuthentication($token)) {
            return $this->createBadRequestResponse();
        }

        try {
            $purUriRequest = (new PutUriRequestFactory)->fromDirtyRequestContent($request);
            $uriEntity     = $manager->putUri($purUriRequest);
            $statusText    = $uriEntity->getShortCode();
        } catch (Exception $exception) {
            return $this->createBadRequestResponse();
        }

        return new Response(
            $statusText,
            Response::HTTP_CREATED
        );
    }

    /**
     * Create redirect response to given uri
     *
     * @param string $uri
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function createRedirectResponseTo(string $uri): RedirectResponse
    {
        return new RedirectResponse($uri, Response::HTTP_MOVED_PERMANENTLY);
    }

    /**
     * @Route("/", methods={"GET","HEAD","POST","DELETE","OPTIONS","PATCH","CONNECT","PURGE","TRACE"})
     */
    public function index(): Response
    {
        return new Response(
            Response::$statusTexts[Response::HTTP_I_AM_A_TEAPOT],
            Response::HTTP_I_AM_A_TEAPOT
        );
    }

    /**
     * Create a bad request response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function createBadRequestResponse(): Response
    {
        return new Response(
            Response::$statusTexts[Response::HTTP_BAD_REQUEST],
            Response::HTTP_BAD_REQUEST
        );
    }
}
