<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250515163854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE panier_produit (panier_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_D31F28A6F77D927C (panier_id), INDEX IDX_D31F28A6F347EFB (produit_id), PRIMARY KEY(panier_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A6F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier_article DROP FOREIGN KEY FK_F880CAE7F77D927C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier_article DROP FOREIGN KEY FK_F880CAE7F347EFB
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE panier_article
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier ADD quantite INT NOT NULL, ADD prix NUMERIC(10, 2) NOT NULL, ADD total NUMERIC(10, 2) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE panier_article (id INT AUTO_INCREMENT NOT NULL, panier_id INT NOT NULL, produit_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_F880CAE7F77D927C (panier_id), INDEX IDX_F880CAE7F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier_article ADD CONSTRAINT FK_F880CAE7F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier_article ADD CONSTRAINT FK_F880CAE7F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier_produit DROP FOREIGN KEY FK_D31F28A6F77D927C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier_produit DROP FOREIGN KEY FK_D31F28A6F347EFB
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE panier_produit
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier DROP quantite, DROP prix, DROP total
        SQL);
    }
}
