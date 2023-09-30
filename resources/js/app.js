import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

const channel = Echo.private(`App.Models.User.${userID}`);
channel.notification(({ title, invoice_id, user }) => {

    let countElement = $('#notifications_count');
    let currentCount = parseInt(countElement.text().match(/\d+/));

    if (isNaN(currentCount)) {
        currentCount = 0;
    }

    currentCount++;

    if (currentCount > 99) {
        currentCount = '99+';
    }

    countElement.text(`عدد الاشعارات الغير مقروة (${currentCount})`);


    // Create a new notification element
    const notificationList = document.getElementById('unreadNotifications');
    const notificationItem = document.createElement('div');
    notificationItem.className = 'main-notification-list Notification-scroll';

    const notificationLink = document.createElement('a');
    notificationLink.className = 'd-flex p-3 border-bottom';
    notificationLink.href = `/dashboard/admin/invoice_details/${invoice_id}`;

    const notifyImg = document.createElement('div');
    notifyImg.className = 'notifyimg bg-pink';
    notifyImg.innerHTML = '<i class="la la-file-alt text-white"></i>';

    const notificationContent = document.createElement('div');
    notificationContent.className = 'mr-3';

    const notificationLabel = document.createElement('h5');
    notificationLabel.className = 'notification-label mb-1';
    notificationLabel.textContent = `${title} ${user}`;

    const notificationSubtext = document.createElement('div');
    notificationSubtext.className = 'notification-subtext';
    notificationSubtext.textContent = new Date().toLocaleString();

    notificationContent.appendChild(notificationLabel);
    notificationContent.appendChild(notificationSubtext);

    notificationLink.appendChild(notifyImg);
    notificationLink.appendChild(notificationContent);

    notificationItem.appendChild(notificationLink);

    // Add the new notification to the top of the list
    notificationList.prepend(notificationItem);
});
