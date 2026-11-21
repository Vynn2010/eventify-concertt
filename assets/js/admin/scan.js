(function () {
  var page = document.getElementById("scan-page");
  var endpoint = page.getAttribute("data-endpoint");
  var resultBox = document.getElementById("result");
  var historyList = document.getElementById("history");
  var filter = document.getElementById("concert-filter");
  var startBtn = document.getElementById("start-btn");
  var stopBtn = document.getElementById("stop-btn");
  var manualForm = document.getElementById("manual-form");
  var manualInput = document.getElementById("manual-token");

  var scanner = null,
    running = false,
    busy = false;
  var lastText = "",
    lastTime = 0;
  var history = [];

  var styles = {
    ok: {
      box: "bg-green-100 text-green-900",
      title: "Ticket valid - entry allowed",
    },
    used: { box: "bg-amber-100 text-amber-900", title: "Ticket already used" },
    wrong_concert: {
      box: "bg-amber-100 text-amber-900",
      title: "Wrong concert",
    },
    invalid: { box: "bg-red-100 text-red-900", title: "Invalid ticket" },
    error: { box: "bg-red-100 text-red-900", title: "Error" },
  };

  // QR bisa berisi token saja, atau URL yang memuat token
  function extractToken(text) {
    text = String(text).trim();
    try {
      var url = new URL(text);
      return (
        url.searchParams.get("token") ||
        url.searchParams.get("qr_token") ||
        url.pathname.split("/").filter(Boolean).pop() ||
        text
      );
    } catch (e) {
      return text;
    }
  }

  function line(parent, label, value) {
    var p = document.createElement("p");
    p.className = "text-sm mt-1";
    var b = document.createElement("span");
    b.className = "font-semibold";
    b.textContent = label + ": ";
    p.appendChild(b);
    p.appendChild(document.createTextNode(value));
    parent.appendChild(p);
  }

  function showResult(data) {
    var st = styles[data.result] || styles.error;
    resultBox.className = "rounded-2xl p-6 " + st.box;
    resultBox.textContent = "";

    var title = document.createElement("p");
    title.className = "text-xl font-bold";
    title.textContent = st.title;
    resultBox.appendChild(title);

    var msg = document.createElement("p");
    msg.className = "text-sm mt-1";
    msg.textContent = data.message || "";
    resultBox.appendChild(msg);

    if (data.ticket) {
      var box = document.createElement("div");
      box.className = "mt-4 pt-3 border-t border-current/20";
      line(box, "Ticket", data.ticket.code);
      line(box, "Concert", data.ticket.concert);
      line(box, "Seat", data.ticket.seat);
      line(box, "Customer", data.ticket.email);
      resultBox.appendChild(box);
    }
  }

  function addHistory(data) {
    var now = new Date();
    var time = now.toLocaleTimeString("id-ID", {
      hour: "2-digit",
      minute: "2-digit",
      second: "2-digit",
    });
    history.unshift({
      time: time,
      code: data.ticket ? data.ticket.code : "-",
      result: (styles[data.result] || styles.error).title,
    });
    history = history.slice(0, 8);

    historyList.textContent = "";
    history.forEach(function (h) {
      var li = document.createElement("li");
      li.className =
        "flex justify-between gap-3 border-b border-[#17143D]/10 pb-2 last:border-0";
      var left = document.createElement("span");
      left.textContent = h.time + "  " + h.code;
      var right = document.createElement("span");
      right.className = "opacity-70 text-right";
      right.textContent = h.result;
      li.appendChild(left);
      li.appendChild(right);
      historyList.appendChild(li);
    });
  }

  function check(token) {
    if (busy || !token) return;
    busy = true;

    var body = new FormData();
    body.append("token", token);
    body.append("concert_id", filter.value);

    fetch(endpoint, { method: "POST", body: body })
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        showResult(data);
        addHistory(data);
      })
      .catch(function () {
        var data = {
          result: "error",
          message:
            "Gagal menghubungi server atau sesi admin berakhir. Muat ulang halaman.",
        };
        showResult(data);
      })
      .finally(function () {
        busy = false;
      });
  }

  function onScan(text) {
    // Abaikan QR yang sama dalam 4 detik supaya tidak diproses berulang
    var now = Date.now();
    if (text === lastText && now - lastTime < 4000) return;
    lastText = text;
    lastTime = now;
    check(extractToken(text));
  }

  function toggleButtons() {
    startBtn.disabled = running;
    stopBtn.disabled = !running;
  }

  startBtn.addEventListener("click", function () {
    if (running) return;
    scanner = new Html5Qrcode("reader");
    scanner
      .start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 240, height: 240 } },
        onScan,
        function () {},
      )
      .then(function () {
        running = true;
        toggleButtons();
      })
      .catch(function () {
        showResult({
          result: "error",
          message:
            "Kamera tidak bisa dibuka. Izinkan akses kamera, atau pakai input token manual.",
        });
      });
  });

  stopBtn.addEventListener("click", function () {
    if (!running || !scanner) return;
    scanner.stop().then(function () {
      scanner.clear();
      running = false;
      toggleButtons();
    });
  });

  manualForm.addEventListener("submit", function (e) {
    e.preventDefault();
    var token = extractToken(manualInput.value);
    if (token) {
      check(token);
      manualInput.value = "";
    }
  });

  toggleButtons();
})();
