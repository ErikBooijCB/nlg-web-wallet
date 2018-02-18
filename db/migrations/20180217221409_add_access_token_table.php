<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class AddAccessTokenTable extends AbstractMigration
{
    /**
     * @return void
     */
    public function up()
    {
        $this->execute('
            CREATE TABLE access_tokens (
                ACCESS_TOKEN CHAR(64) PRIMARY KEY,
                EXPIRATION DATETIME NOT NULL,
                REFRESH_TOKEN CHAR(64) NOT NULL
            );
        ');
    }

    /**
     * @return void
     */
    public function down()
    {
        $this->execute('
            DROP TABLE access_tokens;
        ');
    }
}
