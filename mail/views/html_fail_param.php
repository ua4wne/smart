<?php

/** @var $this \yii\web\View */
/** @var $link string */
/** @var $paramExample string */

?>
<h2>Ошибки в записи параметров</h2>
<p>Следующие параметры не обновляются уже более 3-x суток. Необходимо проверить исправность оборудования!</p>

<table>
    <tr><th>Устройство</th><th>Параметр</th><th>Значение</th><th>Ед изм</th><th>Дата считывания</th></tr>
    <?= $this->params['table'] ?>
</table>
<br>
<p style="color:brown;">Сообщение отправлено почтовым роботом<br>
    системы умного дома <strong>"Домовенок"</strong></p>