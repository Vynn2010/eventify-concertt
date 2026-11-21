const inputs = document.querySelectorAll(".seat-input");
const list = document.getElementById("selectedList");
const emptyText = document.getElementById("emptyText");
const totalSeats = document.getElementById("totalSeats");
const totalPrice = document.getElementById("totalPrice");
const confirmBtn = document.getElementById("confirmBtn");

const rupiah = (n) => "Rp " + new Intl.NumberFormat("id-ID").format(n);

function update() {
  const checked = [...inputs].filter((i) => i.checked);
  let total = 0;

  list.innerHTML = "";
  checked.forEach((i) => {
    const price = parseFloat(i.dataset.price) || 0;
    total += price;

    const li = document.createElement("li");
    li.className = "flex justify-between gap-3";
    const a = document.createElement("span");
    a.textContent = "Kursi " + i.dataset.seat + " · " + i.dataset.category;
    const b = document.createElement("span");
    b.textContent = rupiah(price);
    li.append(a, b);
    list.appendChild(li);
  });

  emptyText.classList.toggle("hidden", checked.length > 0);
  totalSeats.textContent = checked.length;
  totalPrice.textContent = rupiah(total);
  confirmBtn.disabled = checked.length === 0;
}

inputs.forEach((i) => i.addEventListener("change", update));
update();
