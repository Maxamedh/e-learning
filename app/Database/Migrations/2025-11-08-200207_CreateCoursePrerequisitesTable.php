<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCoursePrerequisitesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'prerequisite_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'course_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'required_course_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('prerequisite_id', true);
        $this->forge->addKey('course_id');
        $this->forge->addKey('required_course_id');
        $this->forge->addForeignKey('course_id', 'courses', 'course_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('required_course_id', 'courses', 'course_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('course_prerequisites');
    }

    public function down()
    {
        $this->forge->dropTable('course_prerequisites');
    }
}
