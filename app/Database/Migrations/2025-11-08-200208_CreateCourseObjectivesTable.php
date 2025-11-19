<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCourseObjectivesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'objective_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'course_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'objective_text' => [
                'type' => 'TEXT',
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
        ]);

        $this->forge->addKey('objective_id', true);
        $this->forge->addKey('course_id');
        $this->forge->addForeignKey('course_id', 'courses', 'course_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('course_objectives');
    }

    public function down()
    {
        $this->forge->dropTable('course_objectives');
    }
}
