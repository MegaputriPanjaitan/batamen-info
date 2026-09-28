const complaintForm = document.getElementById('complaint-form');
const complaintMessage = document.getElementById('complaint-message');
const evidenceInput = document.getElementById('evidence');

complaintForm?.addEventListener('submit', (event) => {
  const file = evidenceInput.files[0];

  if (file && file.size > 10 * 1024 * 1024) {
    event.preventDefault();
    complaintMessage.textContent = 'Ukuran lampiran melebihi batas maksimal 10 MB.';
    complaintMessage.className = 'form-message error';
    evidenceInput.focus();
  }
});
