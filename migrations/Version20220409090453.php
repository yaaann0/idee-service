<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220409090453 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE weeksheet_sheet_state');
        $this->addSql('DROP TABLE weeksheet_state');
        $this->addSql('ALTER TABLE weeksheet ADD state_id INT DEFAULT NULL, ADD validator_id INT DEFAULT NULL, ADD comment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE weeksheet ADD CONSTRAINT FK_1063EE495D83CC1 FOREIGN KEY (state_id) REFERENCES sheet_state (id)');
        $this->addSql('ALTER TABLE weeksheet ADD CONSTRAINT FK_1063EE49B0644AEC FOREIGN KEY (validator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1063EE495D83CC1 ON weeksheet (state_id)');
        $this->addSql('CREATE INDEX IDX_1063EE49B0644AEC ON weeksheet (validator_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE weeksheet_sheet_state (weeksheet_id INT NOT NULL, sheet_state_id INT NOT NULL, INDEX IDX_D0E28A6EB0848FE5 (sheet_state_id), INDEX IDX_D0E28A6ED4C9DEF (weeksheet_id), PRIMARY KEY(weeksheet_id, sheet_state_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE weeksheet_state (id INT AUTO_INCREMENT NOT NULL, weeksheet_id INT NOT NULL, state_id INT NOT NULL, user_id INT NOT NULL, comment VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_A3ADE7E0A76ED395 (user_id), UNIQUE INDEX UNIQ_A3ADE7E05D83CC1 (state_id), UNIQUE INDEX UNIQ_A3ADE7E0D4C9DEF (weeksheet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE weeksheet_sheet_state ADD CONSTRAINT FK_D0E28A6EB0848FE5 FOREIGN KEY (sheet_state_id) REFERENCES sheet_state (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE weeksheet_sheet_state ADD CONSTRAINT FK_D0E28A6ED4C9DEF FOREIGN KEY (weeksheet_id) REFERENCES weeksheet (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE weeksheet_state ADD CONSTRAINT FK_A3ADE7E05D83CC1 FOREIGN KEY (state_id) REFERENCES sheet_state (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE weeksheet_state ADD CONSTRAINT FK_A3ADE7E0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE weeksheet_state ADD CONSTRAINT FK_A3ADE7E0D4C9DEF FOREIGN KEY (weeksheet_id) REFERENCES weeksheet (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE weeksheet DROP FOREIGN KEY FK_1063EE495D83CC1');
        $this->addSql('ALTER TABLE weeksheet DROP FOREIGN KEY FK_1063EE49B0644AEC');
        $this->addSql('DROP INDEX IDX_1063EE495D83CC1 ON weeksheet');
        $this->addSql('DROP INDEX IDX_1063EE49B0644AEC ON weeksheet');
        $this->addSql('ALTER TABLE weeksheet DROP state_id, DROP validator_id, DROP comment');
    }
}
