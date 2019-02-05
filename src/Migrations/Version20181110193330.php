<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181110193330 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE fun_fact_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE fun_fact (id INT NOT NULL, text TEXT NOT NULL, source TEXT DEFAULT NULL, source_image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE course_item ADD fact_id INT NOT NULL');
        $this->addSql('ALTER TABLE course_item DROP fact');
        $this->addSql('ALTER TABLE course_item ADD CONSTRAINT FK_D7B9F818156D5C2A FOREIGN KEY (fact_id) REFERENCES fun_fact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D7B9F818156D5C2A ON course_item (fact_id)');
        $this->addSql('ALTER TABLE task ALTER options TYPE TEXT');
        $this->addSql('ALTER TABLE task ALTER options DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE course_item DROP CONSTRAINT FK_D7B9F818156D5C2A');
        $this->addSql('DROP SEQUENCE fun_fact_id_seq CASCADE');
        $this->addSql('DROP TABLE fun_fact');
        $this->addSql('DROP INDEX IDX_D7B9F818156D5C2A');
        $this->addSql('ALTER TABLE course_item ADD fact VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE course_item DROP fact_id');
        $this->addSql('ALTER TABLE task ALTER options TYPE TEXT');
        $this->addSql('ALTER TABLE task ALTER options DROP DEFAULT');
    }
}
