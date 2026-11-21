(function () {
  var searchInput = document.getElementById("searchInput");
  var noResult = document.getElementById("noResult");
  if (!searchInput) return;

  searchInput.addEventListener("input", function () {
    var q = this.value.toLowerCase().trim();
    var totalTampil = 0;

    document.querySelectorAll(".event-section").forEach(function (section) {
      var tampil = 0;
      section.querySelectorAll(".concert-card").forEach(function (card) {
        var cocok = card.dataset.search.includes(q);
        card.style.display = cocok ? "" : "none";
        if (cocok) tampil++;
      });
      // sembunyikan section yang kartunya habis tersaring
      section.style.display = tampil === 0 && q !== "" ? "none" : "";
      totalTampil += tampil;
    });

    noResult.classList.toggle("hidden", totalTampil > 0 || q === "");
  });
})();
