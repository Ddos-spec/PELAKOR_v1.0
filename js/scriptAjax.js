var keyword = document.getElementById('keyword');
var container = document.getElementById('container');
var debounceTimer;

keyword.addEventListener('keyup', function (event) {
    // Abaikan tombol kontrol (misalnya, Ctrl, Shift, Alt, dll.)
    if (event.key.length > 1) return; 

    // Hapus timer sebelumnya
    clearTimeout(debounceTimer);
    
    // Set timer baru
    debounceTimer = setTimeout(function () {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                container.innerHTML = xhr.responseText;
            }
        }
        xhr.open('GET', 'ajax/agen.php?keyword=' + encodeURIComponent(keyword.value), true);
        xhr.send();
    }, 300); // tunggu 300ms setelah berhenti mengetik
});
