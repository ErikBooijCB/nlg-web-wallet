<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Database;

use PDO;
use PDOStatement;

class Prepare
{
    /**
     * @param PDO $PDO
     * @param PreparableStatement $statement
     *
     * @return PDOStatement
     */
    public static function statement(PDO $PDO, PreparableStatement $statement): PDOStatement
    {
        $pdoStatement = $PDO->prepare($statement->getStatement());

        foreach ($statement->getParameters() as $parameter => $value) {
            $type = PDO::PARAM_STR;

            if (is_array($value)) {
                list($value, $type) = $value;
            }

            $pdoStatement->bindValue($parameter, $value, $type ?? PDO::PARAM_STR);
        }

        return $pdoStatement;
    }
}
