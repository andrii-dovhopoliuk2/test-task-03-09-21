<?php
/**
 * @var $model \app\models\Parse;
 * @var $parses ;
 */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
    'options' => ['class' => 'form-horizontal'],
]) ?>
<?= $form->field($model, 'join_files')->checkboxList($parses)->label(false) ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Join', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>