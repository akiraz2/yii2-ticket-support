<?php
/**
 * @author akiraz@bk.ru
 * @link https://github.com/akiraz2/yii2-ticket-support
 * @copyright 2018 akiraz2
 * @license MIT
 */

namespace akiraz2\support\models;

use akiraz2\support\Mailer;
use akiraz2\support\traits\ModuleTrait;
use Hashids\Hashids;
use PhpImap\IncomingMail;
use Yii;

/**
 * This is the model class for Ticket.
 *
 * @property integer|\MongoDB\BSON\ObjectID|string $id
 * @property integer|\MongoDB\BSON\ObjectID|string $category_id
 * @property string $user_contact
 * @property string $user_name
 * @property string $title
 * @property string $hash_id
 * @property integer $status
 * @property integer $type_id
 * @property integer $priority
 * @property integer|\MongoDB\BSON\ObjectID|string $user_id
 * @property integer|\MongoDB\BSON\UTCDateTime $created_at
 * @property integer|\MongoDB\BSON\UTCDateTime $updated_at
 *
 * @property Content[] $contents
 * @property Category $category
 * @property User $user
 */
class Ticket extends TicketBase
{
    use ModuleTrait;

    const TYPE_SITE = 0;
    const TYPE_EMAIL = 10;
    const TYPE_TELEGRAM = 20;

    const PRIORITY_LOW = 0;
    const PRIORITY_MIDDLE = 10;
    const PRIORITY_HIGH = 20;

    const STATUS_OPEN = 0;
    const STATUS_WAITING = 10;
    const STATUS_CLOSED = 100;

    public $content;
    public $info;
    public $mail_id;
    public $fetch_date;

    /**
     * get status text
     * @return string
     */
    public function getStatusText()
    {
        $status = $this->status;
        $list = self::getStatusOption();
        if (!empty($status) && in_array($status, array_keys($list))) {
            return $list[$status];
        }
        return \akiraz2\support\Module::t('support', 'Unknown');
    }

    /**
     * get status list
     * @param null $e
     * @return array
     */
    public static function getStatusOption($e = null)
    {
        $option = [
            self::STATUS_OPEN => \akiraz2\support\Module::t('support', 'Open'),
            self::STATUS_WAITING => \akiraz2\support\Module::t('support', 'Waiting'),
            self::STATUS_CLOSED => \akiraz2\support\Module::t('support', 'Closed'),
        ];
        if (is_array($e)) {
            foreach ($e as $i) {
                unset($option[$i]);
            }
        }
        return $option;
    }

    /**
     * get status text
     * @return string
     */
    public function getStatusColorText()
    {
        $status = $this->status;
        $list = self::getStatusOption();

        switch ($status) {
            case self::STATUS_CLOSED:
                $color = 'danger';
                break;
            case self::STATUS_OPEN:
                $color = 'primary';
                break;
            case self::STATUS_WAITING:
                $color = 'warning';
                break;
            default:
                $color = 'default';
        }

        if (!is_null($status) && in_array($status, array_keys($list))) {
            return '<span class="label label-' . $color . '">' . $list[$status] . '</span>';
        }

        return '<span class="label label-' . $color . '">' . \akiraz2\support\Module::t('support',
                'Unknown') . '</span>';
    }

    public static function getTypeList()
    {
        return [
            self::TYPE_SITE => \akiraz2\support\Module::t('support', 'Site'),
            self::TYPE_EMAIL => \akiraz2\support\Module::t('support', 'Email'),
            self::TYPE_TELEGRAM => \akiraz2\support\Module::t('support', 'Telegram'),
        ];
    }

    public function getType()
    {
        return self::getTypeList()[$this->type_id];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => self::STATUS_OPEN],
            [['priority'], 'default', 'value' => self::PRIORITY_MIDDLE],
            [['type_id'], 'default', 'value' => self::TYPE_SITE],

            [['title',], 'required'],
            [['title'], 'string', 'max' => 255],

            [['status', 'priority'], 'number'],

            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::className(),
                'targetAttribute' => ['category_id' => Yii::$app->getModule('support')->isMongoDb() ? '_id' : 'id']
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => $this->getModule()->userModel,
                'targetAttribute' => ['user_id' => $this->getModule()->userPK]
            ],

            /* custom */
            [['content'], 'required', 'on' => ['create']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \akiraz2\support\Module::t('support', 'ID'),
            'category_id' => \akiraz2\support\Module::t('support', 'Category'),
            'title' => \akiraz2\support\Module::t('support', 'Title'),
            'content' => \akiraz2\support\Module::t('support', 'Content'),
            'status' => \akiraz2\support\Module::t('support', 'Status'),
            'user_id' => \akiraz2\support\Module::t('support', 'Created By'),
            'created_at' => \akiraz2\support\Module::t('support', 'Created At'),
            'updated_at' => \akiraz2\support\Module::t('support', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getContents()
    {
        if (is_a($this, '\yii\mongodb\ActiveRecord')) {
            return $this->hasMany(Content::className(), ['id_ticket' => '_id']);
        } else {
            return $this->hasMany(Content::className(), ['id_ticket' => 'id']);
        }
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getCategory()
    {
        if (is_a($this, '\yii\mongodb\ActiveRecord')) {
            return $this->hasOne(Category::className(), ['_id' => 'category_id']);
        } else {
            return $this->hasOne(Category::className(), ['id' => 'category_id']);
        }
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne($this->getModule()->userModel, [$this->getModule()->userPK => 'user_id']);
    }

    /**
     * @inheritdoc
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->hash_id = uniqid();
            if ($this->type_id == self::TYPE_SITE) {
                $this->user_id = Yii::$app->user->id;
                $this->user_name = Yii::$app->user->identity->{$this->getModule()->userName};
                $this->user_contact = Yii::$app->user->identity->{$this->getModule()->userEmail};
            }
            if ($this->type_id == self::TYPE_EMAIL) {
                if (($userModel = $this->getModule()->userModel::findOne([$this->getModule()->userEmail => $this->user_contact])) !== null) {
                    $this->user_id = $userModel->{$this->getModule()->userPK};
                }
            }
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $hash_ids = new Hashids(Yii::$app->name, 10);
            $hash_id = $hash_ids->encode($this->id); //
            $this->updateAttributes(['hash_id' => $hash_id]);

            $ticketContent = new Content();
            $ticketContent->id_ticket = $this->id;
            $ticketContent->content = $this->content;
            $ticketContent->info = $this->info;
            $ticketContent->user_id = $this->user_id;
            $ticketContent->mail_id = $this->mail_id;
            $ticketContent->fetch_date = $this->fetch_date;
            if ($ticketContent->save()) {
                // notify support by email, has deleted
            }
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    /**
     * get ticket url
     * @param bool $absolute
     */
    public function getUrl($absolute = false)
    {
        $act = 'createUrl';
        if ($absolute) {
            $act = 'createAbsoluteUrl';
        }
        return \Yii::$app->get($this->getModule()->urlManagerFrontend)->$act([
            'support/ticket/view',
            'id' => (string)$this->hash_id
        ]);
    }

    /**
     * system closes ticket
     */
    public function close()
    {
        if ($this->status != Ticket::STATUS_CLOSED) {
            $post = new Content();
            $post->id_ticket = $this->id;
            $post->user_id = null;
            $post->content = \akiraz2\support\Module::t('support',
                'Ticket was closed automatically due to inactivity.');
            if ($post->save()) {
                $this->status = Ticket::STATUS_CLOSED;
                $this->save();
            }
        }
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function beforeDelete()
    {
        foreach ($this->contents as $content) {
            $content->delete();
        }
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }

    protected function getMailer()
    {
        return \Yii::$container->get(Mailer::className());
    }

    public function loadFromEmail(IncomingMail $mail)
    {
        $this->type_id = self::TYPE_EMAIL;
        $this->title = $mail->subject;
        $this->user_name = $mail->fromName;
        $this->user_contact = $mail->fromAddress;
        $this->content = $mail->textHtml ?? $mail->textPlain;
        $this->info = ($mail->headersRaw);
        $this->mail_id = $mail->id;
        $this->fetch_date = $mail->date;
    }

    public function getNameEmail()
    {
        return $this->user_name . ' (' . $this->user_contact . ')';
    }

    public function isEmail()
    {
        return $this->type_id == self::TYPE_EMAIL;
    }
}
