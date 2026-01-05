<h1>Loan API</h1>

<p>Проект <strong>Loan API</strong> представляет собой систему для работы с кредитными заявками. Сервис позволяет:</p>
<ul>
    <li>Создавать новые заявки пользователей.</li>
    <li>Обрабатывать статусы заявок с учетом ограничений (не более одной одобренной заявки на пользователя).</li>
    <li>Использовать очередь Redis для асинхронной обработки задач, при этом поддерживается fallback на синхронное выполнение, если очередь недоступна.</li>
</ul>

<h2>Стек технологий</h2>
<ul>
    <li><strong>PHP 8.2</strong> с использованием <strong>Yii2</strong></li>
    <li><strong>PostgreSQL 16</strong> для хранения данных</li>
    <li><strong>Redis 7</strong> для очередей</li>
    <li><strong>Docker</strong> для контейнеризации сервисов</li>
    <li><strong>Nginx</strong> как веб-сервер</li>
    <li><strong>Composer</strong> для управления зависимостями</li>
</ul>
<h2>Использование</h2>
<p>Команда инициализации проекта:</p>
<pre><code>
make init
</code></pre>

<p>Запуск слушателя очереди для асинхронной обработки заявок:</p>
<pre><code>
make shell-php
php yii queue/listen -v
</code></pre>

<p>Примеры запросов:</p>
<ul>
    <li>POST <code>/requests</code> — создание заявки</li>
    <li>GET <code>/processor</code> — обработка статусов</li>
</ul>

<h2>Структура проекта</h2>
<ul>
    <li><strong>backend/controllers</strong> — контроллеры для работы с заявками.</li>
    <li><strong>common/modules/loan</strong> — модуль заявок, включая модели, репозитории, сервисы, мапперы и задачи очереди.</li>
    <li><strong>common/modules/user</strong> — репозитории для работы с пользователями.</li>
    <li><strong>console</strong> — консольные команды и настройка очереди.</li>
    <li><strong>docker</strong> — Dockerfile и docker-compose.yml для поднятия окружения.</li>
</ul>

<h2>Миграции</h2>
<p>Создана таблица <code>loans</code> с полями:</p>
<ul>
    <li>id — первичный ключ</li>
    <li>user_id — идентификатор пользователя</li>
    <li>amount — сумма займа</li>
    <li>term — срок займа в днях</li>
    <li>status — статус заявки (approved/declined)</li>
    <li>created_at — дата создания</li>
</ul>
<p>Создан уникальный частичный индекс, чтобы ограничить количество одобренных заявок одним на пользователя:</p>
<pre><code>
CREATE UNIQUE INDEX uniq_approved_loan_per_user
ON loans (user_id)
WHERE status = 'approved';
</code></pre>

<h2>Контроллеры</h2>
<p><strong>LoanController</strong> реализует два действия:</p>
<ul>
    <li><code>actionRequests</code> — создание новой заявки</li>
    <li><code>actionProcessor</code> — изменение статусов заявок, с поддержкой очереди</li>
</ul>

<h2>Очереди (Queue)</h2>
<p>Используется компонент <strong>yii-queue</strong> с драйвером Redis:</p>
<ul>
    <li>Задача <code>ProcessUserLoansJob</code> обрабатывает статусы заявок пользователей</li>
    <li>Задача <code>DummyJob</code> используется для проверки работы воркера</li>
</ul>
<p>Если Redis недоступен или очередь не активна, задачи выполняются синхронно, чтобы тесты и обработка не ломались.</p>

<h2>Сервисы</h2>
<p><strong>LoanService</strong> реализует:</p>
<ul>
    <li>Создание заявок с проверкой наличия одобренных заявок</li>
    <li>Добавление задач в очередь или синхронное выполнение</li>
    <li>Логику установки статусов заявок с вероятностью одобрения 10%</li>
</ul>

