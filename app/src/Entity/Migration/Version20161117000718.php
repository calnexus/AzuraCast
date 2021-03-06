<?php

namespace Entity\Migration;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20161117000718 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE station_mounts (id INT AUTO_INCREMENT NOT NULL, station_id INT NOT NULL, name VARCHAR(100) NOT NULL, is_default TINYINT(1) NOT NULL, fallback_mount VARCHAR(100) DEFAULT NULL, enable_streamers TINYINT(1) NOT NULL, enable_autodj TINYINT(1) NOT NULL, autodj_format VARCHAR(10) DEFAULT NULL, autodj_bitrate SMALLINT DEFAULT NULL, INDEX IDX_4DDF64AD21BDB235 (station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE station_mounts ADD CONSTRAINT FK_4DDF64AD21BDB235 FOREIGN KEY (station_id) REFERENCES station (id) ON DELETE CASCADE');
    }

    public function postUp(Schema $schema)
    {
        $all_stations = $this->connection->fetchAll('SELECT * FROM station');

        foreach ($all_stations as $station) {
            $this->connection->insert('station_mounts', [
                'station_id' => $station['id'],
                'name' => '/radio.mp3',
                'is_default' => 1,
                'fallback_mount' => '/autodj.mp3',
                'enable_streamers' => 1,
                'enable_autodj' => 0,
            ]);

            $this->connection->insert('station_mounts', [
                'station_id' => $station['id'],
                'name' => '/autodj.mp3',
                'is_default' => 0,
                'fallback_mount' => '/error.mp3',
                'enable_streamers' => 0,
                'enable_autodj' => 1,
                'autodj_format' => 'mp3',
                'autodj_bitrate' => 128,
            ]);
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE station_mounts');
    }
}
