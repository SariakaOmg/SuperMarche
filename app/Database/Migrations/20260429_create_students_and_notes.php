<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Create_students_and_notes extends Migration
{
    public function up()
    {
        // students
        $this->forge->addField([
            'id' => ['type'=>'int','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'matricule' => ['type'=>'varchar','constraint'=>64,'null'=>true],
            'firstname' => ['type'=>'varchar','constraint'=>128,'null'=>true],
            'lastname' => ['type'=>'varchar','constraint'=>128,'null'=>true],
            'program' => ['type'=>'varchar','constraint'=>128,'null'=>true],
            'created_at' => ['type'=>'datetime','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('students', true);

        // notes
        $this->forge->addField([
            'id' => ['type'=>'int','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'student_id' => ['type'=>'int','constraint'=>11,'unsigned'=>true],
            'subject' => ['type'=>'varchar','constraint'=>128,'null'=>false],
            'note' => ['type'=>'decimal','constraint'=>'5,2','default'=>0.00],
            'coeff' => ['type'=>'int','constraint'=>3,'default'=>1],
            'semester' => ['type'=>'varchar','constraint'=>20,'null'=>true],
            'optional' => ['type'=>'tinyint','constraint'=>1,'default'=>0],
            'created_at' => ['type'=>'datetime','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('student_id','students','id','CASCADE','CASCADE');
        $this->forge->createTable('notes', true);
    }

    public function down()
    {
        $this->forge->dropTable('notes', true);
        $this->forge->dropTable('students', true);
    }
}
