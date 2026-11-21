(function () {
  var form = document.getElementById("cancel-form");
  var button = document.getElementById("cancel-btn");
  var selectAll = document.getElementById("select-all");
  if (!form || !button) return;

  var boxes = form.querySelectorAll(".ticket-check");

  function checkedCount() {
    return form.querySelectorAll(".ticket-check:checked").length;
  }

  function refresh() {
    var n = checkedCount();
    button.disabled = n === 0;
    button.textContent = n ? "Cancel Tickets (" + n + ")" : "Cancel Tickets";
    if (selectAll) selectAll.checked = n === boxes.length && boxes.length > 0;
  }

  boxes.forEach(function (box) {
    box.addEventListener("change", refresh);
  });

  if (selectAll) {
    selectAll.addEventListener("change", function () {
      boxes.forEach(function (box) {
        box.checked = selectAll.checked;
      });
      refresh();
    });
  }

  form.addEventListener("submit", function (e) {
    var n = checkedCount();
    if (
      n === 0 ||
      !confirm(
        "Batalkan " +
          n +
          " tiket yang dipilih? Kursinya akan dikembalikan dan tindakan ini tidak bisa dibatalkan.",
      )
    ) {
      e.preventDefault();
    }
  });

  refresh();
})();
