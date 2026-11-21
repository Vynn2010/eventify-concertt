(function () {
  var methods = document.querySelectorAll(".payment-input");
  var payBtn = document.getElementById("payBtn");
  if (!payBtn || methods.length === 0) return;

  function update() {
    var dipilih = Array.prototype.some.call(methods, function (m) {
      return m.checked;
    });
    payBtn.disabled = !dipilih;

    Array.prototype.forEach.call(methods, function (m) {
      var icon = m.closest("label").querySelector(".check-icon");
      icon.classList.toggle("hidden", !m.checked);
    });
  }

  Array.prototype.forEach.call(methods, function (m) {
    m.addEventListener("change", update);
  });
  update();
})();
