<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250515181214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, quantit INT NOT NULL, total NUMERIC(10, 2) NOT NULL, INDEX IDX_6EEAA67DFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE commande_produit (commande_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_DF1E9E8782EA2E54 (commande_id), INDEX IDX_DF1E9E87F347EFB (produit_id), PRIMARY KEY(commande_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, telephone VARCHAR(20) NOT NULL, email VARCHAR(150) NOT NULL, mots_de_passe VARCHAR(100) NOT NULL, role VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E8782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E87F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DFB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande_produit DROP FOREIGN KEY FK_DF1E9E8782EA2E54
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande_produit DROP FOREIGN KEY FK_DF1E9E87F347EFB
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE commande
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE commande_produit
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE utilisateur
        SQL);
    }
}
