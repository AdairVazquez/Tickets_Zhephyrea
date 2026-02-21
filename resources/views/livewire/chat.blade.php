<div>
    <header>
        <style>
        /* Forzamos a que el cuerpo de la página no tenga scroll bajo ninguna circunstancia */
        html, body {
            height: 100vh;
            overflow: hidden !important;
            margin: 0 !important;
            padding: 0 !important;
            margin-bottom: 100px !important;
        }

        /* Opcional: Haz que el scroll interno sea más estético (delgado) */
        #chat-container::-webkit-scrollbar {
            width: 6px;
        }
        #chat-container::-webkit-scrollbar-thumb {
            background-color: #4b5563; /* color gray-600 */
            border-radius: 10px;
        }
    </style>
    </header>
    <div class="h-[calc(100vh-70px)] flex flex-col overflow-hidden bg-white dark:bg-neutral-900">
        <div class="flex-1 flex flex-col min-h-0">

            <div class="flex flex-col h-full border-b border-neutral-200 dark:border-neutral-700 bg-gray-200 dark:bg-gray-800">

                <div class="p-4 bg-white dark:bg-neutral-900 border-b dark:border-neutral-700">
                    <h1 class="dark:text-white text-black text-2xl font-bold">Chat del ticket #{{ $ticketId }}</h1>
                </div>

                <div id="chat-container" class="flex-1 overflow-y-auto p-6 space-y-4">
                    </div>

                <div class="pt-4 px-4 pb-8 bg-gray-900 border-t border-neutral-700">
                    <div class="flex items-center">
                        <input id='mensaje' type="text" placeholder="Escribe un mensaje..."
                            class="w-full rounded-lg p-3 bg-gray-700 text-white focus:outline-none" />
                        <button id="enviar" class="ml-3 text-white">@include('flux.icon.send-horizontal')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script type="module">
        // 1. LOS IMPORTS SIEMPRE VAN PRIMERO
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
        import {
            getDatabase,
            ref,
            push,
            onChildAdded,
            query,
            orderByChild,
            equalTo
        } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-database.js";

        // 2. CONFIGURACIÓN
        const firebaseConfig = {
            apiKey: "AIzaSyD8ASypj2828Isg8pt-FmmdHsDFX1XxUgE",
            authDomain: "tickets-sjs.firebaseapp.com",
            databaseURL: "https://tickets-sjs-default-rtdb.firebaseio.com",
            projectId: "tickets-sjs",
            storageBucket: "tickets-sjs.firebasestorage.app",
            messagingSenderId: "357550086903",
            appId: "1:357550086903:web:fc4d9f2233ccf91daf11f2"
        };

        // 3. INICIALIZACIÓN (Después de los imports)
        const app = initializeApp(firebaseConfig);
        const db = getDatabase(app);

        // 4. VARIABLES DE ENTORNO (Blade a JS)
        const ticketId = {{ $ticketId }};
        const idUsuario = {{ $id_usuario }};
        const nombre = '{{ $nombre_usuario }}';
        const rol = '{{ $rol }}';
        const concatenado = `${nombre} | ${rol}`;
        const inicioChat = Date.now();

        // 5. REFERENCIAS DEL DOM
        const mensajesDiv = document.getElementById('chat-container');
        const mensajeInput = document.getElementById('mensaje');
        const enviarBtn = document.getElementById('enviar');

        // 6. LÓGICA DE FIREBASE
        const chatRef = ref(db, 'chats/mensajes_globales');
        const ticketQuery = query(chatRef, orderByChild('ticketId'), equalTo(ticketId));

        // Solicitar permisos de notificación (solo si es necesario)
        const solicitarPermisoNotificaciones = async () => {
            if ("Notification" in window && Notification.permission === "default") {
                await Notification.requestPermission();
            }
        };

        // Escuchar nuevos mensajes
        onChildAdded(ticketQuery, (data) => {
            const msg = data.val();
            const esMio = idUsuario == msg.emisor_id;
            const div = document.createElement('div');

            div.className = esMio ? "flex items-start justify-end" : "flex items-start";
            div.innerHTML = `
                <div class="max-w-xs ${esMio ? 'bg-blue-600 rounded-br-none' : 'bg-gray-700 rounded-bl-none'} text-white p-3 rounded-2xl shadow">
                    <span class="block text-[10px] text-gray-300 font-bold mb-1">${msg.datos}</span>
                    <p class="text-sm">${msg.mensaje}</p>
                    <span class="block text-[10px] text-gray-400 text-right mt-1">${msg.hora}</span>
                </div>
            `;

            mensajesDiv.appendChild(div);
            mensajesDiv.scrollTop = mensajesDiv.scrollHeight;

            // Notificaciones para mensajes de otros
            if (!esMio && msg.timestamp > inicioChat) {
                if (Notification.permission === "granted") {
                    new Notification(`Ticket #${ticketId}`, { body: `${msg.datos}: ${msg.mensaje}` });
                }
            }
        });

        // Función para enviar mensaje
        const enviarMensaje = () => {
    const mensaje = mensajeInput.value.trim();
    if (mensaje === '') return;

    const now = new Date();
    const horaFormateada = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    // AGREGAMOS MANEJO DE ERRORES AQUÍ
    push(chatRef, {
        emisor_id: idUsuario,
        mensaje: mensaje,
        timestamp: Date.now(),
        hora: horaFormateada,
        datos: concatenado,
        ticketId: ticketId
    })
    .then(() => {
        console.log("Mensaje enviado con éxito");
        mensajeInput.value = '';
    })
    .catch((error) => {
        console.error("Error detallado de Firebase:", error);
        alert("No tienes permiso para escribir en la base de datos. Revisa las Reglas en la Consola.");
    });
};

        // Eventos
        enviarBtn.addEventListener('click', enviarMensaje);
        mensajeInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                enviarMensaje();
            }
        });
    </script>
    @endpush
</div>
