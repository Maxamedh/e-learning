<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDiscussionsTable extends Migration
{
    public function up()
    {
        // Create discussions table
        $this->forge->addField([
            'discussion_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'primary' => true,
            ],
            'course_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'user_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 300,
            ],
            'content' => [
                'type' => 'TEXT',
            ],
            'post_type' => [
                'type' => 'ENUM',
                'constraint' => ['question', 'discussion', 'announcement'],
                'default' => 'discussion',
            ],
            'is_pinned' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'is_resolved' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'view_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
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

        $this->forge->addKey('discussion_id', true);
        $this->forge->addKey('course_id');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('course_id', 'courses', 'course_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('discussions');

        // Create discussion_replies table
        $this->forge->addField([
            'reply_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'primary' => true,
            ],
            'discussion_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'user_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'parent_reply_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => true,
            ],
            'content' => [
                'type' => 'TEXT',
            ],
            'is_instructor_reply' => [
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

        $this->forge->addKey('reply_id', true);
        $this->forge->addKey('discussion_id');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('discussion_id', 'discussions', 'discussion_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('parent_reply_id', 'discussion_replies', 'reply_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('discussion_replies');
    }

    public function down()
    {
        $this->forge->dropTable('discussion_replies');
        $this->forge->dropTable('discussions');
    }
}
