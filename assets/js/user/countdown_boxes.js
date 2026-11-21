(function () {
  var box = document.getElementById("countdown");
  if (!box) return;
  var target = new Date(box.dataset.target).getTime(); 

  function pad(n) {
    return String(n).padStart(2, "0");                                     
  }

  function tick() {
    var diff = Math.max(0, target - Date.now());
    var s = Math.floor(diff / 1000);
    document.getElementById("cd-days").textContent = pad(Math.floor(s / 86400));
    document.getElementById("cd-hours").textContent = pad(
      Math.floor((s % 86400) / 3600),
    );
    document.getElementById("cd-minutes").textContent = pad(
      Math.floor((s % 3600) / 60),
    );
    document.getElementById("cd-seconds").textContent = pad(s % 60);
  }

  tick();
  setInterval(tick, 1000);
})();
