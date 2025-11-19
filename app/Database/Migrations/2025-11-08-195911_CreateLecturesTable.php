<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLecturesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'lecture_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'primary' => true,
            ],
            'section_id' => [
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
            'content_type' => [
                'type' => 'ENUM',
                'constraint' => ['video', 'article', 'quiz', 'assignment', 'live'],
            ],
            'video_url' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'video_duration' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'article_content' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'downloadable_resources' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'is_preview' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'is_published' => [
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

        $this->forge->addKey('lecture_id', true);
        $this->forge->addKey('section_id');
        $this->forge->addForeignKey('section_id', 'sections', 'section_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lectures');
    }

    public function down()
    {
        $this->forge->dropTable('lectures');
    }
}
