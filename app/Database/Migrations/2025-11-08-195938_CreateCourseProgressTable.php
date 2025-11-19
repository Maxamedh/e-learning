<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCourseProgressTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'progress_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'course_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'completed_lectures' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'total_lectures' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'progress_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0.00,
            ],
            'last_accessed_lecture_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => true,
            ],
            'last_accessed_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'started_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'completed_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('progress_id', true);
        $this->forge->addUniqueKey(['user_id', 'course_id']);
        $this->forge->addKey('user_id');
        $this->forge->addKey('course_id');
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('course_id', 'courses', 'course_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('last_accessed_lecture_id', 'lectures', 'lecture_id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('course_progress');

        // Create lecture_completion table
        $this->forge->addField([
            'completion_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'lecture_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'completed_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'watch_time' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
        ]);

        $this->forge->addKey('completion_id', true);
        $this->forge->addUniqueKey(['user_id', 'lecture_id']);
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('lecture_id', 'lectures', 'lecture_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lecture_completion');
    }

    public function down()
    {
        $this->forge->dropTable('lecture_completion');
        $this->forge->dropTable('course_progress');
    }
}
