const form = document.getElementById("form-plat");
const sectionForm = document.getElementById("section-form");
const btnAjouter = document.getElementById("btn-show-form");
const btnAnnuler = document.getElementById("btn-cancel-form");
const tbody = document.getElementById("table-plats-body");
const btnEnregistrer = document.getElementById("enregister");
const api = "http://localhost:8000/backend/api/api.php";

function loading(isLoading) {
  btnEnregistrer.disabled = isLoading;
  btnEnregistrer.textContent = isLoading ? "En cours ..." : "Enregistrer";
}

function toast(message, type = "success") {
  const toastContainer = document.getElementById("toast-container");
  const toastElement = document.createElement("div");
  const color = type === "success" ? "bg-green-500" : "bg-red-500";

  toastElement.className = `${color} px-5 py-4 text-white transition-opacity duration-500`;
  toastElement.textContent = message;
  toastContainer.appendChild(toastElement);
  setTimeout(() => {
    toastElement.style.opacity = 0;
    setTimeout(() => toastElement.remove(), 500);
  }, 3000);
}

async function afficherPlats() {
  const response = await fetch(api);
  const plats = await response.json();

  if (!response.ok) {
    throw new Error(plats.message || "Erreur lors du chargement des plats");
  }

  tbody.replaceChildren();
  plats.forEach((plat) => {
    const row = document.createElement("tr");
    [plat.id, plat.name, plat.description].forEach((value) => {
      const cell = document.createElement("td");
      cell.className = "px-4 py-3";
      cell.textContent = value;
      row.appendChild(cell);
    });
    tbody.appendChild(row);
  });
}

document.addEventListener("DOMContentLoaded", () => {
  afficherPlats().catch((error) => {
    console.error(error);
    toast(error.message, "error");
  });

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    const name = document.getElementById("name").value.trim();
    if ([...name].length < 3) {
      toast("Le nom doit contenir au moins 3 caractères.", "error");
      return;
    }

    loading(true);

    try {
      const response = await fetch(api, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          name,
          description: document.getElementById("description").value,
        }),
      });
      const data = await response.json();

      if (!response.ok || !data.success) {
        throw new Error(data.message || "Erreur serveur");
      }

      toast(data.message);
      form.reset();
      await afficherPlats();
    } catch (error) {
      console.error(error);
      toast(error.message, "error");
    } finally {
      loading(false);
    }
  });
});

btnAjouter.addEventListener("click", () => sectionForm.classList.remove("hidden"));
btnAnnuler.addEventListener("click", () => {
  sectionForm.classList.add("hidden");
  form.reset();
});