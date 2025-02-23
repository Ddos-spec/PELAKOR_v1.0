<?php
session_start();
require_once 'connect-db.php';
require_once 'functions/functions.php';

// Fungsi helper (misalnya cek login pelanggan) di sini...
cekPelanggan();

// ... kode fungsi getAgentData(), getCustomerData(), calculateAgentRating() tetap sama

// Ambil parameter request
$idAgen = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$jenis = filter_input(INPUT_GET, 'jenis', FILTER_SANITIZE_STRING);
$idPelanggan = $_SESSION["pelanggan"];

$agen = getAgentData($connect, $idAgen);
$pelanggan = getCustomerData($connect, $idPelanggan);
$rating = calculateAgentRating($connect, $idAgen);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'headtags.html'; ?>
    <title>Pemesanan Laundry</title>
    <style>
        /* CSS seperti sebelumnya */
        .profile-img { width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 2px solid #ddd; }
        .agent-info-container { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; }
        .agent-details h3 { margin: 0; }
        .agent-details ul { list-style-type: none; padding: 0; margin: 0; }
        .item-selection { margin: 20px 0; }
        .item-selection label { display: flex; align-items: center; margin: 10px 0; }
        .item-quantity { width: 60px !important; margin-left: 10px !important; }
        .price-preview { margin: 20px 0; font-weight: bold; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Informasi Laundry -->
    <div class="container">
        <div class="row">
            <div class="agent-info-container col s12">
                <div>
                    <img src="img/agen/<?= htmlspecialchars($agen['foto']) ?>" class="profile-img" alt="Laundry Logo">
                </div>
                <div class="agent-details">
                    <h3><?= htmlspecialchars($agen["nama_laundry"]) ?></h3>
                    <ul>
                        <li>
                            <fieldset class="bintang">
                                <span class="starImg star-<?= $rating ?>"></span>
                            </fieldset>
                        </li>
                        <li>Alamat: <?= htmlspecialchars($agen["alamat"] . ", " . $agen["kota"]) ?></li>
                        <li>No. HP: <?= htmlspecialchars($agen["telp"]) ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Pemesanan -->
    <div class="row">
        <div class="col s10 offset-s1">
            <!-- Tambahkan onsubmit untuk validasi -->
            <form action="" method="post" id="orderForm" onsubmit="return validateOrderForm();">
                <!-- Data Diri Pelanggan -->
                <div class="col s5">
                    <h3 class="header light center">Data Diri</h3>
                    <div class="input-field">
                        <input id="nama" type="text" disabled value="<?= htmlspecialchars($pelanggan['nama']) ?>">
                        <label for="nama">Nama Penerima</label>
                    </div>
                    <div class="input-field">
                        <input id="telp" type="text" disabled value="<?= htmlspecialchars($pelanggan['telp']) ?>">
                        <label for="telp">No Telp</label>
                    </div>
                    <div class="input-field">
                        <textarea class="materialize-textarea" name="alamat" id="alamat" required><?= htmlspecialchars($pelanggan['alamat'] . ", " . $pelanggan['kota']) ?></textarea>
                        <label for="alamat">Alamat</label>
                    </div>
                </div>

                <!-- Informasi Paket Laundry -->
                <div class="col s5 offset-s1">
                    <h3 class="header light center">Info Paket Laundry</h3>
                    <div class="input-field">
                        <h5>Pilih Jenis Pakaian:</h5>
                        <?php
                        $items = ['baju', 'celana', 'jaket', 'karpet', 'pakaian_khusus'];
                        foreach ($items as $item):
                        ?>
                            <div class="item-selection">
                                <label>
                                    <input type="checkbox" class="item-checkbox" 
                                           name="items[<?= $item ?>]" value="<?= $item ?>"
                                           onchange="toggleQuantity('<?= $item ?>')">
                                    <span><?= ucfirst($item) ?></span>
                                    <input type="number" name="quantities[<?= $item ?>]" 
                                           id="quantity-<?= $item ?>" class="item-quantity"
                                           min="0" value="0" disabled 
                                           onchange="calculatePrice()">
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pilihan Jenis Layanan -->
                    <div class="input-field">
                        <h5>Jenis Paket:</h5>
                        <div class="row">
                            <?php
                            $serviceTypes = [
                                'cuci' => 'Cuci',
                                'setrika' => 'Setrika', 
                                'komplit' => 'Komplit'
                            ];
                            foreach ($serviceTypes as $type => $label):
                                $checked = ($jenis === $type) ? 'checked' : '';
                            ?>
                                <div class="col s4">
                                    <div class="card-panel hoverable">
                                        <label>
                                            <input type="radio" name="jenis" value="<?= $type ?>" 
                                                   <?= $checked ?> onchange="calculatePrice()" required>
                                            <span class="black-text"><?= $label ?></span>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div class="input-field">
                        <textarea class="materialize-textarea" name="catatan" id="catatan"></textarea>
                        <label for="catatan">Catatan</label>
                    </div>

                    <div class="price-preview">
                        <h4>Perkiraan Harga: <span id="pricePreview">Rp 0</span></h4>
                    </div>

                    <div class="input-field center">
                        <button class="btn-large blue darken-2" type="submit" name="pesan">
                            Buat Pesanan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
    let itemPrices = {};

    function fetchPrices() {
        const idAgen = <?= $idAgen ?>;
        try {
            fetch(`ajax/agen.php?action=getPrices&idAgen=${idAgen}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch prices');
                    }
                    return response.json();
                })
                .then(data => {
                    // Validate prices
                    if (!data || Object.keys(data).length === 0) {
                        throw new Error('No price data received');
                    }
                    
                    // Validate each price
                    Object.entries(data).forEach(([item, prices]) => {
                        if (!prices || typeof prices !== 'object') {
                            throw new Error(`Invalid price structure for ${item}`);
                        }
                        Object.entries(prices).forEach(([serviceType, price]) => {
                            if (isNaN(price) || price <= 0) {
                                throw new Error(`Invalid price for ${item} - ${serviceType}`);
                            }
                        });
                    });

                    itemPrices = data;
                    calculatePrice();
                })
                .catch(error => {
                    console.error('Error fetching prices:', error);
                    M.toast({html: 'Gagal memuat harga: ' + error.message, classes: 'red'});
                    throw error; // Re-throw to prevent further execution
                });
        } catch (error) {
            console.error('Error in fetchPrices:', error);
            M.toast({html: 'Terjadi kesalahan saat memuat harga', classes: 'red'});
        }
    }

    function toggleQuantity(itemType) {
        const checkbox = document.querySelector(`input[name="items[${itemType}]"]`);
        const quantityInput = document.getElementById(`quantity-${itemType}`);
        quantityInput.disabled = !checkbox.checked;
        if (!checkbox.checked) {
            quantityInput.value = 0;
        }
        calculatePrice();
    }

    function calculatePrice() {
        try {
            const serviceType = document.querySelector('input[name="jenis"]:checked')?.value;
            if (!serviceType) {
                throw new Error('Jenis layanan belum dipilih');
            }

            let totalPrice = 0;
            ['baju', 'celana', 'jaket', 'karpet', 'pakaian_khusus'].forEach(item => {
                const qty = parseInt(document.getElementById(`quantity-${item}`).value) || 0;
                if (qty > 0) {
                    if (!itemPrices[item]?.[serviceType]) {
                        throw new Error(`Harga untuk ${item} - ${serviceType} tidak ditemukan`);
                    }
                    if (isNaN(itemPrices[item][serviceType]) || itemPrices[item][serviceType] <= 0) {
                        throw new Error(`Harga tidak valid untuk ${item} - ${serviceType}`);
                    }
                    totalPrice += qty * itemPrices[item][serviceType];
                }
            });

            if (totalPrice <= 0) {
                throw new Error('Total harga tidak valid');
            }

            document.getElementById('pricePreview').innerText = 
                `Rp ${totalPrice.toLocaleString('id-ID')}`;
        } catch (error) {
            console.error('Error in calculatePrice:', error);
            document.getElementById('pricePreview').innerText = 'Rp 0';
            M.toast({html: 'Gagal menghitung harga: ' + error.message, classes: 'red'});
        }
    }

    // Validasi form: pastikan setidaknya satu item terpilih
    function validateOrderForm() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        let checked = false;
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                checked = true;
            }
        });
        if (!checked) {
            Swal.fire({
                title: 'Error',
                text: 'Pilih minimal satu item',
                icon: 'error'
            });
            return false;
        }
        return true;
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchPrices();
        document.querySelectorAll('.item-quantity').forEach(input => {
            input.addEventListener('change', calculatePrice);
        });
    });
    </script>

    <?php
    // Proses pembuatan pesanan
    if (isset($_POST["pesan"])) {
        $orderData = [
            'id_agen' => $idAgen,
            'id_pelanggan' => $idPelanggan,
            'tgl_mulai' => date("Y-m-d H:i:s"),
            'jenis' => filter_input(INPUT_POST, 'jenis', FILTER_SANITIZE_STRING),
            'alamat' => filter_input(INPUT_POST, 'alamat', FILTER_SANITIZE_STRING),
            'catatan' => filter_input(INPUT_POST, 'catatan', FILTER_SANITIZE_STRING),
        ];

        // Proses item yang dipilih
        $items = $_POST['items'] ?? [];
        $quantities = $_POST['quantities'] ?? [];
        $totalItems = 0;
        $itemDetails = [];

        foreach ($items as $item => $val) {
            $qty = (int)($quantities[$item] ?? 0);
            if ($qty > 0) {
                $totalItems += $qty;
                $itemDetails[] = ucfirst($item) . " ($qty)";
            }
        }

        if ($totalItems <= 0) {
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Tidak ada item yang dipilih',
                    icon: 'error'
                });
            </script>";
        } else {
            $orderData['item_type'] = implode(', ', $itemDetails);
            $orderData['total_item'] = $totalItems;

            if (createOrder($connect, $orderData)) {
                // Simpan id pesanan baru dalam session agar diproses di status.php
                $_SESSION['new_order_id'] = mysqli_insert_id($connect);
                echo "<script>
                    Swal.fire({
                        title: 'Pesanan Berhasil Dibuat',
                        text: 'Silahkan periksa status cucian',
                        icon: 'success'
                    }).then(() => {
                        window.location = 'status.php';
                    });
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'Error',
                        text: 'Gagal membuat pesanan',
                        icon: 'error'
                    });
                </script>";
            }
        }
    }
    ?>

    <?php include 'footer.php'; ?>
</body>
</html>
