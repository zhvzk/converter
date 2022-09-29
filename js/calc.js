//конвертер валют. в функцию передается два параметра: action и target
//action - какое действие соврешено (изменение данных первой (основной) валюты или второй (котируемой))
//target - что изменилось (input или select)

function currencyConverter(action, target) {
    //получение элементов формы по id
    let firstCurrencyInput = document.getElementById("firstCurrencyValue"); //input основной валюты
    let firstCurrencyCodeSelect = document.getElementById("firstCurrencyCode"); //select основной валюты
    let secondCurrencyInput = document.getElementById("secondCurrencyValue"); //input котируемой валюты
    let secondCurrencyCodeSelect = document.getElementById("secondCurrencyCode"); //select котируемой валюты

    //переменные, хранящие данные о валютах
    let firstCurrencyValue = firstCurrencyInput.value; //количество основной валюты
    let firstCurrencyId = firstCurrencyCodeSelect.value; //id основной валюты
    let secondCurrencyValue = secondCurrencyInput.value; //количество котируемой валюты
    let secondCurrencyId = secondCurrencyCodeSelect.value; //id котируемой валюты

    $.ajax({
        url: '../php/currencyConverter.php',
        method: 'GET',
        type: 'text',
        data: {
            'action': action,
            'target': target,
            'firstCurrencyValue': firstCurrencyValue,
            'firstCurrencyId': firstCurrencyId,
            'secondCurrencyValue': secondCurrencyValue,
            'secondCurrencyId': secondCurrencyId
        },
        success: function (response) {
            //если изменились данные основной валюты
            if (action === "firstCurrencyChanged") {
                secondCurrencyValue = response; //перезаписывается котируемая валюта
                secondCurrencyInput.value = secondCurrencyValue; //значение второго инпута равно котируемой валюте
            }
            //если изменились данные котируемой валюты
            if (action === "secondCurrencyChanged") {
                //если изменилась валюта
                if (target === "select") {
                    secondCurrencyValue = response; //перезаписывается котируемая валюта
                    secondCurrencyInput.value = secondCurrencyValue; //во второй инпут записывается новое значение котируемой валюты
                }
                //если изменилось значение инпута
                else {
                    firstCurrencyValue = response; //изменяется значение основной валюты
                    firstCurrencyInput.value = firstCurrencyValue; //в первый инпут записывается новое значение основной валюты
                }
            }
        }

    })
}
