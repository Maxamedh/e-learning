<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserSessionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'session_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'primary' => true,
            ],
            'user_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'login_time' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'logout_time' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
        ]);

        $this->forge->addKey('session_id', true);
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_sessions');
    }

    public function down()
    {
        $this->forge->dropTable('user_sessions');
    }
}
