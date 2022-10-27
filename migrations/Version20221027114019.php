<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221027114019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE criteria (id INT AUTO_INCREMENT NOT NULL, size INT DEFAULT NULL, silhouete VARCHAR(255) DEFAULT NULL, alcohol VARCHAR(255) DEFAULT NULL, tobacco VARCHAR(255) DEFAULT NULL, eyes VARCHAR(255) DEFAULT NULL, hair VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE criteria_particular_sign (criteria_id INT NOT NULL, particular_sign_id INT NOT NULL, INDEX IDX_125DD06F990BEA15 (criteria_id), INDEX IDX_125DD06FEA51C03 (particular_sign_id), PRIMARY KEY(criteria_id, particular_sign_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE criteria_origin (criteria_id INT NOT NULL, origin_id INT NOT NULL, INDEX IDX_A4ABA74E990BEA15 (criteria_id), INDEX IDX_A4ABA74E56A273CC (origin_id), PRIMARY KEY(criteria_id, origin_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE criteria_style (criteria_id INT NOT NULL, style_id INT NOT NULL, INDEX IDX_31640E1990BEA15 (criteria_id), INDEX IDX_31640E1BACD6074 (style_id), PRIMARY KEY(criteria_id, style_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE criteria_hobbies (criteria_id INT NOT NULL, hobbies_id INT NOT NULL, INDEX IDX_53DB3F18990BEA15 (criteria_id), INDEX IDX_53DB3F18B2242D72 (hobbies_id), PRIMARY KEY(criteria_id, hobbies_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hobbies (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE origin (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE particular_sign (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE style (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE criteria_particular_sign ADD CONSTRAINT FK_125DD06F990BEA15 FOREIGN KEY (criteria_id) REFERENCES criteria (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE criteria_particular_sign ADD CONSTRAINT FK_125DD06FEA51C03 FOREIGN KEY (particular_sign_id) REFERENCES particular_sign (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE criteria_origin ADD CONSTRAINT FK_A4ABA74E990BEA15 FOREIGN KEY (criteria_id) REFERENCES criteria (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE criteria_origin ADD CONSTRAINT FK_A4ABA74E56A273CC FOREIGN KEY (origin_id) REFERENCES origin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE criteria_style ADD CONSTRAINT FK_31640E1990BEA15 FOREIGN KEY (criteria_id) REFERENCES criteria (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE criteria_style ADD CONSTRAINT FK_31640E1BACD6074 FOREIGN KEY (style_id) REFERENCES style (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE criteria_hobbies ADD CONSTRAINT FK_53DB3F18990BEA15 FOREIGN KEY (criteria_id) REFERENCES criteria (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE criteria_hobbies ADD CONSTRAINT FK_53DB3F18B2242D72 FOREIGN KEY (hobbies_id) REFERENCES hobbies (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messenger_messages CHANGE queue_name queue_name VARCHAR(190) NOT NULL');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE criteria_particular_sign DROP FOREIGN KEY FK_125DD06F990BEA15');
        $this->addSql('ALTER TABLE criteria_particular_sign DROP FOREIGN KEY FK_125DD06FEA51C03');
        $this->addSql('ALTER TABLE criteria_origin DROP FOREIGN KEY FK_A4ABA74E990BEA15');
        $this->addSql('ALTER TABLE criteria_origin DROP FOREIGN KEY FK_A4ABA74E56A273CC');
        $this->addSql('ALTER TABLE criteria_style DROP FOREIGN KEY FK_31640E1990BEA15');
        $this->addSql('ALTER TABLE criteria_style DROP FOREIGN KEY FK_31640E1BACD6074');
        $this->addSql('ALTER TABLE criteria_hobbies DROP FOREIGN KEY FK_53DB3F18990BEA15');
        $this->addSql('ALTER TABLE criteria_hobbies DROP FOREIGN KEY FK_53DB3F18B2242D72');
        $this->addSql('DROP TABLE criteria');
        $this->addSql('DROP TABLE criteria_particular_sign');
        $this->addSql('DROP TABLE criteria_origin');
        $this->addSql('DROP TABLE criteria_style');
        $this->addSql('DROP TABLE criteria_hobbies');
        $this->addSql('DROP TABLE hobbies');
        $this->addSql('DROP TABLE origin');
        $this->addSql('DROP TABLE particular_sign');
        $this->addSql('DROP TABLE style');
        $this->addSql('DROP INDEX IDX_75EA56E0FB7336F0 ON messenger_messages');
        $this->addSql('DROP INDEX IDX_75EA56E0E3BD61CE ON messenger_messages');
        $this->addSql('ALTER TABLE messenger_messages CHANGE queue_name queue_name VARCHAR(255) NOT NULL');
    }
}
