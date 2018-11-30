<?php
const SECRET = '';
const GROUP_ID = 0;
const CONFIRMATION_TOKEN = '';
const API_KEY = '';
const DB_DSN = 'mysql:host=localhost;dbname=inue';
const DB_USER = 'root';
const DB_PASSWORD = 'something';
const DB_OPTIONS = [];
const BOT_MESSAGES = [
    'default' => 'Привет!',
    'conf-info' => 'Информация о конференции...',
    'road-map' => 'Карта проезда...',
    'registration.full-name' => 'Для регистрации введите ФИО',
    'registration.age' => 'Введите ваш возраст',
    'registration.city' => 'Из какого вы города?',
    'registration.church' => 'Из какой церкви?',
    'registration.resettlement' => 'Требуется вам расселение?',
    'registration.pay-instructions' => 'Теперь вы можете оплатить регистрацию, прикрепив платеж к сообщению',
    'registration.success' => 'Спасибо, вы зарегистрированы! В течении дня адмисистратор проверит ваш платеж.',
    'registration.wrong-message' => 'Пожалуйста, прикрепите платеж к сообщению',
    'registration.already-registered' => 'Вы уже зарегистрировались',
    'registration.already-started' => 'Процесс регистрации уже запущен',
    'error.user-not-found' => 'пользователь не найден',
    'error.full-name' => 'ФИО задано не верно',
    'error.age' => 'не верный возраст',
    'error.city' => 'город задан не верно',
    'error.church' => 'церковь задана не верно',
    'error.resettlement' => 'расселение задано не верно',
    'error.paid-not-enough' => 'произведен не достаточный платеж, не хватает еще %s руб.',
    'admin-error.failed-accept-payment' => 'Невозможно принять платеж для пользователя %s оплачено %f из %f',
];
const REG_PRICE = 250.00;