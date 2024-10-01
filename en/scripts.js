const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.querySelector('.container');

signUpButton.addEventListener('click', () => {
    container.classList.add('right-panel-active');
    document.getElementById("reg_div").style.display = "none";
    document.getElementById("log_div").style.display = "block";
});

signInButton.addEventListener('click', () => {
    container.classList.remove('right-panel-active');
    document.getElementById("reg_div").style.display = "block";
    document.getElementById("log_div").style.display = "none";
});



setTimeout(function() {
    var alertDiv = document.querySelector('.alert');
    if (alertDiv) {
        alertDiv.style.display = 'none';
    }
}, 3000); // 3000 milisaniye = 3 saniye
function switchLanguage(language) {
    // URL'nin farklı bölümlerini alalım
    let protocol = window.location.protocol; // http veya https
    let hostname = window.location.hostname; // localhost veya IP adresi
    let port = window.location.port; // Port numarası
    let pathname = window.location.pathname; // URL'deki dosya yolu

    // Eğer bir port numarası varsa, onu da ekleyelim
    let baseUrl = `${protocol}//${hostname}${port ? ':' + port : ''}`;

    // Eğer dil 'en' ise
    if (language === 'en') {
        // Eğer 'en/' içermiyorsa ekle
        if (!pathname.includes('/en/')) {
            let newUrl = pathname.replace(/\/$/, ''); // Sonunda / varsa kaldır
            let lastSlashIndex = newUrl.lastIndexOf('/'); // Dosya isminin yerini bul
            // Dosya ismi öncesine /en ekle
            newUrl = newUrl.slice(0, lastSlashIndex) + '/en' + newUrl.slice(lastSlashIndex);
            window.location.href = `${baseUrl}${newUrl}`;
        }
    }
    // Eğer dil 'tr' ise
    else if (language === 'tr') {
        // Eğer 'en/' varsa kaldır
        if (pathname.includes('/en/')) {
            let newUrl = pathname.replace('/en/', '/');
            window.location.href = `${baseUrl}${newUrl}`;
        }
    }
}





