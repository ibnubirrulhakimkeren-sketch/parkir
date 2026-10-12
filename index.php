<?php
$hasil = false;
$error = "";
$total = 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $no = trim($_POST["no_parkir"] ?? "");
    $kendaraan = trim($_POST["no_kendaraan"] ?? "");
    $jenis = $_POST["jenis"] ?? "";
    $lama = filter_var($_POST["lama"] ?? "", FILTER_VALIDATE_INT);
    $petugas = trim($_POST["petugas"] ?? "");

    if (
        $no === "" || $kendaraan === "" || $petugas === "" ||
        !in_array($jenis, ["Motor", "Mobil", "Truk"], true) ||
        $lama === false || $lama === null || $lama < 1
    ) {
        $error = "Lengkapi semua data dengan benar!";
    } else {
        if ($jenis === "Motor") {
            $awal = 3000;
            $lanjut = 2000;
        } elseif ($jenis === "Mobil") {
            $awal = 5000;
            $lanjut = 3000;
        } else {
            $awal = 7000;
            $lanjut = 3000;
        }

        $total = $awal + (($lama - 1) * $lanjut);
        $hasil = true;
    }
}

function aman($nilai) {
    return htmlspecialchars((string)$nilai, ENT_QUOTES, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Parkir Angkasa</title>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    min-height: 100vh;
    font-family: Arial, sans-serif;
    background:
        radial-gradient(ellipse at top, #13294b 0%, transparent 55%),
        #060912;
    color: #edf6ff;
    padding: 30px 15px;
}

.container {
    max-width: 950px;
    margin: auto;
}

header {
    text-align: center;
    margin-bottom: 28px;
}

.logo {
    color: #00e5ff;
    font-size: 30px;
    font-weight: 900;
    letter-spacing: 3px;
    text-shadow: 0 0 20px #00e5ff70;
}

header p {
    color: #91a5c4;
    font-size: 13px;
    letter-spacing: 2px;
}

.layout {
    display: grid;
    grid-template-columns: 0.9fr 1.1fr;
    gap: 20px;
}

.panel {
    background: rgba(16, 24, 39, 0.92);
    border: 1px solid #26364e;
    border-radius: 18px;
    padding: 25px;
    box-shadow: 0 15px 45px #0005;
}

h2 {
    font-size: 19px;
    margin-top: 0;
}

.subtitle {
    color: #8195b2;
    font-size: 13px;
    margin-bottom: 24px;
}

.vehicle-stage {
    height: 230px;
    border-radius: 14px;
    background:
        radial-gradient(ellipse at center, #13365a 0%, #101827 65%);
    border: 1px solid #23415b;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.vehicle-stage::after {
    content: "";
    position: absolute;
    width: 85%;
    height: 2px;
    bottom: 42px;
    background: #00e5ff;
    box-shadow: 0 0 15px #00e5ff;
    opacity: .6;
}

.vehicle {
    font-size: 105px;
    z-index: 1;
    filter: drop-shadow(0 0 20px #00d9ff65);
    animation: float 2.5s ease-in-out infinite;
    transition: transform .3s;
}

.vehicle.change {
    animation: appear .5s ease;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes appear {
    from { opacity: 0; transform: translateX(45px) scale(.7); }
    to { opacity: 1; transform: translateX(0) scale(1); }
}

.vehicle-name {
    text-align: center;
    margin: 18px 0 8px;
    color: #00e5ff;
    font-weight: bold;
    letter-spacing: 2px;
}

.vehicle-desc {
    text-align: center;
    color: #8295af;
    font-size: 12px;
    margin-bottom: 22px;
}

.tarif {
    background: #0a111e;
    border: 1px solid #26364e;
    border-radius: 12px;
    padding: 15px;
}

.tarif h3 {
    font-size: 13px;
    margin-top: 0;
    color: #9eb1ca;
}

.tarif-row {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    font-size: 12px;
    padding: 8px 0;
    border-bottom: 1px solid #1d293b;
}

.tarif-row:last-child {
    border-bottom: none;
}

.tarif-row span:last-child {
    color: #00e5ff;
    font-weight: bold;
}

label {
    display: block;
    font-size: 12px;
    color: #a8b8cf;
    margin: 16px 0 7px;
}

input, select {
    width: 100%;
    padding: 12px;
    border: 1px solid #2c3b52;
    background: #090f1b;
    color: #edf6ff;
    border-radius: 9px;
    outline: none;
    font-size: 14px;
}

input:focus, select:focus {
    border-color: #00e5ff;
    box-shadow: 0 0 0 3px #00e5ff15;
}

select option {
    background: #101827;
}

button {
    width: 100%;
    margin-top: 23px;
    padding: 14px;
    background: linear-gradient(100deg, #00b8d4, #397bff);
    color: #03101c;
    font-weight: 900;
    letter-spacing: 2px;
    border: none;
    border-radius: 9px;
    cursor: pointer;
    transition: .25s;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 25px #00d9ff50;
}

.result {
    margin-top: 20px;
    padding: 18px;
    border-radius: 12px;
    background: #071d20;
    border: 1px solid #00bda0;
    animation: appear .5s ease;
    overflow-wrap: anywhere;
}

.result h3 {
    color: #58f5ce;
    margin-top: 0;
}

.result p {
    font-size: 13px;
    color: #bdcddd;
    line-height: 1.6;
}

.amount {
    font-size: 30px;
    font-weight: bold;
    color: #58f5ce;
    margin-top: 8px;
}

.error {
    color: #ff9292;
    background: #30151e;
    padding: 12px;
    border-radius: 8px;
    margin-top: 15px;
    font-size: 13px;
}

footer {
    text-align: center;
    color: #52637c;
    font-size: 11px;
    margin-top: 25px;
}

@media (max-width: 700px) {
    .layout {
        grid-template-columns: 1fr;
    }

    .logo {
        font-size: 25px;
    }

    .panel {
        padding: 19px;
    }

    .vehicle-stage {
        height: 190px;
    }

    .vehicle {
        font-size: 85px;
    }
}
</style>
</head>

<body>
<div class="container">

<header>
    <div class="logo">✦ PARKIR ANGKASA</div>
    <p>PARKING MANAGEMENT SYSTEM</p>
</header>

<div class="layout">

    <section class="panel">
        <h2>Vehicle Preview</h2>
        <p class="subtitle">Pilih kendaraan dan lihat animasinya.</p>

        <div class="vehicle-stage">
            <div class="vehicle" id="vehicle">🏍️</div>
        </div>

        <div class="vehicle-name" id="vehicleName">MOTORCYCLE</div>
        <div class="vehicle-desc" id="vehicleDesc">
            Two wheels · Ready to park
        </div>

        <div class="tarif">
            <h3>DAFTAR TARIF PARKIR</h3>
            <div class="tarif-row">
                <span>🏍️ Motor · Jam pertama</span>
                <span>Rp3.000</span>
            </div>
            <div class="tarif-row">
                <span>Motor · Jam berikutnya</span>
                <span>Rp2.000</span>
            </div>
            <div class="tarif-row">
                <span>🚗 Mobil · Jam pertama</span>
                <span>Rp5.000</span>
            </div>
            <div class="tarif-row">
                <span>Mobil · Jam berikutnya</span>
                <span>Rp3.000</span>
            </div>
            <div class="tarif-row">
                <span>🚚 Truk · Jam pertama</span>
                <span>Rp7.000</span>
            </div>
            <div class="tarif-row">
                <span>Truk · Jam berikutnya</span>
                <span>Rp3.000</span>
            </div>
        </div>
    </section>

    <section class="panel">
        <h2>Form Input Parkir</h2>
        <p class="subtitle">Masukkan data kendaraan yang masuk.</p>

        <form method="POST" action="">
            <label for="no_parkir">01 / No Parkir</label>
            <input
                type="text"
                id="no_parkir"
                name="no_parkir"
                placeholder="Contoh: P001"
                value="<?= aman($_POST['no_parkir'] ?? '') ?>"
                required
            >

            <label for="no_kendaraan">02 / No Kendaraan</label>
            <input
                type="text"
                id="no_kendaraan"
                name="no_kendaraan"
                placeholder="Contoh: H 1234 AB"
                value="<?= aman($_POST['no_kendaraan'] ?? '') ?>"
                required
            >

            <label for="jenis">03 / Jenis Kendaraan</label>
            <select id="jenis" name="jenis" required>
                <option value="Motor"
                    <?= ($_POST['jenis'] ?? 'Motor') === 'Motor' ? 'selected' : '' ?>>
                    🏍️ Motor
                </option>
                <option value="Mobil"
                    <?= ($_POST['jenis'] ?? '') === 'Mobil' ? 'selected' : '' ?>>
                    🚗 Mobil
                </option>
                <option value="Truk"
                    <?= ($_POST['jenis'] ?? '') === 'Truk' ? 'selected' : '' ?>>
                    🚚 Truk
                </option>
            </select>

            <label for="lama">04 / Lama Parkir (jam)</label>
            <input
                type="number"
                id="lama"
                name="lama"
                min="1"
                step="1"
                placeholder="Masukkan jumlah jam"
                value="<?= aman($_POST['lama'] ?? '') ?>"
                required
            >

            <label for="petugas">05 / Nama Petugas</label>
            <input
                type="text"
                id="petugas"
                name="petugas"
                placeholder="Nama petugas parkir"
                value="<?= aman($_POST['petugas'] ?? '') ?>"
                required
            >

            <button type="submit">✦ SUBMIT PARKIR</button>
        </form>

        <?php if ($error !== ""): ?>
            <div class="error"><?= aman($error) ?></div>
        <?php endif; ?>

        <?php if ($hasil): ?>
            <div class="result">
                <h3>✓ Pembayaran Berhasil Dihitung</h3>
                <p>No Parkir: <b><?= aman($no) ?></b></p>
                <p>No Kendaraan: <b><?= aman($kendaraan) ?></b></p>
                <p>Jenis Kendaraan: <b><?= aman($jenis) ?></b></p>
                <p>Lama Parkir: <b><?= aman($lama) ?> jam</b></p>
                <p>Nama Petugas: <b><?= aman($petugas) ?></b></p>
                <hr>
                <p>TOTAL PEMBAYARAN</p>
                <div class="amount">
                    Rp <?= number_format($total, 0, ',', '.') ?>
                </div>
            </div>
        <?php endif; ?>
    </section>

</div>

<footer>
    PARKIR ANGKASA © 2026 · SMART PARKING SYSTEM
</footer>

</div>

<script>
const jenis = document.getElementById("jenis");
const vehicle = document.getElementById("vehicle");
const vehicleName = document.getElementById("vehicleName");
const vehicleDesc = document.getElementById("vehicleDesc");

const kendaraan = {
    Motor: {
        icon: "🏍️",
        nama: "MOTORCYCLE",
        deskripsi: "Two wheels · Ready to park"
    },
    Mobil: {
        icon: "🚗",
        nama: "CAR",
        deskripsi: "Four wheels · Ready to park"
    },
    Truk: {
        icon: "🚚",
        nama: "TRUCK",
        deskripsi: "Heavy vehicle · Ready to park"
    }
};

function ubahKendaraan() {
    const data = kendaraan[jenis.value] || kendaraan.Motor;

    vehicle.classList.remove("change");

    // Memulai ulang animasi ketika pilihan berubah
    void vehicle.offsetWidth;

    vehicle.textContent = data.icon;
    vehicleName.textContent = data.nama;
    vehicleDesc.textContent = data.deskripsi;

    vehicle.classList.add("change");
}

jenis.addEventListener("change", ubahKendaraan);
ubahKendaraan();
</script>

</body>
</html>