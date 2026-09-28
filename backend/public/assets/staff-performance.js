const monthlyElement = document.getElementById('staff-monthly-data');
const categoryElement = document.getElementById('staff-category-data');
const trendCanvas = document.getElementById('staff-trend-chart');
const categoryCanvas = document.getElementById('staff-category-chart');

const prepareCanvas = (canvas, preferredHeight = 290) => {
  const ratio = window.devicePixelRatio || 1;
  const width = canvas.parentElement.clientWidth;
  const height = Math.min(preferredHeight, Math.max(245, width * 0.55));
  canvas.width = width * ratio;
  canvas.height = height * ratio;
  canvas.style.width = `${width}px`;
  canvas.style.height = `${height}px`;
  const context = canvas.getContext('2d');
  context.setTransform(ratio, 0, 0, ratio, 0, 0);
  context.clearRect(0, 0, width, height);
  return { context, width, height };
};

const drawScale = (context, width, height, padding) => {
  const plotHeight = height - padding.top - padding.bottom;
  for (let value = 0; value <= 5; value += 1) {
    const y = padding.top + plotHeight - (plotHeight * value / 5);
    context.strokeStyle = '#dce3f0';
    context.lineWidth = 1;
    context.beginPath();
    context.moveTo(padding.left, y);
    context.lineTo(width - padding.right, y);
    context.stroke();
    context.fillStyle = '#61708d';
    context.font = '11px DM Sans, sans-serif';
    context.textAlign = 'right';
    context.textBaseline = 'middle';
    context.fillText(String(value), padding.left - 9, y);
  }
};

const drawCharts = () => {
  if (trendCanvas && monthlyElement) {
    const data = JSON.parse(monthlyElement.textContent);
    const { context, width, height } = prepareCanvas(trendCanvas);
    const padding = { top: 20, right: 22, bottom: 45, left: 36 };
    const plotWidth = width - padding.left - padding.right;
    const plotHeight = height - padding.top - padding.bottom;
    const step = plotWidth / Math.max(1, data.labels.length - 1);
    drawScale(context, width, height, padding);

    context.strokeStyle = '#1c5aa6';
    context.lineWidth = 3;
    context.lineJoin = 'round';
    context.beginPath();
    data.averages.forEach((average, index) => {
      const x = padding.left + step * index;
      const y = padding.top + plotHeight - (average / 5 * plotHeight);
      if (index === 0) context.moveTo(x, y); else context.lineTo(x, y);
    });
    context.stroke();

    data.averages.forEach((average, index) => {
      const x = padding.left + step * index;
      const y = padding.top + plotHeight - (average / 5 * plotHeight);
      context.fillStyle = '#ffcb05';
      context.strokeStyle = '#1b2c5d';
      context.lineWidth = 2;
      context.beginPath();
      context.arc(x, y, 5, 0, Math.PI * 2);
      context.fill();
      context.stroke();
      context.fillStyle = '#61708d';
      context.font = '10px DM Sans, sans-serif';
      context.textAlign = 'center';
      context.textBaseline = 'top';
      context.fillText(data.labels[index], x, height - padding.bottom + 14);
    });
  }

  if (categoryCanvas && categoryElement) {
    const data = JSON.parse(categoryElement.textContent);
    const { context, width, height } = prepareCanvas(categoryCanvas, 330);
    const labelWidth = Math.min(175, width * 0.42);
    const left = labelWidth + 12;
    const availableWidth = width - left - 38;
    const rowHeight = (height - 24) / data.labels.length;

    data.labels.forEach((label, index) => {
      const y = 14 + index * rowHeight;
      const barY = y + 24;
      context.fillStyle = '#17264b';
      context.font = '11px DM Sans, sans-serif';
      context.textAlign = 'right';
      context.textBaseline = 'middle';
      const shortLabel = label.length > 25 ? `${label.slice(0, 24)}…` : label;
      context.fillText(shortLabel, labelWidth, barY + 7);
      context.fillStyle = '#e8edf5';
      context.fillRect(left, barY, availableWidth, 14);
      context.fillStyle = index % 2 === 0 ? '#1c5aa6' : '#ffcb05';
      context.fillRect(left, barY, availableWidth * data.averages[index] / 5, 14);
      context.fillStyle = '#1b2c5d';
      context.font = '700 11px DM Sans, sans-serif';
      context.textAlign = 'left';
      context.fillText(data.averages[index].toFixed(2), left + availableWidth + 7, barY + 7);
    });
  }
};

drawCharts();
window.addEventListener('resize', drawCharts, { passive: true });
