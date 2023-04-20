<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230418103949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create base structure';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL UNIQUE, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "group" (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL UNIQUE, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT group_name_unique UNIQUE (name)');
        $this->addSql('CREATE TABLE user_group (user_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(user_id, group_id))');
        $this->addSql('CREATE INDEX idx_user_group_user_id ON user_group (user_id)');
        $this->addSql('CREATE INDEX idx_user_group_group_id ON user_group (group_id)');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT fk_user_group_user_id FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT fk_user_group_group_id FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE "group"');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE "user_group"');
    }
}
