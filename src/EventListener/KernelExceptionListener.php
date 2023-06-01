<?php

declare(strict_types=1);

/**
 * Plenta Mimir Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2023, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @link          https://github.com/plenta/
 */

namespace Plenta\Mimir\EventListener;

use Plenta\Mimir\Helper\Slack;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(event: 'kernel.exception', method: 'onKernelException')]
class KernelExceptionListener
{
    public function __construct(protected Slack $slack)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $this->slack->handleException($event->getThrowable());
    }
}
