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

    public function hapusBuku($index) {
        if (isset($this->daftarBuku[$index])) {
            array_splice($this->daftarBuku, $index, 1);
            return true;
        }
        return false;
    }

    public function editBuku($index, $judul, $penulis, $tahun) {
        if (isset($this->daftarBuku[$index])) {
            $this->daftarBuku[$index]->judul = $judul;
            $this->daftarBuku[$index]->penulis = $penulis;
            $this->daftarBuku[$index]->tahun = $tahun;
            return true;
        }
        return false;
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
$alert = "";

// Tambah buku
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $judul = $_POST['judul'] ?? '';
    $penulis = $_POST['penulis'] ?? '';
    $tahun = $_POST['tahun'] ?? '';
    if ($judul && $penulis && $tahun) {
        $perpus->tambahBuku(new Buku($judul, $penulis, $tahun));
        $_SESSION['perpus'] = $perpus;
        $alert = "<div class='alert success'>Buku '$judul' berhasil ditambahkan!</div>";
    }
}

// Hapus buku
if (isset($_GET['hapus'])) {
    $index = intval($_GET['hapus']);
    $bukuList = $perpus->getDaftarBuku();
    $judulBuku = $bukuList[$index]->judul ?? '';
    if ($perpus->hapusBuku($index)) {
        $_SESSION['perpus'] = $perpus;
        $alert = "<div class='alert danger'>Buku '$judulBuku' berhasil dihapus!</div>";
    }
}

// Edit buku
$editBuku = null;
$editIndex = -1;
if (isset($_GET['edit'])) {
    $editIndex = intval($_GET['edit']);
    $bukuList = $perpus->getDaftarBuku();
    if (isset($bukuList[$editIndex])) {
        $editBuku = $bukuList[$editIndex];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $index = intval($_POST['index']);
    $judul = $_POST['judul'] ?? '';
    $penulis = $_POST['penulis'] ?? '';
    $tahun = $_POST['tahun'] ?? '';
    if ($perpus->editBuku($index, $judul, $penulis, $tahun)) {
        $_SESSION['perpus'] = $perpus;
        $alert = "<div class='alert success'>Buku '$judul' berhasil diperbarui!</div>";
        $editBuku = null; // hide edit form setelah update
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

        /* Alert */
        .alert {
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-weight: 500;
            color: #fff;
        }

        .success { background: #2ecc71; }
        .danger { background: #e74c3c; }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 12px 14px;
            text-align: left;
        }

        th {
            background: #2d3436;
            color: #fff;
            font-weight: 500;
            font-family: 'Montserrat', sans-serif;
        }

        tr:nth-child(even) { background: #f9f9f9; }
        tr:hover { background: #f1f3f5; }

        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            cursor: pointer;
            margin-right: 5px;
            transition: 0.2s;
        }

        .hapus-btn { background: #e74c3c; color: #fff; }
        .hapus-btn:hover { background: #c0392b; }

        .edit-btn { background: #3498db; color: #fff; }
        .edit-btn:hover { background: #2980b9; }

/* Form */
form {
    background: #fff;
    padding: 20px;
    margin-top: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    gap: 15px;
    width: 100%; /* pastikan form full-width container */
    box-sizing: border-box; /* penting agar padding tidak melampaui container */
}

label {
    font-weight: 500;
    color: #444;
}

input {
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-family: 'Poppins', sans-serif;
    transition: 0.2s;
    width: 100%; /* input ikut lebar container */
    box-sizing: border-box; /* penting */
}

input:focus {
    border-color: #2d3436;
    outline: none;
    box-shadow: 0 0 5px rgba(45,52,54,0.3);
}


        button[type="submit"] {
            background: #2d3436;
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            cursor: pointer;
            transition: 0.2s;
        }

        button[type="submit"]:hover { background: #636e72; }
    </style>
</head>
<body>
    <div class="container">
        <h1>OOP-Tugas Mandiri</h1>

        <?php if($alert) echo $alert; ?>

        <h2>Daftar Buku</h2>
        <table>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun</th>
                <th>Aksi</th>
            </tr>
            <?php
            $bukuList = $perpus->getDaftarBuku();
            if (empty($bukuList)) {
                echo "<tr><td colspan='5' style='text-align:center;'>Belum ada buku</td></tr>";
            } else {
                foreach ($bukuList as $i => $buku) {
                    echo "<tr>
                            <td>" . ($i+1) . "</td>
                            <td>{$buku->judul}</td>
                            <td>{$buku->penulis}</td>
                            <td>{$buku->tahun}</td>
                            <td>
                                <a href='?edit={$i}'><button class='btn edit-btn'>Edit</button></a>
                                <a href='?hapus={$i}'><button class='btn hapus-btn'>Hapus</button></a>
                            </td>
                          </tr>";
                }
            }
            ?>
        </table>

        <?php if($editBuku): ?>
        <h2>Edit Buku</h2>
        <form method="POST">
            <input type="hidden" name="update" value="1">
            <input type="hidden" name="index" value="<?php echo $editIndex; ?>">
            
            <label>Judul</label>
            <input type="text" name="judul" value="<?php echo htmlspecialchars($editBuku->judul); ?>" required>
            
            <label>Penulis</label>
            <input type="text" name="penulis" value="<?php echo htmlspecialchars($editBuku->penulis); ?>" required>
            
            <label>Tahun Terbit</label>
            <input type="number" name="tahun" value="<?php echo htmlspecialchars($editBuku->tahun); ?>" required>
            
            <button type="submit">Update Buku</button>
        </form>
        <?php else: ?>
        <h2>Tambah Buku Baru</h2>
        <form method="POST">
            <input type="hidden" name="add" value="1">
            <label>Judul</label>
            <input type="text" name="judul" required>
            
            <label>Penulis</label>
            <input type="text" name="penulis" required>
            
            <label>Tahun Terbit</label>
            <input type="number" name="tahun" required>
            
            <button type="submit">Tambah Buku</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
