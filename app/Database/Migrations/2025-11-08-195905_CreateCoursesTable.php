<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCoursesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'course_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'primary' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
            ],
            'subtitle' => [
                'type' => 'VARCHAR',
                'constraint' => 300,
                'null' => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'instructor_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'discount_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'language' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'English',
            ],
            'level' => [
                'type' => 'ENUM',
                'constraint' => ['beginner', 'intermediate', 'advanced', 'all'],
                'default' => 'beginner',
            ],
            'thumbnail_url' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'promo_video_url' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'total_duration' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'total_lectures' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'total_students' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'avg_rating' => [
                'type' => 'DECIMAL',
                'constraint' => '3,2',
                'default' => 0.00,
            ],
            'total_reviews' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'published', 'pending', 'rejected'],
                'default' => 'draft',
            ],
            'requirements' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'learning_outcomes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'target_audience' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_free' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'is_featured' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
                'on update' => 'CURRENT_TIMESTAMP',
            ],
            'published_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('course_id', true);
        $this->forge->addKey('instructor_id');
        $this->forge->addKey('category_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('instructor_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'categories', 'category_id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('courses');
    }

    public function down()
    {
        $this->forge->dropTable('courses');
    }
}
