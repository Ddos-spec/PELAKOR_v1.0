document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('keyword');
    const agentContainer = document.getElementById('container');
    const agentList = document.querySelector('.row.card');
    
    // Initial load
    loadAgents('');

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
        if (!agentList) return;
        
        agentList.innerHTML = '';
        
        if (agents.length === 0) {
            agentList.innerHTML = '<div class="col s12 center"><p>Tidak ada hasil yang ditemukan</p></div>';
            return;
        }

        agents.forEach(agent => {
            const agentCard = `
                <div class="col s12 m4">
                    <div class="icon-block center">
                        <h2 class="center light-blue-text">
                            <a href="detail-agen.php?id=${agent.id_agen}">
                                <img src="img/agen/${agent.foto}" class="circle responsive-img" width="60%" />
                            </a>
                        </h2>
                        <h5 class="center">
                            <a href="detail-agen.php?id=${agent.id_agen}">${agent.nama_laundry}</a>
                        </h5>
                        <div class="rating-container">
                            ${'★'.repeat(agent.rating)}${'☆'.repeat(5-agent.rating)}
                        </div>
                        <p class="light">
                            <i class="material-icons tiny">location_on</i> ${agent.alamat}, ${agent.kota}<br/>
                            <i class="material-icons tiny">phone</i> ${agent.telp}
                        </p>
                    </div>
                </div>
            `;
            agentList.insertAdjacentHTML('beforeend', agentCard);
        });
    }
});