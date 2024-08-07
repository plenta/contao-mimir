<?php

declare(strict_types=1);

/**
 * Plenta Mimir Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2023, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @link          https://github.com/plenta/
 */

namespace Plenta\Mimir\Helper;

use Contao\CoreBundle\Exception\AjaxRedirectResponseException;
use Contao\CoreBundle\Exception\InternalServerErrorHttpException;
use Contao\CoreBundle\Exception\NoContentResponseException;
use Contao\CoreBundle\Exception\RedirectResponseException;
use Contao\CoreBundle\Exception\ResponseException;
use Contao\CoreBundle\String\SimpleTokenParser;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Slack
{
    public const IGNORED_EXCEPTIONS = [
        'accessDenied' => AccessDeniedHttpException::class,
        'badRequest' => BadRequestHttpException::class,
        'internalServerError' => InternalServerErrorHttpException::class,
        'notFound' => NotFoundHttpException::class,
        'serviceUnavailable' => ServiceUnavailableHttpException::class,
        'unauthorized' => UnauthorizedHttpException::class,
        'redirect' => RedirectResponseException::class,
        'contaoResponse' => ResponseException::class,
        'accessDeniedSecurity' => AccessDeniedException::class,
        'nocontent' => NoContentResponseException::class,
        'ajaxredirect' => AjaxRedirectResponseException::class,
    ];

    public function __construct(
        protected HttpClientInterface $client,
        protected array $mimirConfig,
        protected string $debug,
        protected SimpleTokenParser $parser,
        protected RequestStack $requestStack
    ) {
    }

    public function handleException(\Throwable $exception): void
    {
        if ($this->shouldReport($exception)) {
            $this->sendMessage($this->buildMessage($exception));
        }
    }

    protected function sendMessage($msg): void
    {
        $body = ['text' => $msg];
        $this->client->request('POST', $this->mimirConfig['webhook'], [
            'json' => $body,
        ]);
    }

    protected function buildMessage(\Throwable $exception): string
    {
        return $this->parser->parse($this->mimirConfig['message'], [
            'exception_message' => $exception->getMessage(),
            'exception_code' => $exception->getCode(),
            'exception_file' => $exception->getFile(),
            'exception_line' => $exception->getLine(),
            'exception_trace' => $exception->getTraceAsString(),
            'exception_url' => $this->requestStack->getCurrentRequest()->getUri(),
            'exception_class' => \get_class($exception),
        ]);
    }

    protected function shouldReport(\Throwable $exception): bool
    {
        if ($this->debug && !$this->mimirConfig['debug']) {
            return false;
        }

        if (!$this->mimirConfig['webhook']) {
            return false;
        }

        if ((\in_array(\get_class($exception), self::IGNORED_EXCEPTIONS, true) || \in_array(\get_class($exception), $this->mimirConfig['exceptions']['blacklist'], true)) && !\in_array(\get_class($exception), $this->mimirConfig['exceptions']['whitelist'], true)) {
            return false;
        }

        return true;
    }

    /**
     * @param array $mimirConfig
     * @return void
     *
     * @Internal
     */
    public function setMimirConfig(array $mimirConfig): void
    {
        $this->mimirConfig = $mimirConfig;
    }
}
