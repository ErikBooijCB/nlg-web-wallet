<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Database;

interface PreparableStatement
{
    /**
     * @return array
     */
    public function getParameters(): array;

    /**
     * @return string
     */
    public function getStatement(): string;
}
