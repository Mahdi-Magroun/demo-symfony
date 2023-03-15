<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230314111908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE citizent (id SERIAL NOT NULL, code VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(50) NOT NULL, father_name VARCHAR(50) NOT NULL, garnd_father_name VARCHAR(50) NOT NULL, identity_card BIGINT DEFAULT NULL, passport VARCHAR(255) DEFAULT NULL, residence_card VARCHAR(255) DEFAULT NULL, phone_number BIGINT NOT NULL, email VARCHAR(255) DEFAULT NULL, id_type VARCHAR(50) NOT NULL, zip_code INT NOT NULL, street VARCHAR(255) NOT NULL, building_number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE citizent_municipality (citizent_id INT NOT NULL, municipality_id INT NOT NULL, PRIMARY KEY(citizent_id, municipality_id))');
        $this->addSql('CREATE INDEX IDX_DA312CB1A6D6B46F ON citizent_municipality (citizent_id)');
        $this->addSql('CREATE INDEX IDX_DA312CB1AE6F181C ON citizent_municipality (municipality_id)');
        $this->addSql('CREATE TABLE debt (id SERIAL NOT NULL, property_id INT NOT NULL, taxe_id INT NOT NULL, citizent_id INT NOT NULL, municipality_id INT NOT NULL, updator_id INT DEFAULT NULL, creator_id INT NOT NULL, main_amount DOUBLE PRECISION NOT NULL, followinf_penalty_amount DOUBLE PRECISION NOT NULL, latency_penalty_amount DOUBLE PRECISION NOT NULL, total_amount DOUBLE PRECISION NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DBBF0A83549213EC ON debt (property_id)');
        $this->addSql('CREATE INDEX IDX_DBBF0A831AB947A4 ON debt (taxe_id)');
        $this->addSql('CREATE INDEX IDX_DBBF0A83A6D6B46F ON debt (citizent_id)');
        $this->addSql('CREATE INDEX IDX_DBBF0A83AE6F181C ON debt (municipality_id)');
        $this->addSql('CREATE INDEX IDX_DBBF0A83A9CED711 ON debt (updator_id)');
        $this->addSql('CREATE INDEX IDX_DBBF0A8361220EA6 ON debt (creator_id)');
        $this->addSql('CREATE TABLE gouvernorate (id SERIAL NOT NULL, code VARCHAR(255) NOT NULL, national_id INT NOT NULL, frensh_name VARCHAR(100) NOT NULL, arabic_name VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE municipality (id SERIAL NOT NULL, governorate_id INT NOT NULL, creator_id INT DEFAULT NULL, updator_id INT NOT NULL, code VARCHAR(255) NOT NULL, frensh_name VARCHAR(100) NOT NULL, arabic_name VARCHAR(100) NOT NULL, phone_number BIGINT DEFAULT NULL, web_site VARCHAR(255) DEFAULT NULL, national_id INT NOT NULL, population_count BIGINT DEFAULT NULL, year_population_count INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_activated BOOLEAN NOT NULL, zip_code INT NOT NULL, street VARCHAR(255) NOT NULL, building_number INT NOT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C6F56628B5FFB04E ON municipality (governorate_id)');
        $this->addSql('CREATE INDEX IDX_C6F5662861220EA6 ON municipality (creator_id)');
        $this->addSql('CREATE INDEX IDX_C6F56628A9CED711 ON municipality (updator_id)');
        $this->addSql('COMMENT ON COLUMN municipality.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN municipality.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE municipality_agent (id SERIAL NOT NULL, municipality_id INT NOT NULL, code VARCHAR(255) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, email VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, roles VARCHAR(100) NOT NULL, cin BIGINT NOT NULL, role VARCHAR(50) DEFAULT NULL, is_activated BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75A479D0AE6F181C ON municipality_agent (municipality_id)');
        $this->addSql('CREATE TABLE municipality_taxe_search_criteria (id SERIAL NOT NULL, creator_id INT NOT NULL, updator_id INT NOT NULL, code VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, is_activated BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, value BIGINT NOT NULL, date_begin DATE NOT NULL, date_end DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3EDD3D2861220EA6 ON municipality_taxe_search_criteria (creator_id)');
        $this->addSql('CREATE INDEX IDX_3EDD3D28A9CED711 ON municipality_taxe_search_criteria (updator_id)');
        $this->addSql('COMMENT ON COLUMN municipality_taxe_search_criteria.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN municipality_taxe_search_criteria.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN municipality_taxe_search_criteria.date_begin IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN municipality_taxe_search_criteria.date_end IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE new_api_user (id SERIAL NOT NULL, code VARCHAR(225) NOT NULL, role VARCHAR(225) DEFAULT NULL, mail VARCHAR(225) NOT NULL, username VARCHAR(225) NOT NULL, password VARCHAR(255) NOT NULL, roles VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE notice (id SERIAL NOT NULL, debt_id INT NOT NULL, creator_id INT NOT NULL, updator_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, sended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, recived_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, transfer_type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_480D45C2240326A5 ON notice (debt_id)');
        $this->addSql('CREATE INDEX IDX_480D45C261220EA6 ON notice (creator_id)');
        $this->addSql('CREATE INDEX IDX_480D45C2A9CED711 ON notice (updator_id)');
        $this->addSql('COMMENT ON COLUMN notice.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN notice.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN notice.sended_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN notice.recived_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE property (id SERIAL NOT NULL, owner_id INT NOT NULL, municipality_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, zip_code INT NOT NULL, street VARCHAR(255) NOT NULL, building_number INT NOT NULL, surface DOUBLE PRECISION DEFAULT NULL, reference BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8BF21CDE7E3C61F9 ON property (owner_id)');
        $this->addSql('CREATE INDEX IDX_8BF21CDEAE6F181C ON property (municipality_id)');
        $this->addSql('CREATE TABLE property_year_debt (id SERIAL NOT NULL, year INT NOT NULL, amount DOUBLE PRECISION NOT NULL, is_payed BOOLEAN NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE taxe (id SERIAL NOT NULL, creator_id INT NOT NULL, updator_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, abbreviation VARCHAR(50) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_activated BOOLEAN NOT NULL, date_begin DATE NOT NULL, date_end DATE DEFAULT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_56322FE961220EA6 ON taxe (creator_id)');
        $this->addSql('CREATE INDEX IDX_56322FE9A9CED711 ON taxe (updator_id)');
        $this->addSql('COMMENT ON COLUMN taxe.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN taxe.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN taxe.date_begin IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN taxe.date_end IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE taxe_search_criteria (id SERIAL NOT NULL, taxe_id INT NOT NULL, creator_id INT NOT NULL, updator_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, value BIGINT NOT NULL, is_activated BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, date_begin DATE NOT NULL, date_end DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5CCE2CC31AB947A4 ON taxe_search_criteria (taxe_id)');
        $this->addSql('CREATE INDEX IDX_5CCE2CC361220EA6 ON taxe_search_criteria (creator_id)');
        $this->addSql('CREATE INDEX IDX_5CCE2CC3A9CED711 ON taxe_search_criteria (updator_id)');
        $this->addSql('COMMENT ON COLUMN taxe_search_criteria.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN taxe_search_criteria.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN taxe_search_criteria.date_begin IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN taxe_search_criteria.date_end IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE team (id SERIAL NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, email VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, roles VARCHAR(255) NOT NULL, role VARCHAR(50) DEFAULT NULL, is_activated BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE token_api_user (id SERIAL NOT NULL, name VARCHAR(50) NOT NULL, token VARCHAR(180) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D83B7D395F37A13B ON token_api_user (token)');
        $this->addSql('CREATE TABLE wording (id SERIAL NOT NULL, domain_id INT DEFAULT NULL, code VARCHAR(100) NOT NULL, label VARCHAR(100) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_15F91DD2115F0EE5 ON wording (domain_id)');
        $this->addSql('CREATE TABLE wording_domain (id SERIAL NOT NULL, code VARCHAR(100) NOT NULL, label VARCHAR(100) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE wording_translation (id SERIAL NOT NULL, wording_id INT DEFAULT NULL, content VARCHAR(200) NOT NULL, language VARCHAR(2) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_353F7543D34102DF ON wording_translation (wording_id)');
        $this->addSql('ALTER TABLE citizent_municipality ADD CONSTRAINT FK_DA312CB1A6D6B46F FOREIGN KEY (citizent_id) REFERENCES citizent (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE citizent_municipality ADD CONSTRAINT FK_DA312CB1AE6F181C FOREIGN KEY (municipality_id) REFERENCES municipality (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE debt ADD CONSTRAINT FK_DBBF0A83549213EC FOREIGN KEY (property_id) REFERENCES property (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE debt ADD CONSTRAINT FK_DBBF0A831AB947A4 FOREIGN KEY (taxe_id) REFERENCES taxe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE debt ADD CONSTRAINT FK_DBBF0A83A6D6B46F FOREIGN KEY (citizent_id) REFERENCES citizent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE debt ADD CONSTRAINT FK_DBBF0A83AE6F181C FOREIGN KEY (municipality_id) REFERENCES municipality (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE debt ADD CONSTRAINT FK_DBBF0A83A9CED711 FOREIGN KEY (updator_id) REFERENCES municipality_agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE debt ADD CONSTRAINT FK_DBBF0A8361220EA6 FOREIGN KEY (creator_id) REFERENCES municipality_agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE municipality ADD CONSTRAINT FK_C6F56628B5FFB04E FOREIGN KEY (governorate_id) REFERENCES gouvernorate (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE municipality ADD CONSTRAINT FK_C6F5662861220EA6 FOREIGN KEY (creator_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE municipality ADD CONSTRAINT FK_C6F56628A9CED711 FOREIGN KEY (updator_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE municipality_agent ADD CONSTRAINT FK_75A479D0AE6F181C FOREIGN KEY (municipality_id) REFERENCES municipality (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE municipality_taxe_search_criteria ADD CONSTRAINT FK_3EDD3D2861220EA6 FOREIGN KEY (creator_id) REFERENCES municipality_agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE municipality_taxe_search_criteria ADD CONSTRAINT FK_3EDD3D28A9CED711 FOREIGN KEY (updator_id) REFERENCES municipality_agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C2240326A5 FOREIGN KEY (debt_id) REFERENCES debt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C261220EA6 FOREIGN KEY (creator_id) REFERENCES municipality_agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C2A9CED711 FOREIGN KEY (updator_id) REFERENCES municipality_agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES citizent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDEAE6F181C FOREIGN KEY (municipality_id) REFERENCES municipality (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE taxe ADD CONSTRAINT FK_56322FE961220EA6 FOREIGN KEY (creator_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE taxe ADD CONSTRAINT FK_56322FE9A9CED711 FOREIGN KEY (updator_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE taxe_search_criteria ADD CONSTRAINT FK_5CCE2CC31AB947A4 FOREIGN KEY (taxe_id) REFERENCES taxe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE taxe_search_criteria ADD CONSTRAINT FK_5CCE2CC361220EA6 FOREIGN KEY (creator_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE taxe_search_criteria ADD CONSTRAINT FK_5CCE2CC3A9CED711 FOREIGN KEY (updator_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wording ADD CONSTRAINT FK_15F91DD2115F0EE5 FOREIGN KEY (domain_id) REFERENCES wording_domain (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wording_translation ADD CONSTRAINT FK_353F7543D34102DF FOREIGN KEY (wording_id) REFERENCES wording (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE citizent_municipality DROP CONSTRAINT FK_DA312CB1A6D6B46F');
        $this->addSql('ALTER TABLE citizent_municipality DROP CONSTRAINT FK_DA312CB1AE6F181C');
        $this->addSql('ALTER TABLE debt DROP CONSTRAINT FK_DBBF0A83549213EC');
        $this->addSql('ALTER TABLE debt DROP CONSTRAINT FK_DBBF0A831AB947A4');
        $this->addSql('ALTER TABLE debt DROP CONSTRAINT FK_DBBF0A83A6D6B46F');
        $this->addSql('ALTER TABLE debt DROP CONSTRAINT FK_DBBF0A83AE6F181C');
        $this->addSql('ALTER TABLE debt DROP CONSTRAINT FK_DBBF0A83A9CED711');
        $this->addSql('ALTER TABLE debt DROP CONSTRAINT FK_DBBF0A8361220EA6');
        $this->addSql('ALTER TABLE municipality DROP CONSTRAINT FK_C6F56628B5FFB04E');
        $this->addSql('ALTER TABLE municipality DROP CONSTRAINT FK_C6F5662861220EA6');
        $this->addSql('ALTER TABLE municipality DROP CONSTRAINT FK_C6F56628A9CED711');
        $this->addSql('ALTER TABLE municipality_agent DROP CONSTRAINT FK_75A479D0AE6F181C');
        $this->addSql('ALTER TABLE municipality_taxe_search_criteria DROP CONSTRAINT FK_3EDD3D2861220EA6');
        $this->addSql('ALTER TABLE municipality_taxe_search_criteria DROP CONSTRAINT FK_3EDD3D28A9CED711');
        $this->addSql('ALTER TABLE notice DROP CONSTRAINT FK_480D45C2240326A5');
        $this->addSql('ALTER TABLE notice DROP CONSTRAINT FK_480D45C261220EA6');
        $this->addSql('ALTER TABLE notice DROP CONSTRAINT FK_480D45C2A9CED711');
        $this->addSql('ALTER TABLE property DROP CONSTRAINT FK_8BF21CDE7E3C61F9');
        $this->addSql('ALTER TABLE property DROP CONSTRAINT FK_8BF21CDEAE6F181C');
        $this->addSql('ALTER TABLE taxe DROP CONSTRAINT FK_56322FE961220EA6');
        $this->addSql('ALTER TABLE taxe DROP CONSTRAINT FK_56322FE9A9CED711');
        $this->addSql('ALTER TABLE taxe_search_criteria DROP CONSTRAINT FK_5CCE2CC31AB947A4');
        $this->addSql('ALTER TABLE taxe_search_criteria DROP CONSTRAINT FK_5CCE2CC361220EA6');
        $this->addSql('ALTER TABLE taxe_search_criteria DROP CONSTRAINT FK_5CCE2CC3A9CED711');
        $this->addSql('ALTER TABLE wording DROP CONSTRAINT FK_15F91DD2115F0EE5');
        $this->addSql('ALTER TABLE wording_translation DROP CONSTRAINT FK_353F7543D34102DF');
        $this->addSql('DROP TABLE citizent');
        $this->addSql('DROP TABLE citizent_municipality');
        $this->addSql('DROP TABLE debt');
        $this->addSql('DROP TABLE gouvernorate');
        $this->addSql('DROP TABLE municipality');
        $this->addSql('DROP TABLE municipality_agent');
        $this->addSql('DROP TABLE municipality_taxe_search_criteria');
        $this->addSql('DROP TABLE new_api_user');
        $this->addSql('DROP TABLE notice');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE property_year_debt');
        $this->addSql('DROP TABLE taxe');
        $this->addSql('DROP TABLE taxe_search_criteria');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE token_api_user');
        $this->addSql('DROP TABLE wording');
        $this->addSql('DROP TABLE wording_domain');
        $this->addSql('DROP TABLE wording_translation');
    }
}
