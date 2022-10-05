<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221004114122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire ADD article_id INT NOT NULL, ADD utilisateurs_id INT NOT NULL, CHANGE contenu contenu LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_67F068BC7294869C ON commentaire (article_id)');
        $this->addSql('CREATE INDEX IDX_67F068BC1E969C5 ON commentaire (utilisateurs_id)');
        $this->addSql('ALTER TABLE utilisateur CHANGE pseudo pseudo VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC7294869C');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC1E969C5');
        $this->addSql('DROP INDEX IDX_67F068BC7294869C ON commentaire');
        $this->addSql('DROP INDEX IDX_67F068BC1E969C5 ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP article_id, DROP utilisateurs_id, CHANGE contenu contenu VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE pseudo pseudo VARCHAR(255) NOT NULL');
    }
}
