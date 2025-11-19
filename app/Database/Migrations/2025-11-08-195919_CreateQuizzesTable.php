<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuizzesTable extends Migration
{
    public function up()
    {
        // Create quizzes table
        $this->forge->addField([
            'quiz_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'primary' => true,
            ],
            'lecture_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'quiz_type' => [
                'type' => 'ENUM',
                'constraint' => ['practice', 'graded', 'survey'],
                'default' => 'practice',
            ],
            'time_limit' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'passing_score' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 70,
            ],
            'max_attempts' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],
            'shuffle_questions' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'show_correct_answers' => [
                'type' => 'BOOLEAN',
                'default' => true,
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

        $this->forge->addKey('quiz_id', true);
        $this->forge->addKey('lecture_id');
        $this->forge->addForeignKey('lecture_id', 'lectures', 'lecture_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('quizzes');

        // Create questions table
        $this->forge->addField([
            'question_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'primary' => true,
            ],
            'quiz_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'question_text' => [
                'type' => 'TEXT',
            ],
            'question_type' => [
                'type' => 'ENUM',
                'constraint' => ['multiple_choice', 'true_false', 'short_answer', 'essay'],
                'default' => 'multiple_choice',
            ],
            'points' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'explanation' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('question_id', true);
        $this->forge->addKey('quiz_id');
        $this->forge->addForeignKey('quiz_id', 'quizzes', 'quiz_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('questions');

        // Create question_options table
        $this->forge->addField([
            'option_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'question_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'option_text' => [
                'type' => 'TEXT',
            ],
            'is_correct' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
        ]);

        $this->forge->addKey('option_id', true);
        $this->forge->addKey('question_id');
        $this->forge->addForeignKey('question_id', 'questions', 'question_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('question_options');

        // Create quiz_attempts table
        $this->forge->addField([
            'attempt_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'primary' => true,
            ],
            'quiz_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'user_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'started_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'submitted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'time_spent' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'total_score' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'max_score' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'passing_score' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'is_passed' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'attempt_number' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],
        ]);

        $this->forge->addKey('attempt_id', true);
        $this->forge->addKey('quiz_id');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('quiz_id', 'quizzes', 'quiz_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('quiz_attempts');

        // Create quiz_answers table
        $this->forge->addField([
            'answer_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'attempt_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'question_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'selected_option_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'answer_text' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_correct' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'points_earned' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0,
            ],
        ]);

        $this->forge->addKey('answer_id', true);
        $this->forge->addKey('attempt_id');
        $this->forge->addKey('question_id');
        $this->forge->addForeignKey('attempt_id', 'quiz_attempts', 'attempt_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('question_id', 'questions', 'question_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('selected_option_id', 'question_options', 'option_id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('quiz_answers');
    }

    public function down()
    {
        $this->forge->dropTable('quiz_answers');
        $this->forge->dropTable('quiz_attempts');
        $this->forge->dropTable('question_options');
        $this->forge->dropTable('questions');
        $this->forge->dropTable('quizzes');
    }
}
