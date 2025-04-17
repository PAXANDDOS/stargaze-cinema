<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220420091919 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ALTER roles TYPE TEXT');
        $this->addSql('ALTER TABLE users ALTER roles DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN users.roles IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users ALTER roles TYPE JSON');
        $this->addSql('ALTER TABLE users ALTER roles DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN users.roles IS NULL');
    }
}
