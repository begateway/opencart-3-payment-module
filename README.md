## OpenCart 3 payment module

[Русская версия](#Модуль-оплаты-opencart-3)

### Installation

* Backup your webstore and database
* Upload the module file [opencart-3-begateway-payment-module.ocmod.zip](https://github.com/begateway/opencart-3-payment-module/raw/master/opencart-3-begateway-payment-module.ocmod.zip) via _Extensions_ -> _Extension Installer_
* Activate the module in payment extensions (_Extensions_ -> _Payments_)
* Configure the module settings:
  * Shop Id
  * Shop secret key
  * Payment page domain
  * Order statuses for successfuly processed payment and for failed one
  * Enabled the module
  * And optionally setup sort order id if you want to move the payment
    option higher level in payment method list

### Notes

Tested and developed with OpenCart v.3.0.2.0

### Troubleshooting

If you hosting service doesn't provide a FTP access, most probably you
will have to install [the extension](http://www.opencart.com/index.php?route=extension/extension/info&extension_id=18892) before to install the payment module.

Alternatively you can just upload the _upload_ directory content to your opencart
installation directory.

### Demo credentials

You are free to use the settings to configure the module to process
payments with a demo gateway.

  * Shop Id __361__
  * Shop secret key __b8647b68898b084b836474ed8d61ffe117c9a01168d867f24953b776ddcb134d__
  * Payment page domain __checkout.begateway.com__

Use the test data to make a test payment:

  * card number __4200000000000000__
  * card name __John Doe__
  * card expiry month __01__ to get a success payment
  * card expiry month __10__ to get a failed payment
  * CVC __123__

### Contributing

Issue pull requests or send feature requests or open [a new issue]( https://github.com/begateway/opencart-3-payment-module/issues/new)

## Модуль оплаты OpenCart 3

[English version](#opencart-3-payment-module)

### Установка

* Создайте резервную копию вашего магазина и базы данных
* Загрузите файл модуля [opencart-3-begateway-payment-module.ocmod.zip](https://github.com/begateway/opencart-3-payment-module/raw/master/opencart-3-begateway-payment-module.ocmod.zip) с помощью _Модули_ -> _Установка расширений_
* Активируйте модуль BeGateway в модулях оплаты (_Модули_ -> _Оплата_)
* Задайте в настройках модуля BeGateway:
  * Id магазина
  * Ключ магазина
  * Домен страницы оплаты
  * Статусы заказа в случае успешной и не успешной оплаты
  * Включите модуль
  * Опционально задайте идентификатор модуля для сортировки его в списке способов оплаты. Меньшее значение подымает модуль в верх списка

### Примечания

Разработано и протестировано с OpenCart v.3.0.2.0

### Проблемы при установке

Если ваша хостинговая компания не предоставляет FTP доступ, то вам будет необходимо установить
[этот модуль](http://www.opencart.com/index.php?route=extension/extension/info&extension_id=18892) прежде чем устанавливать данный модуль оплаты.

Другой вариант - это закачать на сервер содержимое папки _upload_ в корень директoрии, где устанвлена OpenCart

### Тестовые данные

Вы можете использовать следующие данные, чтобы настроить способ оплаты в
тестовом режиме:

  * Идентификационный номер магазина __361__
  * Секретный ключ магазина __b8647b68898b084b836474ed8d61ffe117c9a01168d867f24953b776ddcb134d__
  * Домен платежной страницы __checkout.begateway.com__

Используйте следующий тестовый набор для тестового платежа:

  * номер карты __4200000000000000__
  * имя на карте __John Doe__
  * месяц срока действия карты __01__, чтобы получить успешный платеж
  * месяц срока действия карты __10__, чтобы получить неуспешный платеж
  * CVC __123__

## Нашли ошибку или у вас есть предложение по улучшению модуля?

Создайте [запрос](https://github.com/begateway/opencart-3-payment-module/issues/new)
