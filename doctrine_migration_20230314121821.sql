-- Doctrine Migration File Generated on 2023-03-14 12:18:21

-- Version DoctrineMigrations\Version20230314120633
ALTER TABLE municipality_agent DROP roles;
ALTER TABLE team DROP roles;
ALTER TABLE wording ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE;
ALTER TABLE wording ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE;
ALTER TABLE wording_domain ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE;
ALTER TABLE wording_domain ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE;
ALTER TABLE wording_translation ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE;
ALTER TABLE wording_translation ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE;
