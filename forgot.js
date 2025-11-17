const form = document.getElementById('forgotForm');
const message = document.getElementById('successMessage');

form.addEventListener('submit', function (e) {
  e.preventDefault(); // prevent page refresh
  message.style.display = 'block'; // show success message
  form.reset(); // clear input
});
