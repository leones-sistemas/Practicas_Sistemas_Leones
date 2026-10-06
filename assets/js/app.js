import { initializeApp }

from "https://www.gstatic.com/firebasejs/10.13.2/firebase-app.js";

import {

    getMessaging,

    getToken,

    onMessage

}

from

"https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging.js";

// CONFIGURACIÓN FIREBASE

const firebaseConfig = {

    apiKey: "AIzaSyBqdYA52oa1HRZOEZB5W8pwLgePkhFx7-k",

    authDomain: "leones-grupo-01.firebaseapp.com",

    projectId: "leones-grupo-01",

    messagingSenderId: "392702464974",

    appId: "1:392702464974:web:c97967467b0296f424d19f"

};

// INICIALIZAR FIREBASE

const app = initializeApp(firebaseConfig);

const messaging = getMessaging(app);

// FUNCIÓN PRINCIPAL

async function iniciarNotificaciones() {

    try {

        // VERIFICAR SOPORTE

        if(!("serviceWorker" in navigator)) {

            alert(
                "Service Worker no soportado"
            );

            return;

        }

        if(!("PushManager" in window)) {

            alert(
                "Push API no soportada"
            );

            return;

        }

        // SOLICITAR PERMISOS

        const permission =

        await Notification.requestPermission();

        console.log(
            "PERMISO:",
            permission
        );

        if(permission !== "granted") {

            alert(
                "Permiso denegado"
            );

            return;

        }

        // REGISTRAR SERVICE WORKER

        const registration =

        await navigator.serviceWorker.register(

            "./firebase-messaging-sw.js"

        );

        console.log(
            "SW REGISTRADO"
        );

        // ESPERAR ACTIVACIÓN

        await navigator.serviceWorker.ready;

        console.log(
            "SW ACTIVO"
        );

        // OBTENER TOKEN FIREBASE

        const token = await getToken(

            messaging,

            {

                vapidKey:

                "BCJYlSfxcXoNz6ygaitusdYF_37eSMRzPYUDsyCwNAZ7h6NRltVoYPH640a_3sQ27UlRRN4asMA2xG76ChWlzD0",

                serviceWorkerRegistration:
                    registration

            }

        );

        // VALIDAR TOKEN

        if(!token) {

            alert(
                "No se pudo generar token"
            );

            return;

        }

        console.log(
            "TOKEN:"
        );

        console.log(token);

        // ENVIAR TOKEN AL BACKEND

        const response = await fetch(

            "https://leonesgrupoinmobiliario.com/push/guardar_token",

            {

                method: "POST",

                headers: {

                    "Content-Type":
                    "application/json"

                },

                body: JSON.stringify({

                    token: token

                })

            }

        );

        const result =
        await response.json();

        console.log(result);

        // RESPUESTA BACKEND

        if(result.success) {

            console.log(
                "TOKEN GUARDADO"
            );

        }

        else {

            console.log(
                "ERROR AL GUARDAR TOKEN"
            );

        }

    }

    catch(error) {

        console.error(error);

    }

}

// INICIAR SISTEMA

iniciarNotificaciones();

// MENSAJES EN PRIMER PLANO

/* onMessage(

    messaging,

    (payload) => {

        console.log(
            "MENSAJE RECIBIDO:"
        );

        console.log(payload);

        // MOSTRAR NOTIFICACIÓN WINDOWS

        new Notification(

            payload.data.title,

            {

                body:
                    payload.data.body,

                icon:
                    "./icon.png",

                badge:
                    "./icon.png",

                image:
                    "./banner.jpg",

                vibrate:
                    [200, 100, 200],

                requireInteraction:
                    true

            }

        );

    }

); */

onMessage(
    messaging,
    async (payload) => {

        console.log("MENSAJE RECIBIDO:");
        console.log(payload);

        const registration =
            await navigator.serviceWorker.ready;

        await registration.showNotification(
            payload.data?.title || "Nueva notificación",
            {
                body:
                    payload.data?.body || "",

                icon:
                    "./icon.png",

                badge:
                    "./icon.png",

                image:
                    "./banner.jpg",

                vibrate:
                    [200, 100, 200],

                requireInteraction:
                    true,

                data: {
                    url:
                        'https://leonesgrupoinmobiliario.pe/comparativo/'
                }
            }
        );

    }
);
