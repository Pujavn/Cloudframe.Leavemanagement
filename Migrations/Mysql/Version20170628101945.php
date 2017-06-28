<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20170628101945 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		// this up() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("CREATE TABLE cloudframe_leavemanagement_domain_model_employee (persistence_object_identifier VARCHAR(40) NOT NULL, gender TINYINT(1) NOT NULL, address VARCHAR(255) NOT NULL, contactnumber VARCHAR(255) NOT NULL, createdate DATETIME NOT NULL, token VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE cloudframe_leavemanagement_domain_model_leave (persistence_object_identifier VARCHAR(40) NOT NULL, teamleader VARCHAR(40) DEFAULT NULL, employee VARCHAR(40) DEFAULT NULL, reason VARCHAR(255) NOT NULL, createdate DATETIME NOT NULL, fromdate DATETIME NOT NULL, todate DATETIME NOT NULL, hasteamleaderapproved TINYINT(1) NOT NULL, hasdirectorapproved TINYINT(1) NOT NULL, INDEX IDX_FA07957DB8DD4F0B (teamleader), INDEX IDX_FA07957D5D9F75A1 (employee), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("ALTER TABLE cloudframe_leavemanagement_domain_model_leave ADD CONSTRAINT FK_FA07957DB8DD4F0B FOREIGN KEY (teamleader) REFERENCES cloudframe_leavemanagement_domain_model_employee (persistence_object_identifier)");
		$this->addSql("ALTER TABLE cloudframe_leavemanagement_domain_model_leave ADD CONSTRAINT FK_FA07957D5D9F75A1 FOREIGN KEY (employee) REFERENCES cloudframe_leavemanagement_domain_model_employee (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		// this down() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("ALTER TABLE cloudframe_leavemanagement_domain_model_leave DROP FOREIGN KEY FK_FA07957DB8DD4F0B");
		$this->addSql("ALTER TABLE cloudframe_leavemanagement_domain_model_leave DROP FOREIGN KEY FK_FA07957D5D9F75A1");
		$this->addSql("DROP TABLE cloudframe_leavemanagement_domain_model_employee");
		$this->addSql("DROP TABLE cloudframe_leavemanagement_domain_model_leave");
	}
}