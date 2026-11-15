const api = "http://localhost:8000/backend/api/api.php";
const form = document.getElementById("form-plat");
const tbody = document.getElementById("plats");

function toast(message, type = "success") {
  const div = document.createElement("div");
  div.className = `p-3 text-white rounded ${type === "success" ? "bg-green-500" : "bg-red-500"}`;
  div.textContent = message;
  document.getElementById("toast-container").appendChild(div);
  setTimeout(() => div.remove(), 3000);
}

document.getElementById("btn-ajouter").onclick = () => form.classList.toggle("hidden");

async function afficher() {
  try {
    const response = await fetch(api);
    const plats = await response.json();
    if (!response.ok) throw new Error(plats.message || "Erreur de chargement");

    tbody.innerHTML = "";
    plats.forEach(p => {
      const tr = document.createElement("tr");
      [p.id, p.name, p.description].forEach(v => {
        const td = document.createElement("td");
        td.className = "p-3 border-t";
        td.textContent = v;
        tr.appendChild(td);
      });
      tbody.appendChild(tr);
    });
  } catch (e) {
    toast(e.message, "error");
  }
}

form.onsubmit = async e => {
  e.preventDefault();
  try {
    const response = await fetch(api, {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({
        name: document.getElementById("name").value,
        description: document.getElementById("description").value
      })
    });
    const result = await response.json();
    if (!response.ok || !result.success) throw new Error(result.message || "Erreur");

    toast(result.message);
    form.reset();
    form.classList.add("hidden");
    afficher();
  } catch (e) {
    toast(e.message, "error");
  }
};

afficher();
