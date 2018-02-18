<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Access;

use GuldenWallet\Backend\Infrastructure\Database\PreparableStatement;

class FetchCredentialsStatement implements PreparableStatement
{
    /**
     * @return array
     */
    public function getParameters(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getStatement(): string
    {
        return '
            SELECT SETTING_KEY, SETTING_VALUE FROM user_details WHERE SETTING_KEY IN (\'EMAIL\', \'PASSWORD_HASH\')
        ';
    }
}
