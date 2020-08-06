<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $worker \common\models\db\CoWorker */

$this->registerCssFile('https://unpkg.com/leaflet@1.6.0/dist/leaflet.css', [
    'integrity' => 'sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==',
    'crossorigin' => '',
]);
$this->registerJsFile('https://unpkg.com/leaflet@1.6.0/dist/leaflet.js', [
    'integrity' => 'sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==',
    'crossorigin' => '',
]);

$this->registerJsFile(Url::to(['/js/leaflet/L.Icon.Pulse.js']), ['depends' => ['frontend\assets\VendorAsset']]);
$this->registerCssFile(Url::to(['/js/leaflet/L.Icon.Pulse.css']));

$this->registerCss(<<<CSS
#worker-place-map {
    width: 100%;
    height: 70vh;
}
CSS
);

$this->registerJsFile(Url::to(['/js/worker/geo.js']), ['depends' => ['backend\assets\WorkerAsset']]);

//TODO: перевод
$this->registerJs(<<<JS
    gl.data['geolocation-is-not-accessible'] = 'Geolocation Not accessible.';
JS
);

?>
<div id="worker-place-map"></div>
