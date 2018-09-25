<?php
/**
 * @author akiraz@bk.ru
 * @link https://github.com/akiraz2/yii2-ticket-support
 * @copyright 2018 akiraz2
 * @license MIT
 */

namespace akiraz2\support\migrations;

use yii\db\Migration;

class m180925_080718_init extends Migration
{
    use \akiraz2\support\traits\ModuleTrait;

    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%support_category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'status' => $this->tinyInteger()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%support_ticket_head}}', [
            'id' => $this->primaryKey(),
            'hash_id' => $this->string()->notNull()->unique(),
            'category_id' => $this->integer()->null()->defaultValue(null),
            'title' => $this->string()->notNull(),
            'type_id' => $this->tinyInteger()->notNull(),
            'user_contact' => $this->string()->notNull(),
            'user_name' => $this->string()->null()->defaultValue(null),
            'status' => $this->tinyInteger()->notNull(),
            'priority' => $this->tinyInteger()->null()->defaultValue(null),
            'user_id' => $this->integer()->null()->defaultValue(null),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('{{%fk_support_ticket_head_user_id-user_id}}', '{{%support_ticket_head}}', 'user_id',
            $this->getModule()->userModel::tableName(), $this->getModule()->userPK, 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%fk_support_ticket_head_category-support_category_id}}', '{{%support_ticket_head}}',
            'category_id',
            '{{%support_category}}', 'id');

        $this->createTable('{{%support_ticket_content}}', [
            'id' => $this->primaryKey(),
            'id_ticket' => $this->integer()->notNull(),
            'content' => $this->text()->notNull(),
            'info' => $this->text()->null()->defaultValue(null),
            'mail_id' => $this->text()->null()->defaultValue(null),
            'fetch_date' => $this->text()->null()->defaultValue(null),
            'user_id' => $this->integer()->null()->defaultValue(null),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('{{%fk_support_ticket_content_id-support_ticket_head_id}}', '{{%support_ticket_content}}',
            'id_ticket', '{{%support_ticket_head}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%fk_support_ticket_content_user_id-user_id}}', '{{%support_ticket_content}}',
            'user_id', $this->getModule()->userModel::tableName(), $this->getModule()->userPK, 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('{{%fk_support_ticket_content_user_id-core_account_id}}',
            '{{%support_ticket_content}}');
        $this->dropForeignKey('{{%fk_support_ticket_content_id-support_ticket_head_id}}',
            '{{%support_ticket_content}}');
        $this->dropTable('{{%support_ticket_content}}');

        $this->dropForeignKey('{{%fk_support_ticket_head_category-support_category_id}}', '{{%support_ticket_head}}');
        $this->dropForeignKey('{{%fk_support_ticket_head_user_id-core_account_id}}', '{{%support_ticket_head}}');
        $this->dropTable('{{%support_ticket_head}}');

        $this->dropTable('{{%support_category}}');
    }

}
