<?php
// Class Buku
class Buku {
    public $judul;
    public $penulis;
    public $tahun;

    public function __construct($judul, $penulis, $tahun) {
        $this->judul = $judul;
        $this->penulis = $penulis;
        $this->tahun = $tahun;
    }
}

class Perpustakaan {
    private $daftarBuku = [];

    public function tambahBuku(Buku $buku) {
        $this->daftarBuku[] = $buku;
    }

    public function getDaftarBuku() {
        return $this->daftarBuku;
    }
}

// Session
session_start();
if (!isset($_SESSION['perpus'])) {
    $_SESSION['perpus'] = new Perpustakaan();
    $_SESSION['perpus']->tambahBuku(new Buku("The Great Rico", "Isancuarry", 2005));
    $_SESSION['perpus']->tambahBuku(new Buku("Planet Earth", "Kurosaki Ichigo", 1980));
    $_SESSION['perpus']->tambahBuku(new Buku("Cruelty of Time", "Soulatlast", 1997));
}
$perpus = $_SESSION['perpus'];

// Handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'] ?? '';
    $penulis = $_POST['penulis'] ?? '';
    $tahun = $_POST['tahun'] ?? '';
    if ($judul && $penulis && $tahun) {
        $perpus->tambahBuku(new Buku($judul, $penulis, $tahun));
        $_SESSION['perpus'] = $perpus;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas Mandiri</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Poppins:wght@300;500&display=swap" rel="stylesheet">
    <style>
        /* === Global Style === */
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #222;
            text-align: center;
        }

        h2 {
            font-size: 1.2rem;
            margin-top: 40px;
            margin-bottom: 15px;
            font-weight: 500;
            color: #444;
        }

        /* === Table === */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
        }

        th {
            background: #2d3436;
            color: #fff;
            font-weight: 500;
            font-family: 'Montserrat', sans-serif;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tr:hover {
            background: #f1f3f5;
        }

        /* === Form === */
        form {
            background: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        label {
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
            color: #444;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: 0.2s;
            font-family: 'Poppins', sans-serif;
        }

        input:focus {
            border-color: #2d3436;
            outline: none;
            box-shadow: 0 0 5px rgba(45,52,54,0.3);
        }

        button {
            background: #2d3436;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            transition: background 0.2s;
        }

        button:hover {
            background: #636e72;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tugas Mandiri (OOP Management Perpustakaan)</h1>

        <h2>Daftar Buku</h2>
        <table>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun</th>
            </tr>
            <?php
            $bukuList = $perpus->getDaftarBuku();
            if (empty($bukuList)) {
                echo "<tr><td colspan='4' style='text-align:center;'>Belum ada buku</td></tr>";
            } else {
                foreach ($bukuList as $i => $buku) {
                    echo "<tr>
                            <td>" . ($i+1) . "</td>
                            <td>{$buku->judul}</td>
                            <td>{$buku->penulis}</td>
                            <td>{$buku->tahun}</td>
                          </tr>";
                }
            }
            ?>
        </table>

        <h2>Tambah Buku Baru</h2>
        <form method="POST">
            <label>Judul</label>
            <input type="text" name="judul" required>
            
            <label>Penulis</label>
            <input type="text" name="penulis" required>
            
            <label>Tahun Terbit</label>
            <input type="number" name="tahun" required>
            
            <button type="submit">Tambah Buku</button>
        </form>
    </div>
</body>
</html>
