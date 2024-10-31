// static/js/script.js
document.addEventListener("DOMContentLoaded", function () {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.display = 'block'; // Muestra el mensaje
        setTimeout(() => {
            alert.style.display = 'none'; // Oculta el mensaje después de 3 segundos
        }, 5000); // Cambia 3000 por el tiempo que desees en milisegundos
    });
});
