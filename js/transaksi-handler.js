// Namespace untuk modul transaksi
const TransactionManager = {
    // Cache untuk data
    cache: {},
    
    // Event handlers
    handlers: {
        filterTipe(value) {
            $('.data-row').each(function() {
                $(this).toggle(
                    value === 'all' || 
                    $(this).data('tipe') === value
                );
            });
        },

        async showDetail(id) {
            if (this.cache[id]) {
                this.renderDetail(this.cache[id]);
                return;
            }

            try {
                const response = await fetch(`ajax/get-detail.php?id=${id}`);
                const data = await response.json();
                this.cache[id] = data;
                this.renderDetail(data);
            } catch (error) {
                Swal.fire('Error', 'Gagal memuat detail', 'error');
            }
        },

        async exportData(type, filters) {
            try {
                const response = await fetch('ajax/handle-export.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ type, filters })
                });

                if (!response.ok) throw new Error('Export failed');

                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `transaksi_${type}_${new Date().toISOString()}.${type}`;
                a.click();
            } catch (error) {
                Swal.fire('Error', 'Gagal mengexport data', 'error');
            }
        },

        async showExportDialog() {
            const { value: filters } = await Swal.fire({
                title: 'Export Data',
                html: `
                    <div class="input-field">
                        <input type="date" id="date_start" class="swal2-input">
                        <label for="date_start">Tanggal Mulai</label>
                    </div>
                    <div class="input-field">
                        <input type="date" id="date_end" class="swal2-input">
                        <label for="date_end">Tanggal Akhir</label>
                    </div>
                    <div class="input-field">
                        <select id="tipe" class="swal2-input">
                            <option value="">Semua Tipe</option>
                            <option value="kiloan">Kiloan</option>
                            <option value="satuan">Satuan</option>
                        </select>
                    </div>
                `,
                confirmButtonText: 'Preview',
                showCancelButton: true,
                preConfirm: () => {
                    return {
                        date_start: $('#date_start').val(),
                        date_end: $('#date_end').val(),
                        tipe: $('#tipe').val()
                    }
                }
            });

            if (filters) {
                const preview = await this.getExportPreview(filters);
                if(preview.success) {
                    this.showExportPreview(preview.data, filters);
                }
            }
        },

        async getExportPreview(filters) {
            try {
                const response = await fetch('ajax/handle-export.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        type: 'preview',
                        filters 
                    })
                });
                return await response.json();
            } catch(error) {
                Swal.fire('Error', 'Gagal memuat preview', 'error');
                return { success: false };
            }
        },

        async showExportPreview(data, filters) {
            const { value: exportType } = await Swal.fire({
                title: 'Preview Export',
                html: this.generatePreviewTable(data),
                confirmButtonText: 'Export PDF',
                showDenyButton: true,
                showCancelButton: true,
                denyButtonText: 'Export Excel'
            });

            if (exportType) {
                this.exportData(
                    exportType === true ? 'pdf' : 'excel',
                    filters
                );
            }
        }
    },

    // UI updaters
    ui: {
        renderDetail(data) {
            $('#modalLoader').hide();
            $('#detailContent').show();
            
            $('#namaPelanggan').text(data.nama_pelanggan);
            $('#namaAgen').text(data.nama_laundry);
            $('#tanggalTransaksi').text(data.tanggal);
            $('#statusCucian').text(data.status);
            $('#detailItems').html(data.items);
            $('#totalAmount').text(formatRupiah(data.total));
        },

        updatePagination(page) {
            $('.pagination li').removeClass('active');
            $(`.pagination li a[href="?page=${page}"]`).parent().addClass('active');
        }
    },

    // Utility functions
    utils: {
        formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        },

        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        generatePreviewTable(data) {
            let table = '<table class="striped"><thead><tr>';
            for (const key in data[0]) {
                table += `<th>${key}</th>`;
            }
            table += '</tr></thead><tbody>';
            data.forEach(row => {
                table += '<tr>';
                for (const key in row) {
                    table += `<td>${row[key]}</td>`;
                }
                table += '</tr>';
            });
            table += '</tbody></table>';
            return table;
        }
    },

    // Initialize all event listeners
    init() {
        // Filter handlers
        $('#filter_tipe').on('change', (e) => 
            this.handlers.filterTipe(e.target.value)
        );

        // Modal handlers
        $('.modal').modal();

        // Export handlers
        $('.export-btn').on('click', (e) => 
            this.handlers.showExportDialog()
        );

        // Pagination handlers
        $('.pagination a').on('click', (e) => {
            e.preventDefault();
            const page = new URLSearchParams($(e.target).attr('href')).get('page');
            this.ui.updatePagination(page);
            this.loadPage(page);
        });
    }
};

// Initialize on document ready
$(document).ready(() => TransactionManager.init());

// Export functions for external use
window.showDetailTransaksi = (id) => TransactionManager.handlers.showDetail(id);
window.filterTipe = (value) => TransactionManager.handlers.filterTipe(value);
window.exportData = (type) => TransactionManager.handlers.exportData(type);
