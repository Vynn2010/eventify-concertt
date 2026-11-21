(function () {
  var cd = document.getElementById("countdown");
  if (!cd) return;

  var target = new Date(cd.dataset.date).getTime();

  function tick() {
    var d = target - Date.now();
    if (d <= 0) {
      cd.textContent = "Sedang berlangsung";
      return;
    }
    var day = Math.floor(d / 864e5);
    var h = Math.floor((d % 864e5) / 36e5);
    var m = Math.floor((d % 36e5) / 6e4);
    var s = Math.floor((d % 6e4) / 1e3);
    cd.textContent = day + "d " + h + "h " + m + "m " + s + "s";
  }

  tick();
  setInterval(tick, 1000);
})();
