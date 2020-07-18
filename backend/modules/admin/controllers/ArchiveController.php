<?php

namespace backend\modules\admin\controllers;

use common\helpers\Archiver;
use common\helpers\Database;
use Yii;
use common\models\forms\EmailForm;
use yii\web\Controller;

class ArchiveController extends Controller
{
    public function actionIndex()
    {
        //TODO: убрать и выводить в фоновый процесс
        set_time_limit(300);

        $model = new EmailForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $dbName = Database::getDsnAttribute('dbname', Yii::$app->db->dsn);

            $currentDateTime = false;
            $archiveDirName = false;

            $archiveResultFile = false;
            for (; ;) {
                $currentDateTime = date('Y-m-d_H:i:s');
                if (!is_file($archiveResultFile = Yii::getAlias('@app/data/archive/') . $currentDateTime . '.zip')) {
                    break;
                }
                sleep(1);
            }

            /** TODO: потом может пригодиться, пока будем архивировать в директори изображений
             * for (; ;) {
             * $currentDateTime = date('Y-m-d_H:i:s');
             * if (!is_dir($archiveDirName = Yii::getAlias('@app/data/archive') . '/' . $currentDateTime)) {
             * mkdir($archiveDirName);
             * break;
             * }
             * sleep(1);
             * }*/

            //$currentDateTime = date('Y-m-d_H:i:s');
            $archiveDirName = Yii::getAlias('@webroot') . Yii::$app->params['component_images']['url_path'];

            $sqlFilePath = $archiveDirName . "{$currentDateTime}.sql";

            $command = 'mysqldump --user=' . Yii::$app->db->username
                . ' --password=' . Yii::$app->db->password
                . " --host=localhost $dbName > $sqlFilePath";

            exec($command);

            //unlink($sqlFilePath);

            Archiver::archiveFolder($archiveDirName, $archiveResultFile);

            $result = Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['senderEmail'])
                ->setTo($model->email)
                ->setSubject('"' . Yii::$app->name . '" - ' . Yii::t('app', 'archive'))
                ->setTextBody(Yii::t('app', 'Your archive is in the attachment.'))
                ->attach($archiveResultFile)
                //->setHtmlBody('<b>текст сообщения в формате HTML</b>')
                ->send();

            //unlink($archiveResultFile);

            //TODO: придумать лучший способ
            for (; ;) {
                if (is_file($archiveResultFile)) {
                    break;
                }
                sleep(2);
            }

            if ($result) {
                Yii::$app->session->setFlash('success',
                    Yii::t('app', 'Archive successfully sent to {email}', ['email' => $model->email]));
            } else {
                Yii::$app->session->setFlash('error',
                    Yii::t('app', 'Error sending email to {email}', ['email' => $model->email]));
            }
        }

        $model->email = Yii::$app->user->identity->email;

        return $this->render('index', [
            'emailForm' => $model,
        ]);
    }
}
