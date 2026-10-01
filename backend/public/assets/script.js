const menuButton = document.querySelector('.menu-toggle');
const navigation = document.querySelector('.main-nav');

const closeNavigation = () => {
  navigation?.classList.remove('open');
  menuButton?.setAttribute('aria-expanded', 'false');
  menuButton?.setAttribute('aria-label', 'Buka menu');
};

document.querySelectorAll('[data-logo-fallback]').forEach((logo) => {
  logo.addEventListener('error', () => {
    const fallback = logo.dataset.logoFallback;
    if (fallback && logo.src !== fallback) logo.src = fallback;
  }, { once: true });
});

menuButton?.addEventListener('click', () => {
  const isOpen = navigation.classList.toggle('open');
  menuButton.setAttribute('aria-expanded', String(isOpen));
  menuButton.setAttribute('aria-label', isOpen ? 'Tutup menu' : 'Buka menu');
});

document.querySelectorAll('.main-nav a').forEach((link) => {
  link.addEventListener('click', () => {
    closeNavigation();
  });
});

document.addEventListener('pointerdown', (event) => {
  if (!navigation?.classList.contains('open')) return;
  if (navigation.contains(event.target) || menuButton?.contains(event.target)) return;
  closeNavigation();
}, true);

document.addEventListener('keydown', (event) => {
  if (event.key !== 'Escape' || !navigation?.classList.contains('open')) return;
  closeNavigation();
  menuButton?.focus();
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

// WebView Android tidak selalu membuat jendela baru untuk target="_blank".
// Serahkan tautan eksternal kepada browser bawaan ketika halaman berjalan di Capacitor.
if (window.Capacitor?.isNativePlatform?.()) {
  document.querySelectorAll('a[target="_blank"][href^="http"]').forEach((link) => {
    link.addEventListener('click', async (event) => {
      event.preventDefault();

      const inAppBrowser = window.Capacitor?.Plugins?.Browser;
      if (link.matches('.document-button, .in-app-browser-link') && inAppBrowser?.open) {
        await inAppBrowser.open({
          url: link.href,
          presentationStyle: 'popover',
        });
        return;
      }

      window.location.assign(link.href);
    });
  });
}

const revealItems = document.querySelectorAll('.service-hero-copy, .service-hero-symbol, .information-heading, .information-channels, .information-section-title, .document-button, .service-card, .survey-option, .staff-choice, .rating-scale, .rating-group, .form-field, .form-actions, .about-visual, .about-copy > *');
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const pullToRefresh = document.createElement('div');
pullToRefresh.className = 'pull-to-refresh';
pullToRefresh.setAttribute('aria-hidden', 'true');
pullToRefresh.innerHTML = '<span>↻</span><small>Tarik untuk refresh</small>';
document.body.prepend(pullToRefresh);

let pullStartY = 0;
let pullDistance = 0;
let canPullToRefresh = false;

document.addEventListener('touchstart', (event) => {
  canPullToRefresh = window.scrollY <= 0 && event.touches.length === 1;
  pullStartY = canPullToRefresh ? event.touches[0].clientY : 0;
  pullDistance = 0;
}, { passive: true });

document.addEventListener('touchmove', (event) => {
  if (!canPullToRefresh || event.touches.length !== 1) return;
  pullDistance = Math.max(0, Math.min(120, (event.touches[0].clientY - pullStartY) * 0.55));
  pullToRefresh.style.setProperty('--pull-distance', `${pullDistance}px`);
  pullToRefresh.classList.toggle('is-pulling', pullDistance > 4);
  pullToRefresh.classList.toggle('is-ready', pullDistance >= 72);
  pullToRefresh.querySelector('small').textContent = pullDistance >= 72 ? 'Lepaskan untuk refresh' : 'Tarik untuk refresh';
}, { passive: true });

document.addEventListener('touchend', () => {
  if (!canPullToRefresh) return;
  const shouldRefresh = pullDistance >= 72;
  canPullToRefresh = false;

  if (shouldRefresh) {
    pullToRefresh.classList.add('is-refreshing');
    pullToRefresh.querySelector('small').textContent = 'Memuat ulang…';
    window.setTimeout(() => window.location.reload(), 180);
    return;
  }

  pullToRefresh.style.setProperty('--pull-distance', '0px');
  pullToRefresh.classList.remove('is-pulling', 'is-ready');
}, { passive: true });

const successToast = document.querySelector('[data-success-toast]');
if (successToast) {
  window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
  requestAnimationFrame(() => {
    successToast.classList.add('is-visible');
    successToast.focus({ preventScroll: true });
  });

  const redirectUrl = successToast.dataset.successRedirectUrl;
  const closeSuccessToast = () => {
    if (redirectUrl) window.location.assign(redirectUrl);
    else successToast.classList.remove('is-visible');
  };
  successToast.querySelector('[data-toast-close]')?.addEventListener('click', closeSuccessToast);
  window.setTimeout(closeSuccessToast, redirectUrl ? 3500 : 10000);
}

const staffSurveyForm = document.querySelector('#staff-survey-form');
if (staffSurveyForm) {
  const contextDialog = staffSurveyForm.querySelector('[data-survey-context-dialog]');
  const contextContinueButton = staffSurveyForm.querySelector('[data-survey-context-continue]');
  const contextError = staffSurveyForm.querySelector('[data-survey-context-error]');
  const phoneInput = staffSurveyForm.querySelector('input[name="respondent_phone"]');
  const serviceSelect = staffSurveyForm.querySelector('select[name="service_slug"]');
  const selectedServiceLabel = staffSurveyForm.querySelector('[data-selected-service]');
  const emptyServiceMessage = staffSurveyForm.querySelector('[data-staff-service-empty]');
  const staffStep = staffSurveyForm.querySelector('[data-survey-step="staff"]');
  const questionStep = staffSurveyForm.querySelector('[data-survey-step="questions"]');
  const backButton = staffSurveyForm.querySelector('[data-survey-back]');
  const selectedStaffLabel = staffSurveyForm.querySelector('[data-selected-staff]');
  const confirmDialog = staffSurveyForm.querySelector('[data-staff-confirm-dialog]');
  const confirmStaffLabel = staffSurveyForm.querySelector('[data-confirm-staff]');
  const confirmStaffNip = staffSurveyForm.querySelector('[data-confirm-staff-nip]');
  const confirmStaffPhoto = staffSurveyForm.querySelector('[data-confirm-staff-photo]');
  const confirmStaffButton = staffSurveyForm.querySelector('[data-confirm-staff-survey]');
  const staffOptions = [...staffSurveyForm.querySelectorAll('input[name="staff_member_id"]')];
  const staffChoices = [...staffSurveyForm.querySelectorAll('.staff-choice')];
  const availabilityUrl = staffSurveyForm.dataset.availabilityUrl;
  let contextConfirmed = false;

  const normalizedPhone = () => {
    let phone = (phoneInput?.value || '').replace(/\D+/g, '');
    if (phone.startsWith('62')) phone = `0${phone.slice(2)}`;
    else if (phone.startsWith('8')) phone = `0${phone}`;
    return phone;
  };

  const applyServiceFilter = () => {
    const serviceSlug = serviceSelect?.value || '';
    let visibleStaffCount = 0;
    staffChoices.forEach((choice) => {
      let services = [];
      try { services = JSON.parse(choice.dataset.services || '[]'); } catch (_) { services = []; }
      const isVisible = services.includes(serviceSlug);
      choice.hidden = !isVisible;
      const option = choice.querySelector('input[name="staff_member_id"]');
      if (!isVisible && option) option.checked = false;
      if (isVisible) visibleStaffCount += 1;
    });
    if (emptyServiceMessage) emptyServiceMessage.hidden = visibleStaffCount > 0;
    if (selectedServiceLabel) selectedServiceLabel.textContent = serviceSelect?.selectedOptions?.[0]?.textContent?.trim() || 'belum dipilih';
  };

  const applyRatedStaff = (ratedStaffIds) => {
    const ratedIds = new Set(ratedStaffIds.map((id) => String(id)));
    staffChoices.forEach((choice) => {
      const option = choice.querySelector('input[name="staff_member_id"]');
      const unavailableMessage = choice.querySelector('[data-staff-unavailable]');
      const isUnavailable = option && ratedIds.has(option.value);
      if (option) {
        option.disabled = Boolean(isUnavailable);
        if (isUnavailable) option.checked = false;
      }
      choice.classList.toggle('is-unavailable', Boolean(isUnavailable));
      if (unavailableMessage) unavailableMessage.hidden = true;
    });
  };

  const confirmSurveyContext = async () => {
    const phone = normalizedPhone();
    if (!/^08[0-9]{8,13}$/.test(phone)) {
      if (contextError) {
        contextError.textContent = 'Masukkan nomor HP yang valid, misalnya 081234567890.';
        contextError.hidden = false;
      }
      phoneInput?.focus();
      return;
    }
    if (!serviceSelect?.value) {
      if (contextError) {
        contextError.textContent = 'Pilih jenis layanan yang Anda terima.';
        contextError.hidden = false;
      }
      serviceSelect?.focus();
      return;
    }
    if (phoneInput) phoneInput.value = phone;
    if (contextError) contextError.hidden = true;

    contextContinueButton.disabled = true;
    const originalButtonText = contextContinueButton.textContent;
    contextContinueButton.textContent = 'Memeriksa...';
    try {
      const response = await fetch(availabilityUrl, {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': staffSurveyForm.querySelector('input[name="_token"]')?.value || '',
        },
        body: JSON.stringify({ respondent_phone: phone, service_slug: serviceSelect.value }),
      });
      const result = await response.json();
      if (!response.ok) throw new Error(result.message || 'Data tidak dapat diperiksa.');
      applyRatedStaff(result.rated_staff_ids || []);
    } catch (error) {
      if (contextError) {
        contextError.textContent = error.message || 'Terjadi kendala saat memeriksa data. Silakan coba lagi.';
        contextError.hidden = false;
      }
      return;
    } finally {
      contextContinueButton.disabled = false;
      contextContinueButton.textContent = originalButtonText;
    }

    contextConfirmed = true;
    applyServiceFilter();
    staffStep.hidden = false;
    questionStep.hidden = true;
    contextDialog?.close();
    window.scrollTo({ top: staffSurveyForm.offsetTop - 92, behavior: reduceMotion ? 'auto' : 'smooth' });
  };

  const selectedStaff = () => staffOptions.find((option) => option.checked);
  const updateSelectedStaff = () => {
    const selected = selectedStaff();
    if (!selected) return;
    const staffName = selected.labels?.[0]?.querySelector('strong')?.textContent?.trim() || 'petugas terpilih';
    if (selectedStaffLabel) selectedStaffLabel.textContent = staffName;
    if (confirmStaffLabel) confirmStaffLabel.textContent = staffName;
    if (confirmStaffNip) confirmStaffNip.textContent = selected.labels?.[0]?.querySelector('small')?.textContent?.trim() || '';
    const photo = selected.labels?.[0]?.querySelector('.staff-photo');
    if (confirmStaffPhoto) confirmStaffPhoto.replaceChildren(...(photo ? [photo.cloneNode(true)] : []));
  };
  const showSurveyStep = (step, updateHistory = true) => {
    const showQuestions = contextConfirmed && step === 'questions' && selectedStaff();
    staffStep.hidden = !contextConfirmed || Boolean(showQuestions);
    questionStep.hidden = !showQuestions;
    updateSelectedStaff();
    if (updateHistory) {
      const url = showQuestions ? '#pertanyaan' : `${window.location.pathname}${window.location.search}`;
      window.history.pushState({ surveyStep: showQuestions ? 'questions' : 'staff' }, '', url);
    }
    window.scrollTo({ top: staffSurveyForm.offsetTop - 92, behavior: reduceMotion ? 'auto' : 'smooth' });
  };

  contextContinueButton?.addEventListener('click', confirmSurveyContext);
  contextDialog?.addEventListener('cancel', (event) => event.preventDefault());
  contextDialog?.addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
      event.preventDefault();
      confirmSurveyContext();
    }
  });
  if (contextDialog?.hasAttribute('data-open-on-load')) contextDialog.showModal();

  const openStaffConfirmation = () => {
    updateSelectedStaff();
    if (!selectedStaff()) return;
    if (confirmDialog?.showModal) confirmDialog.showModal();
    else if (window.confirm(`Apakah Anda ingin menilai ${confirmStaffLabel?.textContent || 'petugas ini'}?`)) showSurveyStep('questions');
  };
  const cancelStaffConfirmation = () => {
    confirmDialog?.close();
    const selected = selectedStaff();
    if (selected) selected.checked = false;
    updateSelectedStaff();
  };

  staffOptions.forEach((option) => option.addEventListener('change', openStaffConfirmation));
  staffChoices.forEach((choice) => choice.addEventListener('click', (event) => {
    if (!choice.classList.contains('is-unavailable')) return;
    event.preventDefault();
    staffChoices.forEach((otherChoice) => {
      const otherMessage = otherChoice.querySelector('[data-staff-unavailable]');
      if (otherMessage) otherMessage.hidden = otherChoice !== choice;
    });
    const message = choice.querySelector('[data-staff-unavailable]');
    if (message) message.hidden = false;
    message?.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'nearest' });
  }));
  confirmStaffButton?.addEventListener('click', () => {
    confirmDialog?.close();
    showSurveyStep('questions');
  });
  confirmDialog?.querySelectorAll('[data-dialog-close]').forEach((button) => button.addEventListener('click', cancelStaffConfirmation));
  confirmDialog?.addEventListener('click', (event) => {
    if (event.target === confirmDialog) cancelStaffConfirmation();
  });
  confirmDialog?.addEventListener('cancel', (event) => {
    event.preventDefault();
    cancelStaffConfirmation();
  });
  backButton?.addEventListener('click', () => showSurveyStep('staff'));
  window.addEventListener('popstate', () => showSurveyStep(window.location.hash === '#pertanyaan' ? 'questions' : 'staff', false));
  updateSelectedStaff();
}

const internalSurveyDialog = document.querySelector('[data-internal-survey-dialog]');
if (internalSurveyDialog) {
  const openInternalSurveyDialog = () => {
    if (!internalSurveyDialog.open) internalSurveyDialog.showModal();
  };

  document.querySelectorAll('[data-internal-survey-open]').forEach((button) => button.addEventListener('click', openInternalSurveyDialog));
  internalSurveyDialog.querySelectorAll('[data-dialog-close]').forEach((button) => button.addEventListener('click', () => internalSurveyDialog.close()));
  internalSurveyDialog.addEventListener('click', (event) => {
    if (event.target === internalSurveyDialog) internalSurveyDialog.close();
  });
  if (internalSurveyDialog.hasAttribute('data-open-on-load')) openInternalSurveyDialog();
}

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
