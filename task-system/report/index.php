<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
Loader::includeModule("highloadblock");

$APPLICATION->SetTitle("Таск система");

$hlblock = HL\HighloadBlockTable::getById(2)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$date = new DateTime();
$date1 = new DateTime('+1 month');
$firstMonth = date_parse($date->format('d.m.Y'));
$firstMonth['day'] = '01';
$firstMonth = ''.$firstMonth['day'].'.' . $firstMonth['month'].'.'.$firstMonth['year'];
$lastMonth = date_parse($date1->format('d.m.Y'));
$lastMonth['day'] = '01';
$lastMonth = ''.$lastMonth['day'].'.' . $lastMonth['month'].'.'.$lastMonth['year'];

$res = $entity_data_class::getList([ // запрос на данные из HighloadBlock отчет
    'select' => ['*'],
    'filter' => [
        'UF_ID_USER'   => $USER->GetID(), // id пользователя
        ">=UF_BEGINNING_THE_TASK" => ConvertDateTime($firstMonth, "DD.MM.YYYY") . " 00:00:00", // диапазон времени
        "<=UF_BEGINNING_THE_TASK" => ConvertDateTime($lastMonth, "DD.MM.YYYY") . " 00:00:00",
    ]
]);
?>
<table class="table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">#</th>
        <th scope="col">Фамилия</th>
        <th scope="col">Имя</th>
        <th scope="col">Отчество</th>
        <th scope="col">Заголовок задачи</th>
        <th scope="col">Вид работы</th>
        <th scope="col">Потраченное время на задачу</th>
        <th scope="col">Общая стоимость</th>
        <th scope="col">Начало задачи</th>
        <th scope="col">Конец задачи</th>
    </tr>
    </thead>
    <tbody>
    <?
    $i = 1;
    $time = 0;
    $money = 0;
    while ($row = $res->fetch()){
        $time = $time + $row['UF_SPENT_TIME'];
        $money = $money + $row['UF_COST'];
    ?>
    <tr>
        <th scope="row"><?=$i++?></th>
        <td><?=$row['UF_SURNAME']?></td>
        <td><?=$row['UF_NAME']?></td>
        <td><?=$row['UF_PATRONYMIC']?></td>
        <td><?=$row['UF_TASK_HEADER']?></td>
        <td><?=$row['UF_TYPE_OF_WORK']?></td>
        <td><?=$row['UF_SPENT_TIME']?> мин.</td>
        <td><?=$row['UF_COST']?> р.</td>
        <td><?=$row['UF_BEGINNING_THE_TASK']->format('d.m.Y')?></td>
        <td><?=$row['UF_END_OF_THE_PROBLEM']->format('d.m.Y')?></td>
    </tr>
    <?}?>
    </tbody>
</table>
<div class="h4">Общее затраченное время на задачи в этом месяце: <?=$time?> мин.</div>
<div class="h4">ЗП за этот месяц: <?=$money?> р.</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
