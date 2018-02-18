<?php
declare(strict_types=1);

class SecretHelper
{
    /** @var array[] */
    private static $values = [];

    /**
     * @param string $key
     *
     * @return mixed
     */
    public static function read(string $key)
    {
        self::loadSecrets();

        $value = self::$values;

        foreach (explode('.', $key) as $nextPath) {
            $value = $value[$nextPath] ?? null;
        }

        return $value;
    }

    /**
     * @return void
     */
    private static function loadSecrets()
    {
        if (empty(self::$values)) {
            self::$values = include __DIR__ . '/secrets.php';
        }
    }
}
