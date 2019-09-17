<?php

declare(strict_types=1);

namespace App\Controller;

use App\Factory\DeleteUriRequestFactory;
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
        $token = $this->getTokenFromRequestHeader($request);
        if (false === $basicPutAuthentication->validateTokenAuthentication($token)) {
            return $this->createBadRequestResponse();
        }

        try {
            $putUriRequest = (new PutUriRequestFactory)->fromDirtyRequestContent($request);
            $uriEntity     = $manager->putUri($putUriRequest);
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
     * @Route("/", methods={"DELETE"})
     *
     * @param Request $request
     * @param UriManager $manager
     * @param TokenAuthenticationInterface $basicDeleteAuthentication
     *
     * @return Response
     */
    public function deleteUri(
        Request $request,
        UriManager $manager,
        TokenAuthenticationInterface $basicDeleteAuthentication
    ): Response {
        $token = $this->getTokenFromRequestHeader($request);
        if (false === $basicDeleteAuthentication->validateTokenAuthentication($token)) {
            return $this->createBadRequestResponse();
        }

        $response = $this->createBadRequestResponse();

        try {
            $deleteUriRequest = (new DeleteUriRequestFactory())->fromDirtyRequestContent($request);
            if ($manager->deleteUri($deleteUriRequest)) {
                $response = new Response(
                    Response::$statusTexts[Response::HTTP_GONE],
                    Response::HTTP_GONE
                );
            }
        } catch (Exception $exception) {
            return $this->createBadRequestResponse();
        }

        return $response;
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
     * @Route("/", methods={"GET","HEAD","POST","OPTIONS","PATCH","CONNECT","PURGE","TRACE"})
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

    /**
     * @param Request $request
     *
     * @return string
     */
    private function getTokenFromRequestHeader(Request $request)
    {
        return (string)$request->headers->get('authorization', '');
    }
}
