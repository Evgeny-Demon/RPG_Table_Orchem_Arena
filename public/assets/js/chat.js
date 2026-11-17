

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

sub.on('publication', function(ctx) {
    const chatWindow = document.getElementById('companyChatMessages');

    if (chatWindow) {
        chatWindow.textContent = ctx.data.text;
    }
});

sub.subscribe();


sub.on('publication', function(ctx) {
    console.log('CENTRIFUGO PUBLICATION:', ctx.data);
});
