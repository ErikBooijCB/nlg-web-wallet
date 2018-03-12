<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Controller;

use GuldenWallet\Backend\Application\Helper\ResponseFactory;
use GuldenWallet\Backend\Application\Settings\InvalidSettingValueException;
use GuldenWallet\Backend\Application\Settings\SettingNotFoundException;
use GuldenWallet\Backend\Application\Settings\SettingsRepositoryInterface;
use GuldenWallet\Backend\Application\Settings\UnableToRetrieveSettingException;
use GuldenWallet\Backend\Application\Settings\UnableToUpdateSettingException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SettingsHttpController
{
    /** @var SettingsRepositoryInterface */
    private $settingsRepository;

    /**
     * @param SettingsRepositoryInterface $settingsRepository
     */
    public function __construct(SettingsRepositoryInterface $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        $settingKey = $request->getAttribute('setting', '');

        if ($settingKey === '') {
            return ResponseFactory::failure('No setting provided', 400);
        }

        try {
            $this->settingsRepository->delete($settingKey);

            return ResponseFactory::successMessage('Setting was successfully deleted', 202);
        } catch (UnableToUpdateSettingException $exception) {
            return ResponseFactory::failure('Unable to persist setting', 500);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function get(ServerRequestInterface $request): ResponseInterface
    {
        $settingKey = $request->getAttribute('setting', '');

        if ($settingKey === '') {
            return ResponseFactory::failure('No setting provided', 400);
        }

        try {
            $settingValue = $this->settingsRepository->get($settingKey);

            return ResponseFactory::success([
                'key' => $settingKey,
                'value' => $settingValue,
            ], 200);
        } catch (SettingNotFoundException $exception) {
            return ResponseFactory::failure('Setting does not exist', 404);
        } catch (UnableToRetrieveSettingException $exception) {
            return ResponseFactory::failure('Unable to retrieve setting', 500);
        } catch (InvalidSettingValueException $exception) {
            return ResponseFactory::failure('Unable to retrieve setting', 500);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function set(ServerRequestInterface $request): ResponseInterface
    {
        $settingKey = $request->getAttribute('setting', '');

        if ($settingKey === '') {
            return ResponseFactory::failure('No setting provided', 400);
        }

        $requestBody = (array)$request->getParsedBody();

        if (!isset($requestBody['value'])) {
            return ResponseFactory::failure('No value provided', 400);
        }

        try {
            $this->settingsRepository->set($settingKey, $requestBody['value']);

            return ResponseFactory::successMessage('Setting was successfully saved', 201);
        } catch (UnableToUpdateSettingException $exception) {
            return ResponseFactory::failure('Unable to persist setting', 500);
        }
    }
}
