<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Settings;

use GuldenWallet\Backend\Application\Settings\InvalidSettingValueException;
use GuldenWallet\Backend\Application\Settings\SettingNotFoundException;
use GuldenWallet\Backend\Application\Settings\SettingsRepositoryInterface;
use GuldenWallet\Backend\Application\Settings\UnableToDeleteSettingException;
use GuldenWallet\Backend\Application\Settings\UnableToRetrieveSettingException;
use GuldenWallet\Backend\Application\Settings\UnableToUpdateSettingException;
use GuldenWallet\Backend\Infrastructure\Database\Prepare;
use GuldenWallet\Backend\Infrastructure\Settings\Statement\CreateOrUpdateSettingStatement;
use GuldenWallet\Backend\Infrastructure\Settings\Statement\DeleteSettingStatement;
use GuldenWallet\Backend\Infrastructure\Settings\Statement\FetchSettingStatement;
use PDO;
use PDOException;

class PdoSettingsRepository implements SettingsRepositoryInterface
{
    /** @var PDO */
    private $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param string $key
     * @return void
     * @throws UnableToDeleteSettingException
     */
    public function delete(string $key)
    {
        try {
            $statement = Prepare::statement($this->pdo, new DeleteSettingStatement($key));

            $statement->execute();
        } catch (PDOException $exception) {
            throw UnableToDeleteSettingException::fromPrevious($exception);
        }
    }

    /**
     * @param string $key
     * @return mixed
     * @throws InvalidSettingValueException
     * @throws UnableToRetrieveSettingException
     */
    public function get(string $key)
    {
        try {
            $statement = Prepare::statement($this->pdo, new FetchSettingStatement($key));

            $statement->execute();

            $records = $statement->fetchAll();

            if (count($records) === 0) {
                throw SettingNotFoundException::forKey($key);
            }

            return $this->unserializeFromStorage($records[0]['SETTING_VALUE']);
        } catch (PDOException $exception) {
            throw UnableToRetrieveSettingException::fromPrevious($exception);
        }
    }

    /**
     * @param string $key
     * @param $value
     * @return void
     * @throws UnableToUpdateSettingException
     */
    public function set(string $key, $value)
    {
        try {
            $statement = Prepare::statement(
                $this->pdo,
                new CreateOrUpdateSettingStatement($key, $this->serializeForStorage($value))
            );

            $statement->execute();
        } catch (PDOException $exception) {
            throw UnableToUpdateSettingException::fromPrevious($exception);
        }
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function serializeForStorage($value): string
    {
        return json_encode(['value' => $value]);
    }

    /**
     * @param string $storedValue
     * @return mixed
     */
    private function unserializeFromStorage(string $storedValue)
    {
        $data = json_decode($storedValue, true);

        if (json_last_error() !== JSON_ERROR_NONE || !$data || !isset($data['value'])) {
            throw new InvalidSettingValueException($storedValue);
        }

        return $data['value'];
    }
}
