<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Initial tables for Planetflow3
 */
class Version20110924172235 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("CREATE TABLE planetflow3_domain_model_category (name VARCHAR(255) NOT NULL, PRIMARY KEY(name)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE planetflow3_domain_model_channel (flow3_persistence_identifier VARCHAR(40) NOT NULL, planetflow3_category VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, feedurl VARCHAR(255) DEFAULT NULL, fetchedcategories LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', filter VARCHAR(255) DEFAULT NULL, INDEX IDX_7E483DBE9EFA169E (planetflow3_category), PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE planetflow3_domain_model_item (universalidentifier VARCHAR(255) NOT NULL, planetflow3_channel VARCHAR(40) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, description LONGTEXT NOT NULL, content LONGTEXT NOT NULL, author VARCHAR(255) DEFAULT NULL, publicationdate DATETIME DEFAULT NULL, language VARCHAR(255) DEFAULT NULL, INDEX IDX_65956703C6D66D05 (planetflow3_channel), PRIMARY KEY(universalidentifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE planetflow3_domain_model_item_categories_join (planetflow3_item VARCHAR(255) NOT NULL, planetflow3_category VARCHAR(255) NOT NULL, INDEX IDX_FC04D186F831BBD (planetflow3_item), INDEX IDX_FC04D189EFA169E (planetflow3_category), PRIMARY KEY(planetflow3_item, planetflow3_category)) ENGINE = InnoDB");
		$this->addSql("ALTER TABLE planetflow3_domain_model_channel ADD FOREIGN KEY (planetflow3_category) REFERENCES planetflow3_domain_model_category(name)");
		$this->addSql("ALTER TABLE planetflow3_domain_model_item ADD FOREIGN KEY (planetflow3_channel) REFERENCES planetflow3_domain_model_channel(flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE planetflow3_domain_model_item_categories_join ADD FOREIGN KEY (planetflow3_item) REFERENCES planetflow3_domain_model_item(universalidentifier)");
		$this->addSql("ALTER TABLE planetflow3_domain_model_item_categories_join ADD FOREIGN KEY (planetflow3_category) REFERENCES planetflow3_domain_model_category(name)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("ALTER TABLE planetflow3_domain_model_channel DROP FOREIGN KEY ");
		$this->addSql("ALTER TABLE planetflow3_domain_model_item_categories_join DROP FOREIGN KEY ");
		$this->addSql("ALTER TABLE planetflow3_domain_model_item DROP FOREIGN KEY ");
		$this->addSql("ALTER TABLE planetflow3_domain_model_item_categories_join DROP FOREIGN KEY ");
		$this->addSql("DROP TABLE planetflow3_domain_model_category");
		$this->addSql("DROP TABLE planetflow3_domain_model_channel");
		$this->addSql("DROP TABLE planetflow3_domain_model_item");
		$this->addSql("DROP TABLE planetflow3_domain_model_item_categories_join");
	}
}

?>