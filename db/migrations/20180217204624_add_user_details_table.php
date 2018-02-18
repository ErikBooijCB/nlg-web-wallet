<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class AddUserDetailsTable extends AbstractMigration
{
    /**
     * @return void
     */
    public function up()
    {
        $this->execute('
            CREATE TABLE user_details (
              SETTING_KEY VARCHAR(64) PRIMARY KEY,
              SETTING_VALUE VARCHAR(255)
            );
        ');
    }

    /**
     * @return void
     */
    public function down()
    {
        $this->execute('
            DROP TABLE user_details;
        ');
    }
}
