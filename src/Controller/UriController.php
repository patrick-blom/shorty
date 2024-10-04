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
     * @param Request $request
     * @param UriManager $manager
     *
     * @return RedirectResponse
     */
    #[Route('/{short_code}', methods: ['GET'])]
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
     * @param Request $request
     * @param UriManager $manager
     * @param TokenAuthenticationInterface $basicPutAuthentication
     *
     * @return Response
     */
    #[Route('/', methods: ['PUT'])]
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
     * @param Request $request
     * @param UriManager $manager
     * @param TokenAuthenticationInterface $basicDeleteAuthentication
     *
     * @return Response
     */
    #[Route('/', methods: ['DELETE'])]
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
                    $this->getStatusTextForResponseCode(Response::HTTP_GONE),
                    Response::HTTP_GONE
                );
            }
        } catch (Exception $exception) {
            return $this->createBadRequestResponse();
        }

        return $response;
    }

    /**
     * @param Request $request
     * @param UriManager $manager
     * @param TokenAuthenticationInterface $basicDeleteAuthentication
     *
     * @return Response
     */
    #[Route('/{short_code}', methods: ['DELETE'])]
    public function deleteUriByPath(
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
            $shortCode = $request->attributes->get('short_code');
            $deleteUriRequest = (new DeleteUriRequestFactory())->fromString($shortCode);
            if ($manager->deleteUri($deleteUriRequest)) {
                $response = new Response(
                    $this->getStatusTextForResponseCode(Response::HTTP_GONE),
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
     * @return RedirectResponse
     */
    private function createRedirectResponseTo(string $uri): RedirectResponse
    {
        return new RedirectResponse($uri, Response::HTTP_MOVED_PERMANENTLY);
    }

    #[Route('/', methods: ['GET','HEAD','POST','OPTIONS','PATCH','CONNECT','PURGE','TRACE'])]
    public function index(): Response
    {
        return new Response(
            $this->getStatusTextForResponseCode(Response::HTTP_I_AM_A_TEAPOT),
            Response::HTTP_I_AM_A_TEAPOT
        );
    }

    /**
     * Create a bad request response
     *
     * @return Response
     */
    private function createBadRequestResponse(): Response
    {
        return new Response(
            $this->getStatusTextForResponseCode(Response::HTTP_BAD_REQUEST),
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private function getTokenFromRequestHeader(Request $request): string
    {
        return $request->headers->get('authorization', '');
    }

    /**
     * @param int $responseCode
     *
     * @return string
     */
    private function getStatusTextForResponseCode(int $responseCode): string
    {
        $statusTexts = Response::$statusTexts;

        if (array_key_exists($responseCode, $statusTexts)) {
            return $statusTexts[$responseCode];
        }

        return '';
    }
}
