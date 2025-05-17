document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("bookingModal");
  const closeBtn = document.getElementById("closeModalBtn");
  const dropdown = document.getElementById("dropdownNoKamar");

    const tanggalMulaiInput = document.querySelector('input[name="tanggal_mulai"]');
    const tanggalSelesaiInput = document.querySelector('input[name="tanggal_selesai"]');


  // Ambil tanggal hari ini
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0'); // bulan mulai dari 0
  const day = String(today.getDate()).padStart(2, '0');
  const todayStr = `${year}-${month}-${day}`;

  // Set atribut min
  tanggalMulaiInput.min = todayStr;

    tanggalMulaiInput.addEventListener('change', function() {
        const mulai = new Date(this.value);
        if (!isNaN(mulai.getTime())) {
            // tambah 1 bulan
            let selesai = new Date(mulai);
            selesai.setMonth(selesai.getMonth() + 1);

            // format YYYY-MM-DD
            let month = selesai.getMonth() + 1;
            let day = selesai.getDate();
            let year = selesai.getFullYear();

            if (month < 10) month = '0' + month;
            if (day < 10) day = '0' + day;

            tanggalSelesaiInput.value = `${year}-${month}-${day}`;
        }
    });

  document.querySelectorAll(".open-booking-modal").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const tipeKamar = btn.getAttribute("data-tipe");
      document.getElementById("modalTipeKamar").value = tipeKamar;

      // Reset isi dropdown
      dropdown.innerHTML = '<option value="">-- Memuat kamar kosong... --</option>';

      // Fetch data kamar kosong
      fetch("index.php?page=home", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "tipe_kamar=" + encodeURIComponent(tipeKamar),
      })
        .then((res) => res.json())
        .then((data) => {
          dropdown.innerHTML = '<option value="">-- Pilih No Kamar --</option>';
          if (data.length > 0) {
            data.forEach((kamar) => {
              const option = document.createElement("option");
              option.value = kamar.no_kamar;
              option.textContent = kamar.no_kamar;
              dropdown.appendChild(option);
            });
          } else {
            const option = document.createElement("option");
            option.value = "";
            option.textContent = "Tidak ada kamar kosong";
            dropdown.appendChild(option);
          }
        });

      modal.style.display = "block";
    });
  });

  closeBtn.addEventListener("click", function () {
    modal.style.display = "none";
  });

  window.addEventListener("click", function (event) {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });
});
