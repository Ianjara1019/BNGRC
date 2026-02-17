document.getElementById('villeFilter').addEventListener('change', function() {
    const villeId = this.value;
    const url = villeId ? `?ville_id=${villeId}` : '';
    window.location.href = window.location.pathname + url;
});