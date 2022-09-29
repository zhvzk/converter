<?php
//создание xml объекта
$xmlUrl = 'https://www.cbr-xml-daily.ru/daily_utf8.xml';
$xml = simplexml_load_file($xmlUrl);

//запись полученных параметров в переменные

$action = $_GET['action']; //какое действие соврешено (изменение данных первой (основной) валюты или второй (котируемой))
$target = $_GET['target']; //что изменилось (input или select)

if (!empty($_GET['firstCurrencyId'])) //если GET параметр не пустой
    $firstCurrencyId = $_GET['firstCurrencyId']; //id основной валюты
if (!empty($_GET['firstCurrencyValue']))
    $firstCurrencyValue = $_GET['firstCurrencyValue']; //количество основной валюты
if (!empty($_GET['firstCurrencyId']))
    $secondCurrencyId = $_GET['secondCurrencyId']; //id котируемой валюты
if (!empty($_GET['secondCurrencyValue']))
    $secondCurrencyValue = $_GET['secondCurrencyValue']; //количество котируемой валюты

//если основная и котируемая равны
if ($firstCurrencyId == $secondCurrencyId) {
    if ($action == "firstCurrencyChanged") echo $firstCurrencyValue;
    if ($action == "secondCurrencyChanged") echo $secondCurrencyValue;
}
//если основная и котируемая не равны
else {
    //перебор всех валют внутри xml
    foreach ($xml as $currency) {
        foreach ($currency->attributes() as $attribute => $value) { //перебор атрибутов каждой валюты
            if (($attribute == "ID") & ($value == $firstCurrencyId)) { //если атрибут id и он равен id основной валюты
                $firstCurrencyDenomination = $currency->Nominal; //номинал основной валюты
                $firstCurrencyRate = str_replace(",", ".", $currency->Value); //курс к рублю (, заменяются на .)
            } elseif (($attribute == "ID") & ($value == $secondCurrencyId)) { //атрибут id и он равен id котируемой валюты
                $secondCurrencyDenomination = $currency->Nominal; //номинал котируемой валюты
                $secondCurrencyRate = str_replace(",", ".", $currency->Value); //курс к рублю (, заменяются на .)
            }
        }
        //если изменились данные первой валюты
        if ($action == "firstCurrencyChanged") {
            @$result = $firstCurrencyValue * ($firstCurrencyRate / $firstCurrencyDenomination) / ($secondCurrencyRate / $secondCurrencyDenomination); //конвертация
        }
        //если изменились данные второй валюты
        if ($action == "secondCurrencyChanged") {
            if ($target == "select") { //если изменилась котируемая валюта, пересчитывается второй инпут
                @$result = $firstCurrencyValue * ($firstCurrencyRate / $firstCurrencyDenomination) / ($secondCurrencyRate / $secondCurrencyDenomination);
            }

            if ($target == "input") { //если измениось значение второго инпута, пересчитывается первый
                @$result = $secondCurrencyValue * ($secondCurrencyRate / $secondCurrencyDenomination) / ($firstCurrencyRate / $firstCurrencyDenomination);
            }
        }
    }
    echo round($result, 3); //округление и вывод
}
?>