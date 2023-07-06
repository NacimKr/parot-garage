<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230705113214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hours ADD CONSTRAINT FK_8A1ABD8D3575FA99 FOREIGN KEY (days_id) REFERENCES week (id)');
        $this->addSql('ALTER TABLE services DROP FOREIGN KEY FK_7332E169139DF194');
        $this->addSql('DROP INDEX IDX_7332E169139DF194 ON services');
        $this->addSql('ALTER TABLE services DROP promotion_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE services ADD promotion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E169139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_7332E169139DF194 ON services (promotion_id)');
        $this->addSql('ALTER TABLE hours DROP FOREIGN KEY FK_8A1ABD8D3575FA99');
    }
}
