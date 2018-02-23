<?php
declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class UserDetailsSeed extends AbstractSeed
{
    /**
     * @return void
     */
    public function run()
    {
        $this->table('user_details')
            ->insert([
                'SETTING_KEY' => 'EMAIL',
                'SETTING_VALUE' => 'john@doe.com'
            ])
            ->insert([
                'SETTING_KEY' => 'PASSWORD_HASH',
                'SETTING_VALUE' => '$2y$04$xkz4iSnB72E3Uc.05jmRqOpsZ5A0kt5bhgA31wlHdzvT4XiVy5.fC'
            ])
            ->save();
    }
}
