<?php

declare(strict_types=1);

/**
 * Plenta Mimir Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2023, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @link          https://github.com/plenta/
 */

namespace Plenta\Mimir;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PlentaMimirBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
