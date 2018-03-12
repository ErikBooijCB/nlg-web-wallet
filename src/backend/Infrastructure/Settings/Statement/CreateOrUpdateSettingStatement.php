<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Settings\Statement;

use GuldenWallet\Backend\Infrastructure\Database\PreparableStatement;

/**
 * @codeCoverageIgnore
 */
class CreateOrUpdateSettingStatement implements PreparableStatement
{
    /** @var string */
    private $key;

    /** @var mixed */
    private $value;

    /**
     * @param string $key
     * @param string $value
     */
    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return [
            ':settingKey' => $this->key,
            ':settingValue' => $this->value,
        ];
    }

    /**
     * @return string
     */
    public function getStatement(): string
    {
        return '
          INSERT INTO settings
            (SETTING_KEY, SETTING_VALUE) VALUES (:settingKey, :settingValue)
            ON DUPLICATE KEY UPDATE SETTING_VALUE = VALUES(SETTING_VALUE)
        ';
    }
}
