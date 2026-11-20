

// Подключение к Centrifugo
const centrifuge = new Centrifuge(
    'ws://localhost:8000/connection/websocket'
);

centrifuge.connect();

centrifuge.on('connected', function(ctx) {
    console.log('CENTRIFUGO CONNECTED:', ctx);
});

// Подписка на канал компании
const sub = centrifuge.newSubscription(String(activeCompanyId));


const chatForm = document.getElementById('companyChatForm');

//загрузка истории
document.addEventListener('DOMContentLoaded', async function(e) {
    const formData = new FormData();
    formData.append('action_id', 'get-history-chat');
    const response = await fetch(window.location.href, {
        method: 'POST',
        body: formData
    });
})

// Отправка сообщения через AJAX
chatForm.addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(chatForm);

    try {
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        console.log(data);

        if (data.status === 'ok') {
            chatForm.reset();
        }

    } catch (error) {
        console.error('Ошибка отправки сообщения:', error);
    }
});

const chatTextarea = chatForm.querySelector('textarea');

chatTextarea.addEventListener('keydown', function(e) {
    if (e.key !== 'Enter') return;

    e.preventDefault();
    chatForm.requestSubmit();
});


sub.on('publication', function(ctx) {
    const chatWindow = document.getElementById('companyChatMessages');

    if (!chatWindow) return;

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


sub.subscribe();


sub.on('publication', function(ctx) {
    console.log('CENTRIFUGO PUBLICATION:', ctx.data);
});
