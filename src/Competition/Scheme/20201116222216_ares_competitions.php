<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresCompetitions
 */
final class AresCompetitions extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_competitions');

        $data = [
            [
                'title' => 'Homepage Hunt #3', 
                'short_title' => 'The hunt is on!', 
                'iframe' => 'https://docs.google.com/forms/d/e/1FAIpQLSdVzzA5JY0easGmf4H5voMnxn8WN2A0HRPjr9CQ1Flc68oCIA/viewform', 
                'header' => 'https://www.hablush.com/images/news/news_homepagehunt.png',
                'timestamp', 1687043328,
                'created_at' => date('Y-m-d H:i:s')
            ],
        ];

        $table->addColumn('title', 'string', ['limit' => 100])
        ->addColumn('short_title', 'string', ['limit' => 100])
        ->addColumn('iframe', 'string', ['limit' => 255])
        ->addColumn('header', 'string', ['limit' => 255])
        ->addColumn('to_timestamp', 'integer', ['limit' => 11])
        ->addColumn('created_at', 'datetime')
        ->insert($data)
        ->create();
    }
}
