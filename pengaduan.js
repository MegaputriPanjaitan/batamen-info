const complaintForm = document.getElementById('complaint-form');
const complaintMessage = document.getElementById('complaint-message');
const evidenceInput = document.getElementById('evidence');

complaintForm.addEventListener('submit', (event) => {
  event.preventDefault();
  const file = evidenceInput.files[0];
  if (file && file.size > 10 * 1024 * 1024) {
    complaintMessage.textContent = 'Ukuran lampiran melebihi batas maksimal 10 MB.';
    complaintMessage.className = 'form-message error';
    evidenceInput.focus();
    return;
  }
  if (!complaintForm.checkValidity()) {
    complaintMessage.textContent = 'Mohon lengkapi seluruh kolom dengan benar.';
    complaintMessage.className = 'form-message error';
    complaintForm.reportValidity();
    return;
  }
  complaintMessage.textContent = 'Data pengaduan sudah lengkap dan siap dikirim ke sistem.';
  complaintMessage.className = 'form-message success';
});
