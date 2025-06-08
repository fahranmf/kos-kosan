document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("bookingModal");
  const closeBtn = document.getElementById("closeModalBtn");
  const dropdown = document.getElementById("dropdownNoKamar");

  const tanggalMulaiInput = document.querySelector('input[name="tanggal_mulai"]');
  const tanggalSelesaiInput = document.querySelector('input[name="tanggal_selesai"]');

  // Ambil tanggal hari ini
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0');
  const day = String(today.getDate()).padStart(2, '0');
  const todayStr = `${year}-${month}-${day}`;

  tanggalMulaiInput.min = todayStr;

  tanggalMulaiInput.addEventListener("change", function () {
    const mulai = new Date(this.value);
    if (!isNaN(mulai.getTime())) {
      const selesai = new Date(mulai);
      selesai.setMonth(selesai.getMonth() + 1);

      const selesaiYear = selesai.getFullYear();
      const selesaiMonth = String(selesai.getMonth() + 1).padStart(2, '0');
      const selesaiDay = String(selesai.getDate()).padStart(2, '0');

      tanggalSelesaiInput.value = `${selesaiYear}-${selesaiMonth}-${selesaiDay}`;
    }
  });

  function openBookingModal(tipeKamar) {
    document.getElementById("modalTipeKamar").value = tipeKamar;
    dropdown.innerHTML = '<option value="">-- Memuat kamar kosong... --</option>';

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

    modal.style.display = "flex";
    document.body.classList.add("no-scroll");
  }

  // Event buka modal via klik tombol
  document.querySelectorAll(".open-booking-modal").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const tipeKamar = btn.getAttribute("data-tipe");
      openBookingModal(tipeKamar);
    });
  });

  // Event tutup modal
  closeBtn.addEventListener("click", function () {
    modal.style.display = "none";
    document.body.classList.remove("no-scroll");
  });

  window.addEventListener("click", function (event) {
    if (event.target === modal) {
      modal.style.display = "none";
      document.body.classList.remove("no-scroll");
    }
  });

  // === Buka otomatis kalau error atau dari URL ===
  if (window.bookingModalError?.hasError || window.bookingModalError?.shouldOpenModal) {
    if (window.bookingModalError.tipeKamar) {
      openBookingModal(window.bookingModalError.tipeKamar);
    } else {
      modal.style.display = "flex";
      document.body.classList.add("no-scroll");
    }

  }
});
