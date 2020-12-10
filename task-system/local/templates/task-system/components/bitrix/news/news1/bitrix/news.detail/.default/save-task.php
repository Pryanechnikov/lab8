<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
Loader::includeModule("highloadblock");

if (CModule::IncludeModule("iblock")) {
    $executor = $_POST['executor'];
    $workStatus = $_POST['workStatus'];
    $ELEMENT_ID = $_POST['id'];
    CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, array('EXECUTOR' => $executor, 'STATUS' => $workStatus));
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

$row = $res->fetch();
$user = CUser::GetByID('' . $_POST['userID'] . '');
$userArray = $user->Fetch();

if ($row == null){
    $data = array(
        "UF_ID_USER"=>$_POST['userID'],
        "UF_SURNAME"=>$userArray['LAST_NAME'],
        "UF_NAME"=>$userArray['NAME'],
        "UF_PATRONYMIC"=>$userArray['SECOND_NAME'],
        "UF_TASK_IDENTIFIER"=>$_POST['id'],
        "UF_TASK_HEADER"=>$_POST['TASK_HEADER'],
        "UF_TYPE_OF_WORK"=>$_POST['TYPE_OF_WORK'],
        "UF_BEGINNING_THE_TASK"=>$_POST['BEGINNING_THE_TASK'],
        "UF_END_OF_THE_PROBLEM"=>$_POST['END_OF_THE_PROBLEM'],
    );
    $entity_data_class::add($data);
}else{
    $data = array(
        "UF_ID_USER"=>$_POST['userID'],
        "UF_SURNAME"=>$userArray['LAST_NAME'],
        "UF_NAME"=>$userArray['NAME'],
        "UF_PATRONYMIC"=>$userArray['SECOND_NAME'],
        "UF_TASK_IDENTIFIER"=>$_POST['id'],
        "UF_TASK_HEADER"=>$_POST['TASK_HEADER'],
        "UF_TYPE_OF_WORK"=>$_POST['TYPE_OF_WORK'],
        "UF_BEGINNING_THE_TASK"=>$_POST['BEGINNING_THE_TASK'],
        "UF_END_OF_THE_PROBLEM"=>$_POST['END_OF_THE_PROBLEM'],
    );
    $entity_data_class::update($_POST['id'], $data);
}


