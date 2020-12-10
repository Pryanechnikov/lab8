<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$dateFirst = ConvertDateTime($arResult['~ACTIVE_FROM'], "dd.mm.YYYY", "ru");
$dateLast = ConvertDateTime($arResult['~ACTIVE_TO'], "dd.mm.YYYY", "ru");
$getUser = CUser::GetByID($USER->GetID());
$foreachGetUser = $getUser->Fetch();
?>
<div class="news-detail card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-sm-between">
            <div class="alert alert-primary" role="alert" style="width: 33%">
                Начало создание задачи:
                <?= $dateFirst ?>
            </div>
            <div class="alert alert-danger" role="alert" style="width: 33%">
                Время окончания задачи:
                <?= $dateLast ?>
            </div>
            <div class="alert alert-info" role="alert" style="width: 33%">
                Вид работы:
                <?= $arResult['PROPERTIES']['WORKS']['~VALUE'] ?>
            </div>
        </div>
        <div class="d-flex justify-content-sm-between">
            <select class="custom-select executor" style="width: 33%; height: 50px">
                <option value="0">Выбрать исполнителя</option>
                <?
                $order = ['sort' => 'login ']; //переменная сортировка по логину
                $tmp = 'sort'; // переменная для включения сортировки
                $rsUsers = CUser::GetList($order, $tmp);
                while ($arUser = $rsUsers->Fetch()) {
                    ?>
                    <option <? if ($arResult['PROPERTIES']['EXECUTOR']['VALUE'] == $arUser['ID']) { ?> selected <?
                    } ?> value="<?= $arUser['ID'] ?>"><?= $arUser['LAST_NAME'] . ' ' . $arUser['NAME'] . ' ' . $arUser['SECOND_NAME'] ?> </option>
                    <?
                }
                ?>
            </select>
            <div class="alert alert-secondary" role="alert" style="width: 33%">
                Стоимость работы за час:
                <?=$arResult['PROPERTIES']['WORTH']['VALUE']?>р
            </div>
            <div class="alert alert-warning time-value" role="alert" style="width: 33%">
                Потраченное время:
                <?=$arResult['PROPERTIES']['TIME']['VALUE']?>
            </div>
        </div>
        <div class="d-flex justify-content-sm-between" style="margin-bottom: 16px">
            <select class="custom-select work-status" style="width: 33%; height: 50px">
                <option <? if ($arResult['PROPERTIES']['STATUS']['~VALUE'] == 'На рассмотрении') { ?> selected <?}?> value="5">На рассмотрении</option>
                <option <? if ($arResult['PROPERTIES']['STATUS']['~VALUE'] == 'В работе') { ?> selected <?}?> value="6">В работе</option>
                <option <? if ($arResult['PROPERTIES']['STATUS']['~VALUE'] == 'Готово') { ?> selected <?}?> value="7">Готово</option>
            </select>
            <div class="input-group input-group-time" style="width: 66.5%">
                <input  class="form-control time" oninput="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Добавить время в минутах">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary add-time" type="button">Добавить время</button>
                </div>
            </div>
        </div>
        <div class="alert alert-dark w-100" role="alert">
            Описание задачи:
            <?=$arResult['~DETAIL_TEXT']?>
        </div>
        <button class="btn btn-success btn-save">Сохранить</button>
    </div>
</div>
<script>
    $(document).ready(function () {
        function disableTime() {
            if ($('.executor').val() === '0' || $('.work-status').val() === '5' || $('.work-status').val() === '7'){
                $('.input-group-time').addClass('disabled-input')
            }
        }
        disableTime()
        $('.add-time').click(function () {
            $.ajax({
                method: "POST",
                url: "<?=SITE_TEMPLATE_PATH?>/components/bitrix/news/news/bitrix/news.detail/.default/add-time.php",
                data: {
                    id: '<?=$arResult['ID']?>',
                    userID: $('.executor').val(),
                    time: $('.time').val(),
                    COST: '<?=$arResult['PROPERTIES']['WORTH']['VALUE']?>'
                },
                success: function (data) {
                    $('.time-value').text('Потраченное время: ' + data)
                }
            })
        })
        $('.btn-save').click(function () {
            $.ajax({
                method: "POST",
                url: "<?=SITE_TEMPLATE_PATH?>/components/bitrix/news/news/bitrix/news.detail/.default/save-task.php",
                data: {
                    id: '<?=$arResult['ID']?>',
                    userID: $('.executor').val(),
                    SURNAME: '<?=$foreachGetUser['LAST_NAME']?>',
                    NAME: '<?=$foreachGetUser['NAME']?>',
                    PATRONYMIC: '<?=$foreachGetUser['SECOND_NAME']?>',
                    TASK_HEADER: '<?=$arResult['NAME']?>',
                    TYPE_OF_WORK: '<?= $arResult['PROPERTIES']['WORKS']['~VALUE'] ?>',
                    BEGINNING_THE_TASK: '<?= $dateFirst ?>',
                    END_OF_THE_PROBLEM: '<?= $dateLast ?>',
                    executor: $('.executor').val(),
                    workStatus: $('.work-status').val()
                },
                success: function () {
                    //document.location.reload()
                }
            })
        })
    })
</script>