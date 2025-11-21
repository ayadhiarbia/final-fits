<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251118205304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meal (id INT AUTO_INCREMENT NOT NULL, meals_id INT NOT NULL, mealplan_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, calories INT NOT NULL, protein DOUBLE PRECISION NOT NULL, carbs DOUBLE PRECISION NOT NULL, fat DOUBLE PRECISION NOT NULL, INDEX IDX_9EF68E9C88A25CA2 (meals_id), INDEX IDX_9EF68E9C7EA05811 (mealplan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meal_plan (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, total_calories INT NOT NULL, day VARCHAR(255) NOT NULL, INDEX IDX_C7848889A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE progress (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, weight DOUBLE PRECISION NOT NULL, photo VARCHAR(255) DEFAULT NULL, note LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_2201F246A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, no VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, age INT NOT NULL, weight DOUBLE PRECISION NOT NULL, height DOUBLE PRECISION NOT NULL, goal VARCHAR(255) NOT NULL, activity_level VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workout (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, duration INT NOT NULL, calories_burned INT NOT NULL, level VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_649FFB72A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_9EF68E9C88A25CA2 FOREIGN KEY (meals_id) REFERENCES meal_plan (id)');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_9EF68E9C7EA05811 FOREIGN KEY (mealplan_id) REFERENCES meal_plan (id)');
        $this->addSql('ALTER TABLE meal_plan ADD CONSTRAINT FK_C7848889A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE progress ADD CONSTRAINT FK_2201F246A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE workout ADD CONSTRAINT FK_649FFB72A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_9EF68E9C88A25CA2');
        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_9EF68E9C7EA05811');
        $this->addSql('ALTER TABLE meal_plan DROP FOREIGN KEY FK_C7848889A76ED395');
        $this->addSql('ALTER TABLE progress DROP FOREIGN KEY FK_2201F246A76ED395');
        $this->addSql('ALTER TABLE workout DROP FOREIGN KEY FK_649FFB72A76ED395');
        $this->addSql('DROP TABLE meal');
        $this->addSql('DROP TABLE meal_plan');
        $this->addSql('DROP TABLE progress');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE workout');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
