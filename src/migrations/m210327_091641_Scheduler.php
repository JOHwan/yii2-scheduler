<?php

use yii\db\Expression;
use yii\db\Migration;

class m210327_091641_Scheduler extends Migration
{
    public function safeUp()
    {
        $options = (\Yii::$app->db->getDriverName() == 'mysql') ? 'ENGINE=InnoDB' : '';

        $this->createTable('scheduler_log', [
            'id'=> $this->primaryKey(),
            'scheduler_task_id'=> $this->integer(11)->notNull(),
            'started_at'=> $this->timestamp()->notNull()->defaultValue(new Expression('NOW()')),
            'ended_at'=> $this->timestamp()->null()->defaultValue(NULL),
            'output'=> $this->text()->notNull(),
            'error'=> $this->tinyInteger(1)->notNull()->defaultValue(0),
        ], $options);

        $this->createIndex('id_log_UNIQUE', 'scheduler_log','id',true);
        $this->createIndex('fk_table1_scheduler_task_idx', 'scheduler_log','scheduler_task_id',false);

        $this->createTable('scheduler_task', [
            'id'=> $this->primaryKey(),
            'name'=> $this->string(45)->notNull(),
            'schedule'=> $this->string(45)->notNull(),
            'description'=> $this->text()->notNull(),
            'status_id'=> $this->integer(11)->notNull(),
            'started_at'=> $this->timestamp()->null()->defaultValue(NULL),
            'last_run'=> $this->timestamp()->null()->defaultValue(NULL),
            'next_run'=> $this->timestamp()->null()->defaultValue(NULL),
            'active'=> $this->tinyInteger(1)->notNull()->defaultValue(0),
        ], $options);

        $this->createIndex('id_task_UNIQUE', 'scheduler_task','id',true);
        $this->createIndex('name_UNIQUE', 'scheduler_task','name',true);
        $this->addForeignKey('fk_scheduler_log_scheduler_task_id', 'scheduler_log', 'scheduler_task_id', 'scheduler_task', 'id');
    }

    public function safeDown()
    {
        $this->delete('scheduler_log');
        $this->delete('scheduler_task');

        $this->dropForeignKey('fk_scheduler_log_scheduler_task_id', 'scheduler_log');
        $this->dropTable('scheduler_log');
        $this->dropTable('scheduler_task');
    }
}
