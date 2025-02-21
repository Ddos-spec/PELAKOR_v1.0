<?php
session_start();
include 'connect-db.php';

//konfigurasi pagination
$jumlahDataPerHalaman = 3;
$query = mysqli_query($connect,"SELECT * FROM agen");
$jumlahData = mysqli_num_rows($query);
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = isset($_GET["page"]) ? $_GET["page"] : 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

//Query dasar untuk mengambil data agen
$agen = mysqli_query($connect,"SELECT * FROM agen LIMIT $awalData, $jumlahDataPerHalaman");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laundryku</title>
    <?php include 'headtags.html' ?>
    <style>
        .agent-card {
            height: 100%;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .agent-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .agent-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin: 1rem auto;
        }
        .rating-stars {
            color: #ffd700;
            font-size: 20px;
            margin: 10px 0;
        }
        .search-container {
            margin: 2rem auto;
            max-width: 500px;
        }
        .agent-info {
            padding: 1rem;
            flex-grow: 1;
        }
        .agent-contact {
            margin-top: auto;
            padding: 1rem;
        }
        .pagination {
            margin: 2rem 0;
        }
        #noResults {
            text-align: center;
            padding: 2rem;
            display: none;
        }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <br>
        <h1 class="header center orange-text">
            <img src="img/banner.png" width="70%" alt="Laundryku Banner">
        </h1>
        <div class="row center">
            <h5 class="header col s12 light">Solusi Laundry Praktis Tanpa Keluar Rumah</h5>
        </div>

        <!-- Menu Buttons Section -->
        <div class="row center">
            <?php include 'menu-buttons.php'; ?>
        </div>

        <!-- Search Bar -->
        <div class="search-container">
            <div class="input-field">
                <i class="material-icons prefix">search</i>
                <input type="text" id="keyword" placeholder="Cari berdasarkan nama laundry atau kota..." 
                       class="validate" autocomplete="off">
                <label for="keyword">Pencarian</label>
            </div>
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="grey-text">
            <h5>Tidak ada hasil yang ditemukan</h5>
        </div>

        <!-- Agent List Container -->
        <div class="row" id="agentContainer">
            <!-- Agents will be loaded here dynamically -->
        </div>

        <!-- Pagination -->
        <div class="row center">
            <ul class="pagination">
                <?php if($halamanAktif > 1) : ?>
                    <li class="waves-effect">
                        <a href="?page=<?= $halamanAktif - 1 ?>">
                            <i class="material-icons">chevron_left</i>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                    <li class="waves-effect <?= $i == $halamanAktif ? 'active blue' : '' ?>">
                        <a href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if($halamanAktif < $jumlahHalaman) : ?>
                    <li class="waves-effect">
                        <a href="?page=<?= $halamanAktif + 1 ?>">
                            <i class="material-icons">chevron_right</i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <?php include "footer.php" ?>

    <!-- Initialize Materialize Components -->
    <script src="materialize/js/materialize.min.js"></script>
    
    <!-- Custom JavaScript for Real-time Search -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('keyword');
        const agentContainer = document.getElementById('agentContainer');
        const noResults = document.getElementById('noResults');
        
        // Load initial data
        fetchAgents('');

        // Add input event listener for real-time search
        searchInput.addEventListener('input', function(e) {
            const keyword = e.target.value.trim();
            fetchAgents(keyword);
        });

        function fetchAgents(keyword) {
            fetch(`ajax/agen.php?action=getAgents&keyword=${encodeURIComponent(keyword)}`)
                .then(response => response.json())
                .then(data => {
                    updateAgentList(data);
                })
                .catch(error => console.error('Error:', error));
        }

        function updateAgentList(agents) {
            agentContainer.innerHTML = '';
            
            if (agents.length === 0) {
                noResults.style.display = 'block';
                return;
            }
            
            noResults.style.display = 'none';
            
            agents.forEach(agent => {
                const agentCard = createAgentCard(agent);
                agentContainer.innerHTML += agentCard;
            });

            // Initialize any Materialize components in the new content
            const ratings = document.querySelectorAll('.rating-stars');
            ratings.forEach(rating => {
                const stars = rating.getAttribute('data-rating');
                rating.innerHTML = '★'.repeat(stars) + '☆'.repeat(5 - stars);
            });
        }

        function createAgentCard(agent) {
            return `
                <div class="col s12 m4">
                    <div class="card agent-card">
                        <div class="card-content center-align">
                            <img src="img/agen/${agent.foto}" class="agent-image" alt="${agent.nama_laundry}">
                            <div class="agent-info">
                                <span class="card-title">${agent.nama_laundry}</span>
                                <div class="rating-stars" data-rating="${agent.rating || 0}"></div>
                                <p class="grey-text">
                                    <i class="material-icons tiny">location_on</i> ${agent.alamat}, ${agent.kota}
                                </p>
                                <p class="grey-text">
                                    <i class="material-icons tiny">phone</i> ${agent.telp}
                                </p>
                            </div>
                            <div class="agent-contact">
                                <a href="detail-agen.php?id=${agent.id_agen}" 
                                   class="btn waves-effect waves-light blue darken-2">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
    });
    </script>
</body>
</html>