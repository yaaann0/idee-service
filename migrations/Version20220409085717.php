<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220409085717 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE weeksheet_sheet_state (weeksheet_id INT NOT NULL, sheet_state_id INT NOT NULL, INDEX IDX_D0E28A6ED4C9DEF (weeksheet_id), INDEX IDX_D0E28A6EB0848FE5 (sheet_state_id), PRIMARY KEY(weeksheet_id, sheet_state_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE weeksheet_sheet_state ADD CONSTRAINT FK_D0E28A6ED4C9DEF FOREIGN KEY (weeksheet_id) REFERENCES weeksheet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE weeksheet_sheet_state ADD CONSTRAINT FK_D0E28A6EB0848FE5 FOREIGN KEY (sheet_state_id) REFERENCES sheet_state (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE weeksheet_sheet_state');
    }
}
