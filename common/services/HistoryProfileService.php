<?php

namespace common\services;

use common\models\db\HistoryProfile;
use yii\base\Component;


class HistoryProfileService extends Component
{
    protected $historyProfile;


    public function __construct(HistoryProfile $historyProfile, $config = [])
    {
        parent::__construct($config);

        $this->historyProfile = $historyProfile;
    }

    public static function instance()
    {
        static $instance = null;

        if (!$instance) {
            $instance = new self();
        }
        return $instance;
    }

    public function generateRandomName($prefix)
    {
        //'generated - history profile . ';
        $counter = 1;
        do {
            $newName = '-' . $prefix . '-' . time() . "_$counter";
            ++$counter;
        } while(HistoryProfile::find()->with(['name' => $newName])->exists());

        return $newName;
    }

    public function generateRandomNameForCustomer()
    {
        return $this->generateRandomName('gen - customer profile');
    }
}
