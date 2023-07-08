<?php

use yii\db\Migration;

class m220227_143429_oauth2_access_token extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%oauth2_access_token}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'access_token' => "varchar(80) NOT NULL COMMENT '授权Token'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'auth_key' => "varchar(32) NULL DEFAULT '' COMMENT '授权令牌'",
            'client_id' => "varchar(64) NOT NULL COMMENT '授权ID'",
            'member_id' => "varchar(100) NULL DEFAULT '' COMMENT '用户ID'",
            'expires' => "timestamp NOT NULL COMMENT '有效期'",
            'scope' => "json NULL COMMENT '授权权限'",
            'grant_type' => "varchar(30) NULL DEFAULT '' COMMENT '组别'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='oauth2_授权令牌'");

        /* 索引设置 */
        $this->createIndex('client_id','{{%oauth2_access_token}}','client_id',0);
        $this->createIndex('access_token','{{%oauth2_access_token}}','access_token',0);


        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%oauth2_access_token}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

