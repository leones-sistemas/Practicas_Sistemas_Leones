importScripts(
    'https://www.gstatic.com/firebasejs/10.13.2/firebase-app-compat.js'
);

importScripts(
    'https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging-compat.js'
);

// CONFIGURACIÓN FIREBASE

firebase.initializeApp({

    apiKey: "AIzaSyBqdYA52oa1HRZOEZB5W8pwLgePkhFx7-k",

    authDomain: "leones-grupo-01.firebaseapp.com",

    projectId: "leones-grupo-01",

    messagingSenderId: "392702464974",

    appId: "1:392702464974:web:c97967467b0296f424d19f"

});

// INICIALIZAR MESSAGING

const messaging = firebase.messaging();

// RECIBIR MENSAJES EN SEGUNDO PLANO

messaging.onBackgroundMessage((payload) => {

    console.log(
        '[firebase-messaging-sw.js] Mensaje recibido:',
        payload
    );

    const notificationTitle =
        payload.data.title;

    const notificationOptions = {

        body:
            payload.data.body,

        icon:
            './icon.png',

        badge:
            './icon.png',
/* 
        image:
            './banner.jpg',
 */
        vibrate:
            [200, 100, 200],

        requireInteraction:
            true,

        data: {

            url:
                'http://127.0.0.1/push/'

        }

    };

    self.registration.showNotification(

        notificationTitle,

        notificationOptions

    );

});

// CLICK EN LA NOTIFICACIÓN

self.addEventListener(

    'notificationclick',

    function(event) {

        event.notification.close();

        const urlToOpen =
            event.notification.data.url;

        event.waitUntil(

            clients.matchAll({

                type: 'window',

                includeUncontrolled: true

            }).then((clientList) => {

                // SI YA EXISTE UNA VENTANA ABIERTA

                for (const client of clientList) {

                    if(client.url === urlToOpen
                    && 'focus' in client) {

                        return client.focus();

                    }

                }

                // SI NO EXISTE, ABRIR NUEVA

                if(clients.openWindow) {

                    return clients.openWindow(
                        urlToOpen
                    );

                }

            })

        );

    }

);