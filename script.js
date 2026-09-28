const legacyServiceHeader = document.querySelector('.inner-page .page-header');
if (legacyServiceHeader) {
  legacyServiceHeader.outerHTML = '<header class="site-header"><div class="container nav-wrap"><a class="brand" href="index.html"><span class="brand-mark">BHP</span><span class="brand-text"><strong>Balai Harta Peninggalan</strong><small>Medan</small></span></a><button class="menu-toggle" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="main-nav"><span></span><span></span><span></span></button><nav class="main-nav" id="main-nav"><a href="index.html#beranda">Beranda</a><a class="active" aria-current="page" href="index.html#layanan">Layanan</a><a href="index.html#tentang">Tentang Kami</a><a href="index.html#kontak">Kontak</a></nav></div></header>';
}

const menuButton = document.querySelector('.menu-toggle');
const navigation = document.querySelector('.main-nav');

if (document.body.classList.contains('inner-page') && !document.querySelector('.service-back-wrap')) {
  const serviceHeader = document.querySelector('.site-header');
  const isStaffSurvey = window.location.pathname.toLowerCase().includes('survei-petugas');
  const fallbackUrl = isStaffSurvey ? 'survei.html' : 'index.html';
  const backLabel = isStaffSurvey ? 'Kembali ke pilihan survei' : 'Kembali';
  const backWrap = document.createElement('div');
  backWrap.className = 'container service-back-wrap';
  backWrap.innerHTML = `<a class="service-back-button" href="${fallbackUrl}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m10.7 17.3-1.4 1.4L2.6 12l6.7-6.7 1.4 1.4L6.4 11H21v2H6.4l4.3 4.3Z"></path></svg><span>${backLabel}</span></a>`;
  serviceHeader?.insertAdjacentElement('afterend', backWrap);

  backWrap.querySelector('a')?.addEventListener('click', (event) => {
    if (window.history.length > 1) {
      event.preventDefault();
      window.history.back();
    }
  });
}

menuButton?.addEventListener('click', () => {
  const isOpen = navigation.classList.toggle('open');
  menuButton.setAttribute('aria-expanded', String(isOpen));
  menuButton.setAttribute('aria-label', isOpen ? 'Tutup menu' : 'Buka menu');
});

document.querySelectorAll('.main-nav a').forEach((link) => {
  link.addEventListener('click', () => {
    navigation?.classList.remove('open');
    menuButton?.setAttribute('aria-expanded', 'false');
  });
});

// Tautan dokumen masih menggunakan placeholder sampai URL resmi ditambahkan.
document.querySelectorAll('.document-button[href="#"]').forEach((link) => {
  link.addEventListener('click', (event) => {
    event.preventDefault();
  });
});

document.querySelectorAll('.channel-button[href="#"]').forEach((link) => {
  link.addEventListener('click', (event) => {
    event.preventDefault();
  });
});

document.querySelectorAll('.external-survey[href="#"]').forEach((link) => {
  link.addEventListener('click', (event) => event.preventDefault());
});

// Letakkan tiga kontak setelah seluruh tombol dokumen.
const informationContacts = document.querySelector('.contact-links');
const informationPanel = document.querySelector('.information-panel');
if (informationContacts && informationPanel && !informationContacts.closest('.contact-panel')) {
  informationContacts.classList.add('information-contacts');
  informationPanel.appendChild(informationContacts);
}

const surveyGroups = [
  { title: 'Kompetensi Pegawai', questions: [
    'Pegawai memiliki pengetahuan tentang tupoksi BHP yang cukup dalam memberikan pelayanan kepada masyarakat.',
    'Pegawai selalu memperbarui pengetahuannya terkait layanan yang diberikan.'
  ]},
  { title: 'Sikap dan Etika Kerja', questions: [
    'Pegawai selalu bersikap ramah dan sopan dalam melayani masyarakat.',
    'Pegawai mampu menjaga profesionalisme dalam setiap situasi pelayanan.',
    'Pegawai menerima kritik dan saran dari pelanggan dengan baik.'
  ]},
  { title: 'Kecepatan dan Ketepatan Pelayanan', questions: [
    'Pegawai tidak membiarkan pelanggan menunggu terlalu lama tanpa kepastian.',
    'Pegawai memberikan informasi yang akurat dan sesuai dengan kebutuhan pelanggan.',
    'Pegawai menyelesaikan pelayanan dengan tuntas tanpa menimbulkan kebingungan bagi pelanggan.'
  ]},
  { title: 'Kedisiplinan Pegawai', questions: [
    'Pegawai selalu berpakaian rapi dan bersih saat memberikan pelayanan.',
    'Pegawai hadir tepat waktu sesuai jadwal pelayanan.',
    'Pegawai tetap menjaga kerapihan tempat pelayanan.'
  ]},
  { title: 'Kemampuan Memberi Solusi', questions: [
    'Pegawai dapat memahami permasalahan pelanggan dengan baik.',
    'Pegawai mampu memberikan solusi yang tepat sesuai dengan kebutuhan pelanggan.',
    'Pegawai menunjukkan kreativitas dalam menyelesaikan masalah pelanggan.'
  ]}
];

// Ganti nama, inisial, dan elemen foto setelah data petugas resmi tersedia.
const staffMembers = [
  { id: 'petugas-1', name: 'Nama Petugas 1', initials: 'P1' },
  { id: 'petugas-2', name: 'Nama Petugas 2', initials: 'P2' },
  { id: 'petugas-3', name: 'Nama Petugas 3', initials: 'P3' }
];

const staffList = document.getElementById('staff-list');
const ratingSections = document.getElementById('rating-sections');
if (staffList && ratingSections) {
  staffList.innerHTML = staffMembers.map((staff) => `<div class="staff-choice"><input type="radio" name="petugas" id="${staff.id}" value="${staff.name}" required><label for="${staff.id}"><span class="staff-photo" aria-hidden="true">${staff.initials}</span><span><strong>${staff.name}</strong><small>Petugas pelayanan</small></span></label></div>`).join('');
  let questionIndex = 0;
  ratingSections.innerHTML = surveyGroups.map((group, groupIndex) => {
    const rows = group.questions.map((question) => {
      questionIndex += 1;
      const stars = [5, 4, 3, 2, 1].map((score) => `<input type="radio" id="q${questionIndex}-${score}" name="q${questionIndex}" value="${score}" required><label for="q${questionIndex}-${score}" title="${score} bintang" aria-label="${score} bintang">★</label>`).join('');
      return `<div class="rating-row"><p>${question}</p><div class="stars">${stars}</div></div>`;
    }).join('');
    return `<section class="rating-group"><div class="rating-group-heading"><span>${String(groupIndex + 1).padStart(2, '0')}</span><h3>${group.title}</h3></div>${rows}</section>`;
  }).join('');
}

const surveyTrigger = document.querySelector('.survey-form-trigger');
const staffSurvey = document.getElementById('staff-survey-form');
const formMessage = document.getElementById('form-message');
if (surveyTrigger && staffSurvey) {
  surveyTrigger.addEventListener('click', () => {
    const willOpen = staffSurvey.hidden;
    staffSurvey.hidden = !willOpen;
    surveyTrigger.setAttribute('aria-expanded', String(willOpen));
    if (willOpen) staffSurvey.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });

}

if (staffSurvey && formMessage) {
  staffSurvey.addEventListener('submit', (event) => {
    event.preventDefault();
    if (!staffSurvey.checkValidity()) {
      formMessage.textContent = 'Mohon pilih petugas dan beri nilai pada seluruh pernyataan.';
      formMessage.className = 'form-message error';
      staffSurvey.reportValidity();
      return;
    }
    formMessage.textContent = 'Terima kasih. Penilaian Anda sudah lengkap dan siap dikirim ke sistem.';
    formMessage.className = 'form-message success';
  });
}

const revealItems = document.querySelectorAll('.service-hero-copy, .service-hero-symbol, .information-heading, .information-channels, .information-section-title, .document-button, .contact-panel, .service-card, .survey-option, .staff-choice, .rating-scale, .rating-group, .form-field, .form-actions, .about-visual, .about-copy > *');
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (revealItems.length && !reduceMotion && 'IntersectionObserver' in window) {
  document.documentElement.classList.add('reveal-enabled');
  revealItems.forEach((item, index) => {
    item.classList.add('reveal-item');
    item.style.setProperty('--reveal-delay', ((index % 4) * 70) + 'ms');
  });

  const revealObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      entry.target.classList.add('reveal-visible');
      observer.unobserve(entry.target);
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -30px' });

  revealItems.forEach((item) => revealObserver.observe(item));
}

const yearElement = document.getElementById('year');
const unavailableTestCard = document.querySelector('.service-card.green');
if (unavailableTestCard) {
  unavailableTestCard.removeAttribute('href');
  unavailableTestCard.setAttribute('aria-disabled', 'true');
  unavailableTestCard.setAttribute('tabindex', '-1');
  const unavailableLabel = unavailableTestCard.querySelector('.card-link');
  if (unavailableLabel) unavailableLabel.textContent = 'Segera hadir';
}
if (yearElement) yearElement.textContent = new Date().getFullYear();
if (!document.body.classList.contains('inner-page')) {
  const sections = [...document.querySelectorAll('main section[id], header[id]')];
  const navLinks = [...document.querySelectorAll('.main-nav a')];

  const updateHomeNavigation = () => {
    const currentSection = sections.reduce((active, section) => {
      return window.scrollY >= section.offsetTop - 140 ? section.id : active;
    }, 'beranda');

    navLinks.forEach((link) => {
      const target = new URL(link.href, window.location.href).hash.slice(1);
      const isActive = target === currentSection || (currentSection === 'informasi' && target === 'layanan');
      link.classList.toggle('active', isActive);
      if (isActive) link.setAttribute('aria-current', 'page');
      else link.removeAttribute('aria-current');
    });
  };

  updateHomeNavigation();
  window.addEventListener('scroll', updateHomeNavigation, { passive: true });
}
