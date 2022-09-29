<?
//создание xml объекта
$xmlUrl = 'https://www.cbr-xml-daily.ru/daily_utf8.xml';
$xml = simplexml_load_file($xmlUrl);

//функция создания <option>
function putOption($xml) {
    $count = 0; //счетчик
    foreach ($xml as $currency => $currencyInfo) { //перебор всех валют внутри xml
        echo "<option value='"; //вывод на страницу открывающего тега option с открытым value
        foreach ($xml->$currency[$count]->attributes() as $attribute => $value) { //перебор атрибутов валюты с порядковым номером count
            if ($attribute == "ID") { //если атрибут id (на случай, если в xml появятся дополнительные атрибуты)
                echo $value; //запись id валюты в value тега
            }
        }
        echo "'>"; //закрытие открывающего тега
        echo $currencyInfo->CharCode . " - " . $currencyInfo->Name; //значение внутри option в формате "буквенный код - название валюты"
        echo "</option>"; //закрытие option
        $count++;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500&display=swap" rel="stylesheet">
    <title>Калькулятор курса валют</title>
</head>
<body>
<span class="title">Калькулятор курса валют</span>
<form method="post" id="form">
    <input type="number" name="firstCurrencyValue" id="firstCurrencyValue" oninput="currencyConverter('firstCurrencyChanged', 'input')" class="currencyInput" placeholder="Основная валюта">
    <select name="firstCurrencyCode" id="firstCurrencyCode" onchange="currencyConverter('firstCurrencyChanged', 'select')" class="currencySelect">
        <? putOption($xml); ?>
    </select>
    <br>
    <input type="number" id="secondCurrencyValue" name="secondCurrencyValue" oninput="currencyConverter('secondCurrencyChanged', 'input')" class="currencyInput" placeholder="Котируемая валюта">
    <select name="secondCurrencyCode" id="secondCurrencyCode" onchange="currencyConverter('secondCurrencyChanged', 'select')" class="currencySelect">
        <? putOption($xml); ?>
    </select>
</form>
<script src="https://yastatic.net/jquery/3.3.1/jquery.min.js"></script>
<script src="js/calc.js"></script>
</body>