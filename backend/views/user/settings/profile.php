<?php

use Da\User\Helper\TimezoneHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \Da\User\Model\Profile $model
 * @var TimezoneHelper $timezoneHelper
 * @var \common\models\UploadProfileIconImageForm $uploadProfileIconImageForm
 */

$this->title = Yii::t('usuario', 'Profile settings');
$this->params['breadcrumbs'][] = $this->title;
$timezoneHelper = $model->make(TimezoneHelper::class);

$this->registerCssFile('https://unpkg.com/leaflet@1.6.0/dist/leaflet.css', [
    'integrity' => 'sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==',
    'crossorigin' => '',
]);
$this->registerJsFile('https://unpkg.com/leaflet@1.6.0/dist/leaflet.js', [
    'integrity' => 'sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==',
    'crossorigin' => '',
]);

$mpData = Yii::$app->mapHandler->getStartPointParamsForUser();

$mpMarksJs = json_encode(Yii::$app->mapHandler->getUserMarkers());

$statusHintsJs = json_encode(ArrayHelper::map($model->getStatuses(), 'id', 'hint'));

$this->registerJs(<<<JS
(function() {
    var currentMarker = null;

    var defaultMarks = $mpMarksJs;

    var map = L.map('place-map').setView([{$mpData['latitude']}, {$mpData['longitude']}], {$mpData['zoom']});
    
    setMapLabels(!defaultMarks.length);
    
    if (defaultMarks.length) {
        var latLng = L.latLng(defaultMarks[0].latitude, defaultMarks[0].longitude);
        addMarker({latlng:latLng});
    }

	L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    	attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap<\/a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA<\/a>',
    	maxZoom: 18
	}).addTo(map);
	//var newMarkerGroup = new L.LayerGroup();
	map.on('click', addMarker);
	
	function bindNewPopupToMarker() {
	    if (!currentMarker) {
	        return;
	    }
	    
	    currentMarker.unbindPopup();
	    
	    var popupHtml = gl.createMapMarkerPopupHtml({
            name:$('#profile-name').val(),
            address:$('#profile-location').val(),
            url:$('#profile-website').val()
        });
        if (popupHtml) {
            currentMarker.bindPopup(popupHtml);
        }
	}
	
	$('#profile-name, #profile-location, #profile-website').blur(function() {
	    bindNewPopupToMarker();
	});

	function addMarker(e) {
        if (currentMarker) {
            return;
        }
        currentMarker = new L.marker(e.latlng, {draggable:true}).addTo(map);
        
        bindNewPopupToMarker();
        
        setMarkerPosition(currentMarker);
        
        currentMarker.on('dragend', function () {
            setMarkerPosition(currentMarker);
        });
    }
    
    function setMarkerPosition(marker) {
	    var latLong = marker.getLatLng().lat + ';' + marker.getLatLng().lng;
	    $('#profile-company_lat_long').val(latLong);
	    setMapLabels(false);
    }
    
    function setMapLabels(noMarker) {
	    if (noMarker) {
	        $('.map-with-no-marker').attr('style', 'display: inline !important');
        } else {
            $('.map-with-no-marker').attr('style', 'display: none !important');
            $('.map-with-marker').attr('style', 'display: inline !important');
	    }
    }
    
    
    var statusHints = $statusHintsJs;
	function setStatusHint() {
	    var hint = statusHints[$('#profile-status').val()];
	    $('.field-profile-status .hint-block').text(hint);
	}
	setStatusHint();
	$('#profile-status').change(function() {
	  setStatusHint();
	});
	
    
})();
JS
);

$imgConfig = [
    'options' => [
        'multiple' => false,
        'accept' => 'image/*',
    ],
    'pluginOptions' => [
        'previewFileType' => 'image',
        'showUpload' => false,
        //'showRemove' => false,
        'overwriteInitial' => true,
        'initialPreviewAsData' => true,
    ],
];

if ($model->icon_image_path) {
    //$imgConfig['pluginOptions']['initialPreview'][] = $uploadProfileIconImageForm::imagePathUrl() . '/' . $model->icon_image_path;
    $imgConfig['pluginOptions']['initialPreview'][] = $model->icon_image_path;
    $imgConfig['pluginOptions']['initialPreviewConfig'][] = [
        'url' => Url::to(['/user/settings/image-delete']),
    ];
}

?>

<div class="clearfix"></div>

<?= $this->render('/shared/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(
                    [
                        'id' => $model->formName(),
                        'options' => ['class' => 'form-horizontal'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-lg-offset-3 col-lg-9\">{error}\n{hint}</div>",
                            'labelOptions' => ['class' => 'col-lg-3 control-label'],
                        ],
                        'enableAjaxValidation' => true,
                        'enableClientValidation' => true,
                        'validateOnBlur' => true,
                    ]
                ); ?>

                <?= $form->field($model, 'name')->hint(Yii::t('app', 'User Name or Company Name')) ?>

                <?= $form->field($model, 'status')->dropDownList(ArrayHelper::map($model->getStatuses(), 'id',
                    'name'))->hint('&nbsp;') ?>

                <?= $form->field($model, 'public_email')->label(Yii::t('app', 'Reserve Email')) ?>

                <?= $form->field($model, 'website');/*->hint(Yii::t('app',
                    'Input the domain of the site on which the order form will be placed (required for the form to work).'))*/ ?>

                <?= $form->field($model, 'location') ?>

                <?= Html::activeHiddenInput($model, 'company_lat_long') ?>

                <div class="form-group field-profile-map">
                    <label class="col-lg-3 control-label map-label">
                        <span><?= Yii::t('app',
                                'Mark on the map the location of your pizzeria (the place from which orders will be delivered).') ?><hr></span>
                        <span class="hidden map-with-no-marker"><?= Yii::t('app',
                                'Click on the map to set up your company marker.') ?>'<br></span>
                        <span class="hidden map-with-marker"><?= Yii::t('app',
                                'Drag your company marker if you want to change its location.') ?><br></span>
                        <span class="hidden text-danger map-with-marker"><?= Yii::t('app',
                                'Don\'t forget to click “Save” to commit the changes.') ?></span>
                    </label>
                    <div class="col-lg-9 col-md-12">
                        <div id="place-map" style="height:30vmax"></div>
                    </div>
                    <div class="col-sm-offset-3 col-lg-9">
                        <div class="help-block"><?= !empty($model->errors['company_lat_long']) ? implode('<br>',
                                $model->errors['company_lat_long']) : '' ?></div>
                    </div>
                </div>

                <?= $form
                    ->field($model, 'timezone')
                    ->dropDownList(ArrayHelper::map($timezoneHelper->getAll(), 'timezone', 'name'));
                ?>
                <?php /*$form
                    ->field($model, 'gravatar_email')
                    ->hint(
                        Html::a(
                            Yii::t('usuario', 'Change your avatar at Gravatar.com'),
                            'http://gravatar.com',
                            ['target' => '_blank']
                        )
                    )*/ ?>

                <?= $form->field($model, 'bio')->textarea(['rows' => 7]) ?>

                <?= $form->field($model, 'schedule')->textarea(['rows' => 7])->label(Yii::t('app', 'Расписание')) ?>

                <?= $form->field($uploadProfileIconImageForm, 'iconImageFile')
                    ->widget(\kartik\file\FileInput::className(), $imgConfig)
                    ->label(Yii::t('app', 'Icon image file on the map'));
                ?>

                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-9">
                        <?= Html::submitButton(Yii::t('usuario', 'Save'), ['class' => 'btn btn-block btn-success']) ?>
                        <br>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
