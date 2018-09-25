<?php
/**
 * @author akiraz@bk.ru
 * @link https://github.com/akiraz2/yii2-ticket-support
 * @copyright 2018 akiraz2
 * @license MIT
 */

namespace akiraz2\support\console;

use MongoDB\BSON\UTCDateTime;
use yii\db\Query;

/**
 * Class MigrateController
 * @package akiraz2\contact\console
 */
class MigrateController extends \yii\console\Controller
{
    public function actionIndex()
    {
        echo "Migrating Cat...\n";
        /* logs */
        $rows = (new Query())->select('*')->from('{{%support_category}}')->all();
        $collection = \Yii::$app->mongodb->getCollection('support_categorys');
        foreach ($rows as $row) {
            $collection->insert([
                'title' => $row['title'],
                'status' => (int)$row['status'],
                'created_at' => new UTCDateTime($row['created_at'] * 1000),
                'updated_at' => new UTCDateTime($row['updated_at'] * 1000),
            ]);
        }
        echo "Cat migration completed.\n";
    }
}