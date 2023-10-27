<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231016144924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE joueur ADD matchee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C53E42D55A FOREIGN KEY (matchee_id) REFERENCES matchee (id)');
        $this->addSql('CREATE INDEX IDX_FD71A9C53E42D55A ON joueur (matchee_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE joueur DROP FOREIGN KEY FK_FD71A9C53E42D55A');
        $this->addSql('DROP INDEX IDX_FD71A9C53E42D55A ON joueur');
        $this->addSql('ALTER TABLE joueur DROP matchee_id');
    }
}
