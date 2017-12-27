<?php

/** @var $this \yii\web\View */
/** @var $link string */
/** @var $paramExample string */

?>
<p><strong>UPTIME:&nbsp;<?= $this->params['uptime'] ?></strong></p>
<p><strong>UPLOAD:&nbsp;<?= $this->params['upload'] ?>%</strong></p>

<table>
    <caption>ЗАГРУЗКА СИСТЕМЫ</caption>
    <tr><th>Параметр</th><th>Занято</th><th>Свободно</th><th>% загрузки</th></tr>
    <?= $this->params['table'] ?>
</table>
<br>
<p style="color:brown;">Сообщение отправлено почтовым роботом<br>
    системы умного дома <strong>"Домовенок"</strong></p>