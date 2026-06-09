import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);

// Wedding Budget Chart (simple inline helper)
window.budgetChart = (canvas, data) => {
    if (!canvas || !data) return;

    const ctx = canvas.getContext('2d');
    const { labels, planned, actual, colors } = data;

    const maxVal = Math.max(...planned, ...actual, 1);
    const barW = 30;
    const gap = 20;
    const groupW = barW * 2 + gap;
    const paddingLeft = 60;
    const paddingBottom = 60;
    const chartH = canvas.height - paddingBottom - 20;

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    labels.forEach((label, i) => {
        const x = paddingLeft + i * (groupW + 30);

        // Planned bar
        const plannedH = (planned[i] / maxVal) * chartH;
        ctx.fillStyle = colors[i] + '80';
        ctx.fillRect(x, chartH - plannedH + 20, barW, plannedH);

        // Actual bar
        const actualH = (actual[i] / maxVal) * chartH;
        ctx.fillStyle = colors[i];
        ctx.fillRect(x + barW + 2, chartH - actualH + 20, barW, actualH);

        // Label
        ctx.fillStyle = '#374151';
        ctx.font = '10px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText(label.substring(0, 8), x + barW, chartH + 35);
    });
};

window.Alpine = Alpine;
Alpine.start();
