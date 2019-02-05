<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181109193139 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE rule_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE section_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE task_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE rule (id INT NOT NULL, section_id INT DEFAULT NULL, number SMALLINT NOT NULL, text TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_46D8ACCCD823E37A ON rule (section_id)');
        $this->addSql('CREATE TABLE section (id INT NOT NULL, title VARCHAR(255) NOT NULL, number SMALLINT DEFAULT NULL, part SMALLINT NOT NULL, subpart SMALLINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE task (id INT NOT NULL, rule_id INT NOT NULL, text TEXT NOT NULL, options TEXT NOT NULL, answer VARCHAR(255) NOT NULL, explanation TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_527EDB25744E0351 ON task (rule_id)');
        $this->addSql('COMMENT ON COLUMN task.options IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE rule ADD CONSTRAINT FK_46D8ACCCD823E37A FOREIGN KEY (section_id) REFERENCES section (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25744E0351 FOREIGN KEY (rule_id) REFERENCES rule (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25744E0351');
        $this->addSql('ALTER TABLE rule DROP CONSTRAINT FK_46D8ACCCD823E37A');
        $this->addSql('DROP SEQUENCE rule_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE section_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE task_id_seq CASCADE');
        $this->addSql('DROP TABLE rule');
        $this->addSql('DROP TABLE section');
        $this->addSql('DROP TABLE task');
    }
}
