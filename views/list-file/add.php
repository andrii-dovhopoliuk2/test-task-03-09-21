<?php

use yii\bootstrap4\ActiveForm;

/* @var $model app\models\ListFile */

$this->title = Yii::t('app', 'Add file DB');

$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'files[]')->fileInput(['multiple' => true]) ?>

    <button>Add</button>
<?php ActiveForm::end() ?>