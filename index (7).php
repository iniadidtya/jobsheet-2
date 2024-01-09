<?php
session_start();

// Functions
function getTransactions()
{
    return isset($_SESSION['transactions']) ? $_SESSION['transactions'] : [];
}

function addTransaction($type, $amount, $description)
{
    $transaction = [
        'id' => uniqid(),
        'type' => $type,
        'amount' => $amount,
        'description' => $description,
        'date' => date('Y-m-d'), // Menggunakan format hanya tanggal
    ];

    $_SESSION['transactions'][] = $transaction;

    // Menambahkan pesan ke sesi untuk ditampilkan setelah pengguna menambahkan transaksi
    $_SESSION['message'] = "Transaksi berhasil ditambahkan";

    // Mengarahkan pengguna kembali ke halaman utama
    header("Location: index.php");
    exit();
}

function calculateBalance()
{
    $transactions = getTransactions();
    $balance = 0;

    foreach ($transactions as $transaction) {
        if ($transaction['type'] === 'Pemasukan') {
            $balance += $transaction['amount'];
        } elseif ($transaction['type'] === 'Pengeluaran') {
            $balance -= $transaction['amount'];
        }
    }

    return $balance;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];

    addTransaction($type, $amount, $description);
}

$transactions = getTransactions();
$balance = calculateBalance();

// Menghapus pesan setelah ditampilkan
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kas Kelas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
            background-color: lightblue; /* Warna latar belakang */
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white; /* Warna latar belakang tabel */
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px; /* Menambahkan padding */
            text-align: left; /* Mencentang ke kiri */
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            margin-top: 20px;
            background-color: #fff; /* Warna latar belakang formulir */
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        .message {
            margin: 10px 0;
            color: green;
            background-color: #e1f7d5; /* Warna latar belakang pesan */
            padding: 10px;
            border-radius: 5px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        label, input, select, button {
            margin-bottom: 10px; /* Menambahkan jarak di bawah setiap elemen */
        }

        /* Responsivitas */
        @media (max-width: 600px) {
            form, table {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <h1>Kas Kelas XII 2</h1>

    <?php if ($message) : ?>
        <p class="message"><?= $message; ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="type">Jenis Transaksi:</label>
        <select id="type" name="type" required>
            <option value="Pemasukan">Pemasukan</option>
            <option value="Pengeluaran">Pengeluaran</option>
        </select>

        <br>

        <label for="amount">Jumlah:</label>
        <input type="number" id="amount" name="amount" required>

        <br>

        <label for="description">Keterangan:</label>
        <input type="text" id="description" name="description" required>

        <br>

        <button type="submit">Tambah Transaksi</button>
    </form>

    <h2>Daftar Transaksi</h2>
    <table>
        <tr>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
        </tr>
        <?php foreach ($transactions as $transaction) : ?>
            <tr>
                <td><?= $transaction['date']; ?></td>
                <td><?= ucfirst($transaction['type']); ?></td>
                <td>Rp <?= number_format($transaction['amount'], 0, ',', '.'); ?></td>
                <td><?= $transaction['description']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Saldo Akhir: Rp <?= number_format($balance, 0, ',', '.'); ?></h2>

</div>

<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
</html>
