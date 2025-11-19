<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCourseReviewsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'review_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'course_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'user_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'rating' => [
                'type' => 'INT',
                'constraint' => 1,
            ],
            'review_title' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true,
            ],
            'review_text' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_approved' => [
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
        ]);

        $this->forge->addKey('review_id', true);
        $this->forge->addUniqueKey(['course_id', 'user_id']);
        $this->forge->addKey('course_id');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('course_id', 'courses', 'course_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('course_reviews');
    }

    public function down()
    {
        $this->forge->dropTable('course_reviews');
    }
}
