<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240523213530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C67B3B43D');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CD5E258C5');
        $this->addSql('ALTER TABLE images_comment DROP FOREIGN KEY FK_B094C0E67B3B43D');
        $this->addSql('ALTER TABLE images_comment DROP FOREIGN KEY FK_B094C0EF8697D13');
        $this->addSql('ALTER TABLE images_post DROP FOREIGN KEY FK_60845D724B89032C');
        $this->addSql('ALTER TABLE post_like DROP FOREIGN KEY FK_653627B84B89032C');
        $this->addSql('ALTER TABLE post_like DROP FOREIGN KEY FK_653627B867B3B43D');
        $this->addSql('ALTER TABLE vue_posts DROP FOREIGN KEY FK_EB29A582D5E258C5');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE images_comment');
        $this->addSql('DROP TABLE images_post');
        $this->addSql('DROP TABLE post_like');
        $this->addSql('DROP TABLE vue_posts');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, posts_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', content LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, image LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_9474526C67B3B43D (users_id), INDEX IDX_9474526CD5E258C5 (posts_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE images_comment (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, comment_id INT NOT NULL, nom LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_B094C0E67B3B43D (users_id), INDEX IDX_B094C0EF8697D13 (comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE images_post (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, nom LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_60845D724B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE post_like (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_653627B867B3B43D (users_id), INDEX IDX_653627B84B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE vue_posts (id INT AUTO_INCREMENT NOT NULL, posts_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_EB29A582D5E258C5 (posts_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CD5E258C5 FOREIGN KEY (posts_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE images_comment ADD CONSTRAINT FK_B094C0E67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE images_comment ADD CONSTRAINT FK_B094C0EF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE images_post ADD CONSTRAINT FK_60845D724B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_like ADD CONSTRAINT FK_653627B84B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_like ADD CONSTRAINT FK_653627B867B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vue_posts ADD CONSTRAINT FK_EB29A582D5E258C5 FOREIGN KEY (posts_id) REFERENCES post (id)');
    }
}
