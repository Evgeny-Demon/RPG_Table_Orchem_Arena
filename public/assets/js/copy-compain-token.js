document.addEventListener('DOMContentLoaded', () => {
    const copyBtn = document.getElementById('copyTokenBtn');
    if (!copyBtn) return;

    copyBtn.addEventListener('click', async () => {
        const originalText = copyBtn.textContent;
        copyBtn.disabled = true;

            const response = await fetch('/get-active-token');
            const data = await response.json();

            await navigator.clipboard.writeText(data.token);
            copyBtn.textContent = 'Скопировано!';
        
            setTimeout(() => {
                copyBtn.textContent = originalText;
                copyBtn.disabled = false;
            }, 1500);
        
    });
});
