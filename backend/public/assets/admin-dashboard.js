const chartCanvas = document.getElementById('service-trend-chart');
const chartDataElement = document.getElementById('dashboard-chart-data');
const chartDetail = document.getElementById('service-trend-detail');

if (chartCanvas && chartDataElement) {
  const chartData = JSON.parse(chartDataElement.textContent);
  const context = chartCanvas.getContext('2d');
  let chartBars = [];
  let selectedIndex = null;

  const showDetail = (index) => {
    selectedIndex = index;
    if (chartDetail) {
      chartDetail.hidden = false;
      chartDetail.querySelector('[data-chart-period]').textContent = chartData.labels[index];
      chartDetail.querySelector('[data-chart-complaints]').textContent = chartData.complaints[index];
    }
    drawChart();
  };

  const drawChart = () => {
    const ratio = window.devicePixelRatio || 1;
    const width = chartCanvas.parentElement.clientWidth;
    const height = Math.min(330, Math.max(240, width * 0.36));
    chartCanvas.width = width * ratio;
    chartCanvas.height = height * ratio;
    chartCanvas.style.width = `${width}px`;
    chartCanvas.style.height = `${height}px`;
    context.setTransform(ratio, 0, 0, ratio, 0, 0);
    context.clearRect(0, 0, width, height);
    chartBars = [];

    const padding = { top: 24, right: 24, bottom: 44, left: 44 };
    const plotWidth = width - padding.left - padding.right;
    const plotHeight = height - padding.top - padding.bottom;
    const maximum = Math.max(5, ...chartData.complaints);
    const gridMaximum = Math.ceil(maximum / 5) * 5;

    context.font = '12px DM Sans, sans-serif';
    context.textAlign = 'right';
    context.textBaseline = 'middle';
    for (let index = 0; index <= 5; index += 1) {
      const value = (gridMaximum / 5) * index;
      const y = padding.top + plotHeight - (plotHeight * index / 5);
      context.strokeStyle = '#dce3f0';
      context.lineWidth = 1;
      context.beginPath();
      context.moveTo(padding.left, y);
      context.lineTo(width - padding.right, y);
      context.stroke();
      context.fillStyle = '#61708d';
      context.fillText(String(value), padding.left - 9, y);
    }

    const slotWidth = plotWidth / chartData.labels.length;
    const barWidth = Math.min(68, slotWidth * 0.56);
    chartData.labels.forEach((label, index) => {
      const value = chartData.complaints[index];
      const x = padding.left + slotWidth * index + (slotWidth - barWidth) / 2;
      const barHeight = value / gridMaximum * plotHeight;
      const y = padding.top + plotHeight - barHeight;
      chartBars.push({ x, y, width: barWidth, height: Math.max(barHeight, 3), index });

      const gradient = context.createLinearGradient(0, y, 0, padding.top + plotHeight);
      gradient.addColorStop(0, selectedIndex === index ? '#ffc700' : '#1c5aa6');
      gradient.addColorStop(1, selectedIndex === index ? '#d4a500' : '#123b72');
      context.fillStyle = gradient;
      context.beginPath();
      context.roundRect(x, y, barWidth, Math.max(barHeight, 3), [8, 8, 2, 2]);
      context.fill();

      context.fillStyle = '#17264b';
      context.font = '700 12px DM Sans, sans-serif';
      context.textAlign = 'center';
      context.textBaseline = 'bottom';
      context.fillText(String(value), x + barWidth / 2, y - 7);
      context.fillStyle = selectedIndex === index ? '#17264b' : '#61708d';
      context.textBaseline = 'top';
      context.fillText(label, x + barWidth / 2, height - padding.bottom + 14);
    });
  };

  const findBar = (event) => {
    const bounds = chartCanvas.getBoundingClientRect();
    const x = event.clientX - bounds.left;
    const y = event.clientY - bounds.top;
    return chartBars.find((bar) => x >= bar.x - 7 && x <= bar.x + bar.width + 7 && y >= bar.y - 18 && y <= bar.y + bar.height + 8);
  };

  chartCanvas.addEventListener('pointermove', (event) => {
    chartCanvas.style.cursor = findBar(event) ? 'pointer' : 'default';
  });
  chartCanvas.addEventListener('pointerleave', () => { chartCanvas.style.cursor = 'default'; });
  chartCanvas.addEventListener('click', (event) => {
    const bar = findBar(event);
    if (bar) showDetail(bar.index);
  });
  chartCanvas.addEventListener('keydown', (event) => {
    if (event.key !== 'Enter' && event.key !== ' ') return;
    event.preventDefault();
    showDetail(selectedIndex ?? chartData.labels.length - 1);
  });

  drawChart();
  window.addEventListener('resize', drawChart, { passive: true });
}
