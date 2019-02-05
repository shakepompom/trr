<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181110160202 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE quiz ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A412FA92A76ED395 ON quiz (user_id)');
        $this->addSql('ALTER TABLE course_item ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE course_item ADD CONSTRAINT FK_D7B9F818A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D7B9F818A76ED395 ON course_item (user_id)');
        $this->addSql('ALTER TABLE task ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE task ALTER options TYPE TEXT');
        $this->addSql('ALTER TABLE task ALTER options DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE course_item DROP CONSTRAINT FK_D7B9F818A76ED395');
        $this->addSql('DROP INDEX IDX_D7B9F818A76ED395');
        $this->addSql('ALTER TABLE course_item DROP user_id');
        $this->addSql('ALTER TABLE task ALTER options TYPE TEXT');
        $this->addSql('ALTER TABLE task ALTER options DROP DEFAULT');
        $this->addSql('ALTER TABLE quiz DROP CONSTRAINT FK_A412FA92A76ED395');
        $this->addSql('DROP INDEX IDX_A412FA92A76ED395');
        $this->addSql('ALTER TABLE quiz DROP user_id');
    }
}
