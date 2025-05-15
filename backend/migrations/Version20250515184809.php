<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250515184809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DFB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE utilisateur
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6EEAA67DFB88E14F ON commande
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande ADD nom VARCHAR(100) NOT NULL, ADD telephone VARCHAR(20) NOT NULL, ADD email VARCHAR(155) NOT NULL, DROP utilisateur_id, CHANGE quantit quantite INT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, telephone VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(150) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, mots_de_passe VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, role VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande ADD utilisateur_id INT DEFAULT NULL, DROP nom, DROP telephone, DROP email, CHANGE quantite quantit INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6EEAA67DFB88E14F ON commande (utilisateur_id)
        SQL);
    }
}
