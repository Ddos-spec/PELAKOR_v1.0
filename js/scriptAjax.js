document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('keyword');
    const agentContainer = document.getElementById('agentContainer');
    const paginationContainer = document.querySelector('.pagination');
    let currentPage = 1;
    let timeoutId = null;

    // Muat data awal (opsional), jika ingin langsung menampilkan data pertama kali tanpa reload:
    loadAgents('', currentPage);

    // Real-time search handler dengan debounce
    searchInput.addEventListener('input', function(e) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            currentPage = 1; // Reset ke halaman pertama saat ada input baru
            const keyword = e.target.value.trim();
            loadAgents(keyword, currentPage);
        }, 300);
    });

    function loadAgents(keyword, page = 1) {
        fetch(`ajax/agen.php?action=getAgents&keyword=${encodeURIComponent(keyword)}&page=${page}`)
            .then(response => response.json())
            .then(data => {
                // Pastikan data tidak error
                if (data.error) {
                    console.error(data.error);
                    return;
                }
                updateAgentList(data.agents);
                updatePagination(data.pagination);
            })
            .catch(error => console.error('Error:', error));
    }

    function updateAgentList(agents) {
        if (!agentContainer) return;

        agentContainer.innerHTML = '';

        // Tangani kasus jika tidak ada data
        const noResults = document.getElementById('noResults');
        if (!agents || agents.length === 0) {
            if (noResults) noResults.style.display = 'block';
            return;
        }
        if (noResults) noResults.style.display = 'none';

        // Render data agen
        agents.forEach(agent => {
            const agentCard = `
                <div class="col s12 m4">
                    <div class="card agent-card">
                        <div class="card-image">
                            <a href="detail-agen.php?id=${agent.id_agen}">
                                <img src="img/agen/${agent.foto}" alt="${agent.nama_laundry}" class="agent-image">
                            </a>
                        </div>
                        <div class="card-content">
                            <span class="card-title">${agent.nama_laundry}</span>
                            <p>
                                <i class="material-icons tiny">location_on</i> ${agent.alamat}, ${agent.kota}
                            </p>
                            <p>
                                <i class="material-icons tiny">phone</i> ${agent.telp}
                            </p>
                        </div>
                        <div class="card-action">
                            <div class="rating-stars">
                                ${'★'.repeat(agent.rating)}${'☆'.repeat(5 - agent.rating)}
                            </div>
                            <a href="detail-agen.php?id=${agent.id_agen}">Detail</a>
                        </div>
                    </div>
                </div>
            `;
            agentContainer.insertAdjacentHTML('beforeend', agentCard);
        });
    }

    function updatePagination(paginationData) {
        if (!paginationContainer) return;

        const { currentPage, totalPages } = paginationData;
        let paginationHTML = '';

        // Tombol "previous"
        if (currentPage > 1) {
            paginationHTML += `
                <li class="waves-effect">
                    <a href="#!" data-page="${currentPage - 1}">
                        <i class="material-icons">chevron_left</i>
                    </a>
                </li>
            `;
        }

        // Nomor halaman
        for (let i = 1; i <= totalPages; i++) {
            paginationHTML += `
                <li class="waves-effect ${i == currentPage ? 'active blue' : ''}">
                    <a href="#!" data-page="${i}">${i}</a>
                </li>
            `;
        }

        // Tombol "next"
        if (currentPage < totalPages) {
            paginationHTML += `
                <li class="waves-effect">
                    <a href="#!" data-page="${currentPage + 1}">
                        <i class="material-icons">chevron_right</i>
                    </a>
                </li>
            `;
        }

        paginationContainer.innerHTML = paginationHTML;

        // Tambahkan event klik untuk pagination
        paginationContainer.querySelectorAll('a[data-page]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = parseInt(this.dataset.page);
                loadAgents(searchInput.value.trim(), currentPage);
            });
        });
    }
});
