<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Settings\Statement;

use GuldenWallet\Backend\Infrastructure\Database\PreparableStatement;

/**
 * @codeCoverageIgnore
 */
class DeleteSettingStatement implements PreparableStatement
{
    /** @var string */
    private $key;

    /**
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return [
            ':settingKey' => $this->key,
        ];
    }

    /**
     * @return string
     */
    public function getStatement(): string
    {
        return '
            DELETE FROM settings WHERE SETTING_KEY = :settingKey
        ';
    }
}
