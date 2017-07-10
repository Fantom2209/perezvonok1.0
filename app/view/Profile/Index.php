<h1><?php echo $this->Get('content');?></h1>

<p>Добавить</p>
<form action="<?php echo \app\helpers\Html::ActionPath('Site', 'Add')?>" method="POST" class="ajax-form">
    <input type="hidden" name="UserData[idUser]" value="<?php echo $this->Get('UserId');?>">
    <div>
        <input type="text" name="UserData[DefaultText:name]">
        <div class="error-box"></div>
    </div>
    <div>
        <input type="text" name="UserData[Link:url]">
        <div class="error-box"></div>
    </div>
    <button class="btn btn-primary">Добавить</button>
</form>