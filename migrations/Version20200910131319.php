<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200910131319 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message ADD user_target_id INT DEFAULT NULL, DROP user_target');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F156E8682 FOREIGN KEY (user_target_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F156E8682 ON message (user_target_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F156E8682');
        $this->addSql('DROP INDEX IDX_B6BD307F156E8682 ON message');
        $this->addSql('ALTER TABLE message ADD user_target INT NOT NULL, DROP user_target_id');
    }
}
