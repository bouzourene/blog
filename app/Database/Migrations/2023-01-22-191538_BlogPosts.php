<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BlogPosts extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id' => [
				'type' => 'BIGINT',
				'unsigned' => true,
				'auto_increment' => true
			],
			'author' => [
				'type' => 'VARCHAR'
			],
			'title' => [
				'type' => 'VARCHAR'
			],
			'slug' => [
				'type' => 'VARCHAR'
			],
			'content' => [
				'type' => 'LONGTEXT'
			],
			'created_on' => [
				'type' => 'DATETIME'
			],
			'updated_on' => [
				'type' => 'DATETIME'
			],
			'published_on' => [
				'type' => 'DATETIME',
				'null' => true
			],
			'deleted_on' => [
				'type' => 'DATETIME',
				'null' => true
			]
		]);

		$this->forge->addKey('id', true);
		$this->forge->createTable('blog');
	}

	public function down()
	{
		$this->forge->dropTable('blog');
	}
}
