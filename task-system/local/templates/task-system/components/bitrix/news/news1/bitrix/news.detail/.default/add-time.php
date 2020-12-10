<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); // подключение ядра битрикс
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
Loader::includeModule("highloadblock");

if (CModule::IncludeModule("iblock")) {
    $db_props = CIBlockElement::GetProperty('1', $_POST['id'], array("sort" => "asc"), Array("CODE"=>"TIME"));
    while ($ob = $db_props->GetNext())
    {
        $time = $ob['VALUE'] + $_POST['time'];
        $ELEMENT_ID = $_POST['id'];
        $PROPERTY_CODE = "TIME";
        $PROPERTY_VALUE = $time;
        CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, array($PROPERTY_CODE => $PROPERTY_VALUE));
        echo $time;
    }
}

$hlblock = HL\HighloadBlockTable::getById(2)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$res = $entity_data_class::getList(array(
    'select' => ['*'],
    'filter' => [
        'UF_ID_USER'   => $_POST['userID'],
        'UF_TASK_IDENTIFIER'   => $_POST['id'],
    ]
));

while ($row = $res->fetch()) {
    $time = $row['UF_SPENT_TIME'] + $_POST['time'];
    $money = $_POST['COST'] / 60;
    $summMoney = $money * $time;

    $data = array(
        "UF_SPENT_TIME"=>$time,
        "UF_COST"=>$summMoney,
    );
    $entity_data_class::update($row['ID'], $data);
}