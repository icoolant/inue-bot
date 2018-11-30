<?php
/** @var $storage \app\Storage */
$stmt = $storage->getAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список зарегестрированных</title>
</head>
<body>
<table>
    <thead>
        <td>ФИО</td>
        <td>Возраст</td>
        <td>Горд</td>
        <td>Церковь</td>
        <td>Расселение</td>
        <td>Оплачено</td>
        <td>Платеж подтвержден</td>
        <td>Шаг регистрации</td>
    </thead>
    <tbody>
    <?php if ($stmt) { ?>
        <?php /** @var \app\Registration $row */ ?>
        <?php while ($row = $stmt->fetchObject(\app\Registration::class)) {  ?>
        <?php if (!$row->step) continue; ?>
            <tr>
                <td><?=$row->full_name?></td>
                <td><?=$row->age?></td>
                <td><?=$row->city?></td>
                <td><?=$row->church?></td>
                <td><?=$row->resettlement === null ? 'n/a' : (int)$row->resettlement?></td>
                <td><?=$row->paid_in_currency?></td>
                <td><?=$row->paid === null ? 'n/a' : (int)$row->paid?></td>
                <td><?=$row->step?></td>
            </tr>
        <?php } ?>
    <?php }?>
    </tbody>
</table>
</body>
</html>
