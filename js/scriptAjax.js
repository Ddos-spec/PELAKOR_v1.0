document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('keyword');
    const agentContainer = document.getElementById('agentContainer');
    const noResults = document.getElementById('noResults');
    
    // Sembunyikan pesan "tidak ada hasil" di awal
    noResults.style.display = 'none';

    // Initial load tidak perlu karena data sudah dimuat dari PHP
    
    // Real-time search handler
    searchInput.addEventListener('keyup', function(e) {
        const keyword = e.target.value.trim();
        loadAgents(keyword);
    });

    function loadAgents(keyword) {
        fetch(`ajax/agen.php?action=getAgents&keyword=${encodeURIComponent(keyword)}`)
            .then(response => response.json())
            .then(data => updateAgentList(data))
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
                                ${'★'.repeat(agent.rating)}${'☆'.repeat(5-agent.rating)}
                            </div>
                            <a href="detail-agen.php?id=${agent.id_agen}">Detail</a>
                        </div>
                    </div>
                </div>
            `;
            agentContainer.insertAdjacentHTML('beforeend', agentCard);
        });
    }
});