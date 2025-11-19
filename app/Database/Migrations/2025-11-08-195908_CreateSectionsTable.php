<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSectionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'section_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'primary' => true,
            ],
            'course_id' => [
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
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'is_free_preview' => [
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

        $this->forge->addKey('section_id', true);
        $this->forge->addKey('course_id');
        $this->forge->addForeignKey('course_id', 'courses', 'course_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sections');
    }

    public function down()
    {
        $this->forge->dropTable('sections');
    }
}
