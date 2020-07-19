<?php

/* @var $this yii\web\View */

/* @var $initialJSCode string */

/* @var $uid string */

/* @var $activeUsers common\models\db\User[] */


use common\helpers\Internationalization;
use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\Js;
use corpsepk\DaData\SuggestionsWidget;

//use kartik\select2\Select2;

$this->registerCssFile('https://unpkg.com/leaflet@1.6.0/dist/leaflet.css', [
    'integrity' => 'sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==',
    'crossorigin' => '',
]);
$this->registerJsFile('https://unpkg.com/leaflet@1.6.0/dist/leaflet.js', [
    'integrity' => 'sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==',
    'crossorigin' => '',
]);

$this->registerJsFile('https://unpkg.com/@tweenjs/tween.js@18.5.0/dist/tween.umd.js');
$this->registerJsFile('/js/CanvasFlowmapLayer.js');

$activeUserProfiles = [];
foreach ($activeUsers as $aUser) {
    $popupHtml = '<b>' . $aUser->profile->name . '</b><br>' . nl2br(Html::encode($aUser->profile->bio));
    if ($aUser->profile->schedule) {
        $popupHtml .= '<hr><i>Расписание:</i><br>' . nl2br(Html::encode($aUser->profile->schedule));
    }
    $activeUserProfiles[] = [
        'id' => $aUser->profile->user_id,
        'name' => $aUser->profile->name,
        'location' => $aUser->profile->location,
        'website' => $aUser->profile->website,
        'timezone' => $aUser->profile->timezone,
        'bio' => $aUser->profile->bio,
        'company_lat_long' => $aUser->profile->company_lat_long,
        'status' => $aUser->profile->status,
        'icon_name' => $aUser->profile->icon_name,
        'icon_color' => $aUser->profile->icon_color,
        'icon_image_path' => $aUser->profile->icon_image_path,
        'facility_image_path' => $aUser->profile->facility_image_path,
        'schedule' => $aUser->profile->schedule,

        'icon_image_size' => $aUser->getIconImageSize(35, 35),
        'popupHtml' => $popupHtml,
    ];
}

$jsStrings = [
    'delete' => Yii::t('app', 'Delete'),
    'switch-component' => Yii::t('app', 'Switch component'),
    'ingredients' => Yii::t('app', 'Ingredients'),
    //'for_free' => Yii::t('app', 'For free'),
    'price:' => Yii::t('app', 'Price:'),
    'Pizzerias' => Yii::t('app', 'Pizzerias'),
    'info' => Yii::t('app', 'Info'),
    'for-free' => Yii::t('app', 'For free'),

    'please-enter' => Yii::t('app', 'Please, enter:'),
    'delivery-address' => Yii::t('app', 'Delivery address'),
    'your-name' => Yii::t('app', 'Your name'),
    'email-or-phone' => Yii::t('app', 'Email or phone'),

    'geolocation-is-not-accessible' => Yii::t('app', 'Sorry, geolocation is not accessible in your browser.'),
    'Order confirmed.' => Yii::t('app', 'Order confirmed.'),
    'Expect pizzeria notifications.' => Yii::t('app', 'Expect pizzeria notifications.'),
    'Minimize the order panel' => Yii::t('app', 'Minimize the order panel'),
    'Recipient:' => Yii::t('app', 'Recipient:'),
    'Delivery address:' => Yii::t('app', 'Delivery address:'),
    'Phone:' => Yii::t('app', 'Phone:'),
    'Your comment:' => Yii::t('app', 'Your comment:'),

    'currency' => Internationalization::getCurrencySign(),
    'cant_add_so_many_of_component' => Yii::t('app', 'So much does not fit on pizza!'),
    'frame-order-form_src' => Url::to(['/vendor/order-panel', 'uid' => $uid]),
];

$userGeoPosition = (new \common\models\Geo())->getUserGeoPosition();

$jsCode = "var gl = {data:{}};\n"
    . Js::createJsDataStrings($jsStrings)
    . Js::createJsDataStringsNE([
        'activeUserProfilesJs' => $activeUserProfiles,
        'initialJSCode' => $initialJSCode,
        'userGeoPosition' => $userGeoPosition,
    ]);

$this->registerJs($jsCode, \yii\web\View::POS_HEAD);
$this->registerJsFile(Url::to(['/js/vendor/order-form.js']), ['depends' => ['frontend\assets\VendorAsset']]);
$this->registerJsFile(Url::to(['/js/leaflet/MovingMarker.js']), ['depends' => ['frontend\assets\VendorAsset']]);

$this->registerJsFile(Url::to(['/js/leaflet/L.Icon.Pulse.js']), ['depends' => ['frontend\assets\VendorAsset']]);
$this->registerCssFile(Url::to(['/js/leaflet/L.Icon.Pulse.css']));

$cityList = (new \common\models\Geo())->getCityList();

/*$cityListSelect = [];
foreach ($cityList as $cityId => $cityData) {
    $cityListSelect[$cityId] = $cityData['name'];
}*/
?>
<div id="elems-container" style="display: none">
    <div class="order-data-container-wrp">
        <div class="order-data-container">
            <div class="title"><?= Html::encode(Yii::t('app', 'Your data')) ?></div>
            <div class="order-data-data">
                <!--<button class="correct-geolocation" onclick="gl.functions.correctGeolocation();return false;"><? /*= Yii::t('app', 'Скорректировать геолокацию') */ ?></button>-->
                <label class="preview-element-yd">
                    <div class="label"><?= Html::encode(Yii::t('app', 'Your city:')) ?></div>
                    <select class="select-deliver_city_id" name="ShopOrderForm[deliver_city_id]">
                        <?php foreach ($cityList as $cityId => $cityData): ?>
                            <option value="<?= $cityId ?>" data-lat="<?= $cityData['coords']['lat'] ?>"
                                    data-lon="<?= $cityData['coords']['lon'] ?>"><?= Html::encode($cityData['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php /*Select2::widget([
                    'name' => 'ShopOrderForm[deliver_city_id]',
                    'data' => $cityListSelect,
                    'size' => Select2::MEDIUM,
                    'addon' => [
                        'prepend' => [
                            'content' => \kartik\helpers\Html::icon('globe'),
                        ],
                        'append' => [
                            'content' => Html::button(Html::icon('map-marker'), [
                                'class' => 'btn btn-primary',
                                'title' => 'Mark on map',
                                'data-toggle' => 'tooltip'
                            ]),
                            'asButton' => true
                        ]
                    ]
                ]);*/
                    ?>
                </label>
                <label class="preview-element-yd">
                    <div class="label"><?= Html::encode(Yii::t('app', 'Delivery address:')) ?></div>
                    <input type="text" name="ShopOrderForm[deliver_address]" placeholder="<?= Html::encode(Yii::t('app',
                        'Улица, название места, дом/квартира и т.п.')) ?>">
                    <?php /*= SuggestionsWidget::widget([
                    'name' => 'ShopOrderForm[deliver_address]',
                    'count' => 10,
                    'deferRequestBy' => 700,
                    'floating' => true,
                    //'minChars' => 5,
                    'token' => '3b0831fece6038806811a6eaef5843755d0ae9a4',
                ]) */ ?>
                </label>
                <label class="preview-element-yd">
                    <div class="label"><?= Html::encode(Yii::t('app', 'Your name:')) ?></div>
                    <input type="text" name="ShopOrderForm[deliver_customer_name]"
                           placeholder="<?= Html::encode(Yii::t('app', 'Имя(имена) получателей')) ?>"></label>
                <label class="preview-element-yd">
                    <div class="label"><?= Html::encode(Yii::t('app', 'Phone:')) ?></div>
                    <input type="text" name="ShopOrderForm[deliver_phone]"></label>
                <label class="preview-element-yd">
                    <div class="label"><?= Html::encode(Yii::t('app', 'Email:')) ?></div>
                    <input type="text" name="ShopOrderForm[deliver_email]"></label>
                <label class="preview-element-yd">
                    <div class="label"><?= Html::encode(Yii::t('app', 'Payment type:')) ?></div>
                    <select class="select-payment_type_id" name="ShopOrderForm[payment_type_id]">
                        <option value="card"><?= Html::encode('Карта') ?></option>
                        <option value="cash"><?= Html::encode('Наличный расчет') ?></option>
                        <option value="bonuses"><?= Html::encode('Бонусы') ?></option>
                    </select>
                </label>
                <label class="preview-element-yd">
                    <div class="label"><?= Html::encode(Yii::t('app', 'Comment:')) ?></div>
                    <textarea rows="3" name="ShopOrderForm[deliver_comment]"
                              placeholder="<?= Html::encode(Yii::t('app',
                                  'For example: warm, and quickly')) ?>"></textarea></label>
                <!--<label class="preview-element-yd">' + '<div class="label">Когда получить:</div>' + '<input type="text" name="ShopOrderForm[deliver_required_time_begin]"></label>-->
                <div class="error-messages" style="display: none"></div>
            </div>
        </div>
    </div>
    <div class="providers-handling-wrp">
        <div class="title"><?= Html::encode(Yii::t('app', 'Providers')) ?></div>
        <button class="btn-select select-all"
                onclick="gl.functions.SelectProviders['select-all'](this);return false;"><?= Html::encode(Yii::t('app',
                'Select all')) ?></button>
        <button class="btn-select unselect-all"
                onclick="gl.functions.SelectProviders['unselect-all'](this);return false;"><?= Html::encode(Yii::t('app',
                'Unselect all')) ?></button>
        <button class="btn-select nearest"
                onclick="gl.functions.SelectProviders['nearest'](this);return false;"><?= Html::encode(Yii::t('app',
                'Nearest')) ?></button>
        <br>
    </div>
</div>
