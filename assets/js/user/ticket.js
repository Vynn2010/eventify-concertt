document.querySelectorAll(".qr").forEach((el) => {
  new QRCode(el, {
    text: el.dataset.token,
    width: 150,
    height: 150,
    correctLevel: QRCode.CorrectLevel.M,
  });
});

async function saveTicket(btn) {
  const area = document.getElementById(btn.dataset.target);
  const teks = btn.textContent;
  btn.disabled = true;
  btn.textContent = "Menyimpan...";
  try {
    const canvas = await html2canvas(area, {
      scale: 2,
      backgroundColor: "#ffffff",
      useCORS: true,
    });
    const a = document.createElement("a");
    a.download = btn.dataset.filename;
    a.href = canvas.toDataURL("image/png");
    a.click();
  } catch (e) {
    alert("Gagal menyimpan tiket. Coba lagi.");
  } finally {
    btn.disabled = false;
    btn.textContent = teks;
  }
}

function icsEscape(s) {
  return String(s)
    .replace(/\\/g, "\\\\")
    .replace(/;/g, "\\;")
    .replace(/,/g, "\\,")
    .replace(/\r?\n/g, "\\n");
}

function addToCalendar(btn) {
  const d = btn.dataset;
  const stamp =
    new Date().toISOString().replace(/[-:]/g, "").split(".")[0] + "Z";
  const ics = [
    "BEGIN:VCALENDAR",
    "VERSION:2.0",
    "PRODID:-//Eventify//Ticket//ID",
    "BEGIN:VEVENT",
    "UID:ticket-" + d.uid + "@eventify",
    "DTSTAMP:" + stamp,
    "DTSTART:" + d.start,
    "DTEND:" + d.end,
    "SUMMARY:" + icsEscape(d.title),
    "LOCATION:" + icsEscape(d.location),
    "DESCRIPTION:" + icsEscape(d.desc),
    "END:VEVENT",
    "END:VCALENDAR",
  ].join("\r\n");

  const blob = new Blob([ics], { type: "text/calendar;charset=utf-8" });
  const a = document.createElement("a");
  a.href = URL.createObjectURL(blob);
  a.download = "konser-" + d.uid + ".ics";
  a.click();
  URL.revokeObjectURL(a.href);
}
