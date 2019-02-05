<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181110151912 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE course_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE quiz_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE quiz_result_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE course_item (id INT NOT NULL, rule_id INT NOT NULL, exam_id INT NOT NULL, practice_id INT NOT NULL, fact VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D7B9F818744E0351 ON course_item (rule_id)');
        $this->addSql('CREATE INDEX IDX_D7B9F818578D5E91 ON course_item (exam_id)');
        $this->addSql('CREATE INDEX IDX_D7B9F818ED33821 ON course_item (practice_id)');
        $this->addSql('CREATE TABLE quiz (id INT NOT NULL, rule_id INT NOT NULL, type SMALLINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A412FA92744E0351 ON quiz (rule_id)');
        $this->addSql('CREATE TABLE quiz_task (quiz_id INT NOT NULL, task_id INT NOT NULL, PRIMARY KEY(quiz_id, task_id))');
        $this->addSql('CREATE INDEX IDX_988F277E853CD175 ON quiz_task (quiz_id)');
        $this->addSql('CREATE INDEX IDX_988F277E8DB60186 ON quiz_task (task_id)');
        $this->addSql('CREATE TABLE quiz_result (id INT NOT NULL, quiz_id INT NOT NULL, correct INT NOT NULL, total INT NOT NULL, avg DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FE2E314A853CD175 ON quiz_result (quiz_id)');
        $this->addSql('ALTER TABLE course_item ADD CONSTRAINT FK_D7B9F818744E0351 FOREIGN KEY (rule_id) REFERENCES rule (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE course_item ADD CONSTRAINT FK_D7B9F818578D5E91 FOREIGN KEY (exam_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE course_item ADD CONSTRAINT FK_D7B9F818ED33821 FOREIGN KEY (practice_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92744E0351 FOREIGN KEY (rule_id) REFERENCES rule (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_task ADD CONSTRAINT FK_988F277E853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_task ADD CONSTRAINT FK_988F277E8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_result ADD CONSTRAINT FK_FE2E314A853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE task ALTER options TYPE TEXT');
        $this->addSql('ALTER TABLE task ALTER options DROP DEFAULT');
        $this->addSql('SELECT setval(\'task_id_seq\', (SELECT MAX(id) FROM task))');
        $this->addSql('ALTER TABLE task ALTER id SET DEFAULT nextval(\'task_id_seq\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE course_item DROP CONSTRAINT FK_D7B9F818578D5E91');
        $this->addSql('ALTER TABLE course_item DROP CONSTRAINT FK_D7B9F818ED33821');
        $this->addSql('ALTER TABLE quiz_task DROP CONSTRAINT FK_988F277E853CD175');
        $this->addSql('ALTER TABLE quiz_result DROP CONSTRAINT FK_FE2E314A853CD175');
        $this->addSql('DROP SEQUENCE course_item_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quiz_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quiz_result_id_seq CASCADE');
        $this->addSql('DROP TABLE course_item');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE quiz_task');
        $this->addSql('DROP TABLE quiz_result');
        $this->addSql('ALTER TABLE task ALTER options TYPE TEXT');
        $this->addSql('ALTER TABLE task ALTER options DROP DEFAULT');
    }
}
