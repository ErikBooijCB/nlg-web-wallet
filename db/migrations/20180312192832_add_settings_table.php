<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class AddSettingsTable extends AbstractMigration
{
    /**
     * @return void
     */
    public function up()
    {
        $this->execute('
            CREATE TABLE settings (
              SETTING_KEY VARCHAR(50) NOT NULL PRIMARY KEY,
              SETTING_VALUE VARCHAR(255) NOT NULL,
              KEY idx_setting_key (SETTING_KEY),
              KEY idx_setting_value (SETTING_VALUE)
            );
        ');
    }

    /**
     * @return void
     */
    public function down()
    {
        $this->execute('
            DROP TABLE settings;
        ');
    }
}
