<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
global $USER; // глобальная переменная

/**
 * Распечатывает массивы
 * @param $var
 * @param int $mode
 * @param string $str
 * @param int $die
 */
function gg($var, $mode = 0, $str = 'Var', $die = 0)
{
    switch ($mode) {
        case 0:
            echo "<pre>";
            echo "######### {$str} ##########<br/>";
            print_r($var);
            echo "</pre>";
            if ($die) {
                die();
            }
            break;
        case 2:
            $handle = fopen($_SERVER["DOCUMENT_ROOT"] . "/upload/debug.txt", "a+");
            fwrite($handle, "######### {$str} ##########\n");
            fwrite($handle, (string)$var);
            fwrite($handle, "\n\n\n");

            fclose($handle);
            break;
    }
}

?>
<?
if (!$USER->IsAuthorized()) { // метод передает авторизован ли пользователь
    if ($APPLICATION->GetCurPage() !== '/login/') { // проверка если пользователь не находится на странице
        LocalRedirect('/login/'); // перенапривить на страницу
    }
}
?>
<!DOCTYPE html>
<html lang="<?= LANGUAGE_ID; ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <? $APPLICATION->ShowHead(); ?>
    <link href="<?= SITE_TEMPLATE_PATH ?>/assets/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
    <script src="<?= SITE_TEMPLATE_PATH ?> /assets/js/jquery-3.5.1.min.js"></script>
    <script src="<?= SITE_TEMPLATE_PATH ?> /assets/js/bootstrap.min.js"></script>
    <title><? $APPLICATION->ShowTitle() ?></title>
</head>
<body>
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
<? if ($APPLICATION->GetCurPage() !== '/login/'): //если пользователь не находится на странице то не включать блок шапки?>
    <header class="border-bottom p-md-3 mb-5">
        <div class="container d-flex justify-content-between">
            <div>
                <a class="btn btn-outline-dark" href="/">Главная</a>
                <a class="btn btn-outline-secondary" href="/my-tasks/">Мои задачи</a>
                <a class="btn btn-outline-secondary" href="/report/">Отчет</a>
            </div>
            <a class="btn btn-danger" href="/?logout=yes">выход</a>
        </div>
    </header>
<? endif; ?>
<div id="page-wrapper" class="container">