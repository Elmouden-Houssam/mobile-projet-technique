const form = document.getElementById("form");
const sectionForm = document.getElementById("section-form");
const btnAjouter = document.getElementById("btn-show-form");
const btnAnnuler = document.getElementById("btn-cancel-form");
const tbody = document.getElementById("table-ts-body");
const btnEnregister = document.getElementById("enregister");
const api = "http://localhost:8000/backend/api/api.php";

function Loading(isloading) {
  if (isloading) {
    btnEnregister.disabled = true;
    btnEnregister.textContent = "En cours ...";
  } else {
    btnEnregister.disabled = false;
    btnEnregister.textContent = "Enregistrer";
  }
}

function Toast(message, type = "success") {
  const toastContainer = document.getElementById("toast-container");
  const toast = document.createElement("div");
  const color = type == "success" ? "bg-green-500" : "bg-red-500";

  toast.className = `
        ${color} px-5 py-4 text-white transition-opacity duration-500
    `;

  toast.textContent = `${message}`;
  toastContainer.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = 0;
    setTimeout(() => {
      toast.remove();
    }, 500);
  }, 3000);
}

function list() {
  fetch(api)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Erreur lors du chargement");
      }

      return response.json();
    })
    .then((data) => {
      tbody.innerHTML = "";

      data.forEach((ts) => {
        const tr = document.createElement("tr");

        tr.innerHTML = `
                    <td class="px-4 py-3">${ts.id}</td>
                    <td class="px-4 py-3">${ts.name}</td>
                    <td class="px-4 py-3">${ts.description}</td>
                `;

        tbody.appendChild(tr);
      });
    })
    .catch((error) => {
      console.error(error);
      Toast(error.message, "error");
    });
}

document.addEventListener("DOMContentLoaded", () => {
  list();
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    Loading(true);
    fetch(api, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        name: document.getElementById("ts-name").value,
        description: document.getElementById("ts-description").value,
      }),
    })
      .then(async (response) => {
        const data = await response.json();

        if (!response.ok) {
          throw new Error(data.message || "Erreur serveur");
        }

        return data;
      })
      .then((data) => {
        Toast(data.message, "success");
        form.reset();
        list();
      })
      .catch((error) => {
        Toast(error.message, "error");
        console.error(error);
      })
      .finally(() => {
        Loading(false);
      });
  });
});

btnAjouter.addEventListener("click", () =>
  sectionForm.classList.remove("hidden"),
);
btnAnnuler.addEventListener("click", () => {
  sectionForm.classList.add("hidden");
  form.reset();
});
