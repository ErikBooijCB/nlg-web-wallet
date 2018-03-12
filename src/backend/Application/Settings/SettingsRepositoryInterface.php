<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Settings;

interface SettingsRepositoryInterface
{
    /**
     * @param string $key
     * @return void
     * @throws UnableToDeleteSettingException
     */
    public function delete(string $key);

    /**
     * @param string $key
     * @return mixed
     * @throws InvalidSettingValueException
     * @throws UnableToRetrieveSettingException
     */
    public function get(string $key);

    /**
     * @param string $key
     * @param $value
     * @return void
     * @throws UnableToUpdateSettingException
     */
    public function set(string $key, $value);
}
