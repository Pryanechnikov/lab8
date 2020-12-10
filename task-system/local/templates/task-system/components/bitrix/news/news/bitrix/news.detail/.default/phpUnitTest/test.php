<?php
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;

Loader::includeModule("highloadblock");


function checkTask($UF_ID_USER, $UF_TASK_IDENTIFIER)
{
    $hlblock = HL\HighloadBlockTable::getById(2)->fetch();
    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();

    $res = $entity_data_class::getList([
        'select' => ['*'],
        'filter' => [
            "UF_ID_USER"         => $UF_ID_USER,
            "UF_TASK_IDENTIFIER" => $UF_TASK_IDENTIFIER
        ]
    ]);

    while ($row = $res->fetch()) {
        if ($row === null) {
            return 'error';
        } else {
            return 'correctly';
        }
    }
}

function checkEXECUTOR($postId)
{
    if (CModule::IncludeModule("iblock")) {
        $db_props = CIBlockElement::GetProperty('1', $postId, array("sort" => "asc"), Array("CODE"=>"STATUS"));
        while ($ob = $db_props->fetch()) {
            return $ob['VALUE'];
        }
    }
}

function checkTaskTime($postId) {
    if (CModule::IncludeModule("iblock")) {
        $db_props = CIBlockElement::GetProperty('1', $postId, array("sort" => "asc"), Array("CODE"=>"TIME"));
        while ($ob = $db_props->fetch()) {
            return $ob['VALUE'];
        }
    }
}

function checkTime($UF_ID_USER, $UF_TASK_IDENTIFIER, $UF_SPENT_TIME, $UF_COST) {
    $hlblock = HL\HighloadBlockTable::getById(2)->fetch();
    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();

    $res = $entity_data_class::getList([
        'select' => ['*'],
        'filter' => [
            "UF_ID_USER"         => $UF_ID_USER,
            "UF_TASK_IDENTIFIER" => $UF_TASK_IDENTIFIER,
            "UF_SPENT_TIME" => $UF_SPENT_TIME,
            "UF_COST" => $UF_COST
        ]
    ]);

    while ($row = $res->fetch()) {
        if ($row === null) {
            return 'error';
        } else {
            return 'correctly';
        }
    }
}