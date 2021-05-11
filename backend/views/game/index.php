<?php

use common\helpers\Internationalization;
use common\helpers\Js;
use common\helpers\Web;
use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\HtmlHelper;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Пицца Майя - игра');

$this->registerJsFile('/js/FileSaver.js', ['depends' => ['frontend\assets\VendorAsset']]);

//echo $this->render('_content_js', ['initialJSCode' => $initialJSCode, 'uid' => $uid, 'activeUsers' => $activeUsers]);

$this->registerJsFile('https://unpkg.com/@tweenjs/tween.js@18.5.0/dist/tween.umd.js');
$this->registerJsFile('/js/CanvasFlowmapLayer.js');

$this->registerCss(<<<CSS
#map-placeholder {
    width: 100%;
    height: 100vh;
    background-color: wheat;
}
CSS
);

$this->registerJs(<<<JS
(function() {
    var lat = 55.751244;
    var lng = 37.618423;
    
    var map = L.map('map-placeholder').setView([lat, lng], 13);
    console.log("map: ", map);
    
    var pulsingIcon = L.icon.pulse({iconSize: [15, 15], color: 'green', fillColor: 'red'});
    var customerMarker = this.addMarkerByCoords(lat, lng, pulsingIcon);
    
    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    	attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap<\/a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA<\/a>',
    	maxZoom: 18
	}).addTo(map);
    
})();
JS
);


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
/*foreach ($activeUsers as $aUser) {
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
}*/

$jsStrings = [
    'delete' => Yii::t('app', 'Delete'),
    'switch-component' => Yii::t('app', 'Switch component'),
    'ingredients' => Yii::t('app', 'Ingredients'),
    //'for_free' => Yii::t('app', 'For free'),
    'price:' => Yii::t('app', 'Price:'),
    'Pizzerias' => Yii::t('app', 'Pizzerias'),
    'info' => Yii::t('app', 'Info'),
    'for-free' => Yii::t('app', 'For free'),
    'Confirm order' => Yii::t('app', 'Confirm order'),

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
    'Order ID:' => Yii::t('app', 'Order ID:'),
    'Unknown error. Please try again later or refer to administrator.'=> Yii::t('app', 'Unknown error. Please try again later or refer to administrator.'),

    'currency' => Internationalization::getCurrencySign(),
    'cant_add_so_many_of_component' => Yii::t('app', 'So much does not fit on pizza!'),
    'frame-order-form_src' => Url::to(['/vendor/order-panel', 'uid' => $uid]),

    'domain-admin' => Yii::$app->params['domain-admin'],
    'domain-admin-schema' => Yii::$app->params['domain-admin-schema'],
];

$userGeoPosition = (new \common\models\Geo())->getUserGeoPosition();

$jsCode = "var gl = {data:{}};\n"
    . Js::createJsDataStrings($jsStrings)
    . Js::createJsDataStringsNE([
        'activeUserProfilesJs' => $activeUserProfiles,
        'initialJSCode' => $initialJSCode,
        'userGeoPosition' => $userGeoPosition,
        'isWebLocal' => \common\helpers\Web::isLocal(),

        'yii-params' => [
            'websocket' => [
                'schema' => Yii::$app->params['websocket']['schema'],
                'host' => Yii::$app->params['websocket']['host'],
                'port' => Yii::$app->params['websocket']['port'],
            ],
        ],
    ]);

$this->registerCss(<<<CSS
.vertical-pane .component-link {
    visibility: hidden;
}
.map-marker-icon {
    border-radius: 50%;
    filter: drop-shadow(3px 3px 2px #222);
}
CSS
);

$this->registerJs($jsCode, \yii\web\View::POS_HEAD);
$this->registerJsFile(Url::to(['/js/vendor/order-form/storage.js']), ['depends' => ['frontend\assets\VendorAsset'], 'appendTimestamp' => YII_DEBUG]);
$this->registerJsFile(Url::to(['/js/vendor/order-form.js']), ['depends' => ['frontend\assets\VendorAsset'], 'appendTimestamp' => YII_DEBUG]);

$this->registerJsFile(Url::to(['/js/vendor/types.js']), ['depends' => ['frontend\assets\VendorAsset'], 'appendTimestamp' => YII_DEBUG]);
$this->registerJsFile(Url::to(['/js/vendor/events.js']), ['depends' => ['frontend\assets\VendorAsset'], 'appendTimestamp' => YII_DEBUG]);

$this->registerJsFile(Url::to(['/js/vendor/order-form/component.drag-n-drop.js']), ['depends' => ['frontend\assets\VendorAsset'], 'appendTimestamp' => YII_DEBUG]);
$this->registerJsFile(Url::to(['/js/vendor/order-form/scrollbar.js']), ['depends' => ['frontend\assets\VendorAsset'], 'appendTimestamp' => YII_DEBUG]);
$this->registerJsFile(Url::to(['/js/vendor/order-form/positioning.js']), ['depends' => ['frontend\assets\VendorAsset'], 'appendTimestamp' => YII_DEBUG]);
$this->registerJsFile(Url::to(['/js/vendor/order-form/geo.js', 'ver' => '1.4']), ['depends' => ['frontend\assets\VendorAsset'], 'appendTimestamp' => YII_DEBUG]);
$this->registerJsFile(Url::to(['/js/vendor/order-form/components.js']), ['depends' => ['frontend\assets\VendorAsset'], 'appendTimestamp' => YII_DEBUG]);
$this->registerJsFile(Url::to(['/js/vendor/order-form/order.js']), ['depends' => ['frontend\assets\VendorAsset'], 'appendTimestamp' => YII_DEBUG]);
$this->registerJsFile(Url::to(['/js/vendor/order-form/categories.js']), ['depends' => ['frontend\assets\VendorAsset'], 'appendTimestamp' => YII_DEBUG]);
//$this->registerJsFile(Url::to(['/js/vendor/order-form/websocket.js']), ['depends' => ['frontend\assets\VendorAsset'], 'appendTimestamp' => YII_DEBUG]);
//$this->registerJsFile(Url::to(['/js/vendor/order-form/longpoll.js']), ['depends' => ['frontend\assets\VendorAsset'], 'appendTimestamp' => YII_DEBUG]);
$this->registerJsFile(Url::to(['/js/vendor/order-form/sse.js']), ['depends' => ['frontend\assets\VendorAsset'], 'appendTimestamp' => YII_DEBUG]);

$this->registerJsFile(Url::to(['/js/vendor/order-form/handlers/orderInterface.js']), ['depends' => ['frontend\assets\VendorAsset'], 'appendTimestamp' => YII_DEBUG]);
$this->registerJsFile(Url::to(['/js/vendor/order-form/handlers/orderAddressHandler.js']), ['depends' => ['frontend\assets\VendorAsset'], 'appendTimestamp' => YII_DEBUG]);

$this->registerJsFile(Url::to(['/js/ESD/object.js']), ['depends' => ['frontend\assets\VendorAsset'], 'appendTimestamp' => YII_DEBUG]);

$this->registerJsFile(Url::to(['/dist/leaflet/MovingMarker.js']), ['depends' => ['frontend\assets\VendorAsset']]);
$this->registerJsFile(Url::to(['/dist/leaflet/L.Icon.Pulse.js']), ['depends' => ['frontend\assets\VendorAsset']]);
$this->registerCssFile(Url::to(['/dist/leaflet/L.Icon.Pulse.css']));

//$this->registerJsFile(Url::to(['/dist/leaflet/leaflet-routing-machine/leaflet-routing-machine.min.js']), ['depends' => ['frontend\assets\VendorAsset']]);
$this->registerJsFile(Url::to(['/dist/leaflet/leaflet-routing-machine/leaflet-routing-machine.js']), ['depends' => ['frontend\assets\VendorAsset']]);
$this->registerCssFile(Url::to(['/dist/leaflet/leaflet-routing-machine/leaflet-routing-machine.css']));



$cityList = (new \common\models\Geo())->getCityList();

echo $this->render('_content_js', ['initialJSCode' => $initialJSCode, 'uid' => $uid, 'activeUsers' => []]);

?>
<div id="map-placeholder"></div>
