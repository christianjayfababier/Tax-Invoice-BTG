
// Enable Bootstrap validation
(function () {
    'use strict';

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation');

    // Loop over them and prevent submission if they are invalid
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);
    });
})();


document.addEventListener("DOMContentLoaded", () => {
    const toggleSidebar = document.getElementById("toggleSidebar");
    const sidebar = document.querySelector(".sidebar");

    toggleSidebar.addEventListener("click", () => {
        sidebar.style.width = sidebar.style.width === "0px" ? "250px" : "0px";
    });

});

function fetchAdminNotifications() {
    fetch('../notifications/fetch_admin_notifications.php')
        .then(response => response.json())
        .then(data => {
            const notificationsMenu = document.getElementById('notificationsMenu');
            const notificationCount = document.getElementById('notificationCount');
            notificationsMenu.innerHTML = '';
            
            if (data.length > 0) {
                notificationCount.textContent = data.length;
                data.forEach(notification => {
                    const item = document.createElement('li');
                    item.innerHTML = `<a class="dropdown-item" href="#">${notification.message}</a>`;
                    notificationsMenu.appendChild(item);
                });
            } else {
                notificationsMenu.innerHTML = '<li class="dropdown-item">No new notifications</li>';
            }
        });
}

document.addEventListener('DOMContentLoaded', () => {
    fetchAdminNotifications();
});
