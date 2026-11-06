(() => {
    const lines = {
        azul: { name: 'Linha Azul', region: 'Sul', times: [['06:00', '1010'], ['07:00', '1534'], ['11:30', '6521']] },
        verde: { name: 'Linha Verde', region: 'Centro', times: [['08:00', '2201'], ['12:00', '4456']] },
        amarela: { name: 'Linha Amarela', region: 'Itaum', times: [['09:30', '7890']] },
        vermelha: { name: 'Linha Vermelha', region: 'Villa Nova', times: [['10:00', '9999']] }
    };
    const dialog = document.getElementById('schedule');
    const times = document.getElementById('schedule-times');
    document.querySelectorAll('[data-line]').forEach(button => {
        button.addEventListener('click', () => {
            const line = lines[button.dataset.line];
            document.getElementById('schedule-title').textContent = line.name;
            document.getElementById('schedule-region').textContent = line.region;
            times.replaceChildren();
            line.times.forEach(([time, train]) => {
                const row = document.createElement('tr');
                [time, 'Trem ' + train].forEach(value => {
                    const cell = document.createElement('td');
                    cell.textContent = value;
                    row.append(cell);
                });
                times.append(row);
            });
            dialog.showModal();
            document.body.classList.add('modal-open');
        });
    });
    dialog.querySelector('.close-dialog').addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', event => {
        const rect = dialog.getBoundingClientRect();
        if (event.target === dialog && (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom)) dialog.close();
    });
    dialog.addEventListener('close', () => document.body.classList.remove('modal-open'));
})();