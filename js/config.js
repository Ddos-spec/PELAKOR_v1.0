const CONFIG = {
    // API endpoints
    API: {
        DETAIL: 'ajax/get-detail.php',
        EXPORT: 'ajax/handle-export.php',
        UPDATE: 'ajax/update-status.php'
    },
    
    // Pagination
    ITEMS_PER_PAGE: 10,
    
    // Cache duration in minutes
    CACHE_DURATION: 5,
    
    // Debounce delays
    DELAYS: {
        FILTER: 300,
        SEARCH: 500
    }
};

Object.freeze(CONFIG);
