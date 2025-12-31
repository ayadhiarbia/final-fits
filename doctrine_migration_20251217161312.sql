-- Doctrine Migration File Generated on 2025-12-17 16:13:12

-- Version DoctrineMigrations\Version20251216134336
ALTER TABLE meal_plan ADD created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)';
-- Version DoctrineMigrations\Version20251216134336 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20251216134336', '2025-12-17 16:13:12', 0);

-- Version DoctrineMigrations\Version20251216134539
ALTER TABLE meal_plan ADD created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)';
-- Version DoctrineMigrations\Version20251216134539 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20251216134539', '2025-12-17 16:13:12', 0);

-- Version DoctrineMigrations\Version20251217150712
ALTER TABLE workout ADD instructions LONGTEXT DEFAULT NULL, ADD video_url VARCHAR(500) DEFAULT NULL;
-- Version DoctrineMigrations\Version20251217150712 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20251217150712', '2025-12-17 16:13:12', 0);
