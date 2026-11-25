
const chatWindow = document.getElementById('companyChatMessages');
const chatForm = document.getElementById('companyChatForm');

console.log('CHAT WINDOW:', chatWindow);

// Подключение к Centrifugo
const centrifuge = new Centrifuge(
    'ws://localhost:8000/connection/websocket'
);

centrifuge.on('connected', function(ctx) {
    console.log('CENTRIFUGO CONNECTED:', ctx);
});

centrifuge.on('disconnected', function(ctx) {
    console.log('CENTRIFUGO DISCONNECTED:', ctx);
});

// Подписка на канал компании
const sub = centrifuge.newSubscription(String(activeCompanyId));

// Отслеживаем успешную подписку
sub.on('subscribed', function(ctx) {
    console.log('CENTRIFUGO SUBSCRIBED:', ctx);

    // Запрашиваем историю только после подписки
    loadChatHistory();
});

sub.on('error', function(ctx) {
    console.error('CENTRIFUGO SUBSCRIPTION ERROR:', ctx);
});

// Загрузка истории
async function loadChatHistory() {
    const formData = new FormData();
    formData.append('action_id', 'get-history-chat');

    try {
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData
        });

        const responseText = await response.text();

        console.log('CHAT HISTORY RESPONSE:', response.status, responseText);

    } catch (error) {
        console.error('CHAT HISTORY ERROR:', error);
    }
}

// Отправка сообщения через AJAX
chatForm.addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(chatForm);

    try {
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData
        });

        const responseText = await response.text();

        console.log('SERVER RESPONSE:', responseText);

        // Не разбираем пустой ответ как JSON
        if (!responseText.trim()) {
            console.warn('Сервер вернул пустой ответ');
            return;
        }

        const data = JSON.parse(responseText);

        if (data.status === 'ok') {
            chatForm.reset();
        }

    } catch (error) {
        console.error('Ошибка отправки сообщения:', error);
    }
});

// Отправка по Enter
const chatTextarea = chatForm.querySelector('textarea');

chatTextarea.addEventListener('keydown', function(e) {
    if (e.key !== 'Enter') return;

    e.preventDefault();
    chatForm.requestSubmit();
});

// Получение публикаций
sub.on('publication', function(ctx) {
    console.log('CENTRIFUGO PUBLICATION:', ctx.data);

    if (!chatWindow) return;
    if (typeof ctx.data.text !== 'string') return;

    const messages = ctx.data.text.split('\n');

    chatWindow.replaceChildren();

    messages.forEach(function(message) {
        if (!message.trim()) return;

        const separator = message.indexOf(' ');

        const author = separator === -1
            ? message
            : message.slice(0, separator);

        const text = separator === -1
            ? ''
            : message.slice(separator + 1);

        const messageElement = document.createElement('div');
        messageElement.classList.add('chat-message');

        const authorElement = document.createElement('div');
        authorElement.classList.add('chat-message__author');
        authorElement.textContent = author;

        const textElement = document.createElement('div');
        textElement.classList.add('chat-message__text');
        textElement.textContent = text;

        messageElement.append(authorElement, textElement);
        chatWindow.appendChild(messageElement);
    });

    chatWindow.scrollTop = chatWindow.scrollHeight;
});

// Запуск
sub.subscribe();
centrifuge.connect();
