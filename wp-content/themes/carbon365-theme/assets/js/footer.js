// Footer JS: set current year and basic form accessibility
(function () {
  var yEl = document.getElementById("footer-year");
  if (yEl) yEl.textContent = new Date().getFullYear();

  // Simple client-side email validation on submit (progressive enhancement)
  var form = document.querySelector(".subscribe-form");
  if (form) {
    form.addEventListener("submit", function (e) {
      var email = form.querySelector('input[name="email"]');
      if (email) {
        var val = email.value.trim();
        if (!val || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(val)) {
          e.preventDefault();
          email.focus();
          email.setAttribute("aria-invalid", "true");
        }
      }
    });
  }
})();
