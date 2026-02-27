<div>
    <header>
        <style>
            /* Forzamos a que el cuerpo de la página no tenga scroll bajo ninguna circunstancia */
            html,
            body {
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
                background-color: #4b5563;
                /* color gray-600 */
                border-radius: 10px;
            }
        </style>
    </header>
    <div class="h-[calc(100vh-70px)] flex flex-col overflow-hidden bg-white dark:bg-neutral-900">
        <div class="flex-1 flex flex-col min-h-0">

            <div
                class="flex flex-col h-full border-b border-neutral-200 dark:border-neutral-700 bg-gray-200 dark:bg-gray-800">

                <div class="p-4 bg-white dark:bg-neutral-900 border-b dark:border-neutral-700">
                    <h1 class="dark:text-white text-black text-2xl font-bold">Chat del ticket #{{ $ticketId }}</h1>
                </div>

                <div id="chat-container" class="flex-1 overflow-y-auto p-6 space-y-4">
                </div>

                <div class="pt-4 px-4 pb-8 bg-gray-900 border-t border-neutral-700">
                    <div class="flex items-center">
                        <input id='mensaje' type="text" placeholder="Escribe un mensaje..."
                            class="w-full rounded-lg p-3 bg-gray-700 text-white focus:outline-none" />
                        <label class="cursor-pointer text-gray-400 hover:text-white">
                            @include('flux.icon.paperclip') <input type="file" id="input-archivo" class="hidden"
                                accept="image/*,.pdf,.doc" />
                        </label>
                        <button id="enviar" class="ml-3 text-white">@include('flux.icon.send-horizontal')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script type="module">
            // 1. IMPORTS (Todos juntos al inicio)
            import {
                initializeApp
            } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
            import {
                getDatabase,
                ref,
                push,
                onChildAdded,
                query,
                orderByChild,
                equalTo
            } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-database.js";
            import {
                getStorage,
                ref as sRef,
                uploadBytes,
                getDownloadURL
            } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-storage.js";

            // 2. CONFIGURACIÓN E INICIALIZACIÓN
            const firebaseConfig = {
                apiKey: "AIzaSyD8ASypj2828Isg8pt-FmmdHsDFX1XxUgE",
                authDomain: "tickets-sjs.firebaseapp.com",
                databaseURL: "https://tickets-sjs-default-rtdb.firebaseio.com",
                projectId: "tickets-sjs",
                storageBucket: "gs://tickets-sjs.firebasestorage.app",
                messagingSenderId: "357550086903",
                appId: "1:357550086903:web:fc4d9f2233ccf91daf11f2"
            };

            const app = initializeApp(firebaseConfig);
            const db = getDatabase(app);
            const storage = getStorage(app);

            // 3. VARIABLES DE ENTORNO (Sintaxis ultra-segura)
            const ticketId = Number(@json($ticketId));
            const idUsuario = Number(@json($id_usuario));
            const nombre = String(@json($nombre_usuario) || 'Usuario');
            const rol = String(@json($rol) || 'Sin Rol');
            const concatenado = `${nombre} | ${rol}`;
            const inicioChat = Date.now();

            // 4. REFERENCIAS DEL DOM
            const mensajesDiv = document.getElementById('chat-container');
            const mensajeInput = document.getElementById('mensaje');
            const enviarBtn = document.getElementById('enviar');
            const inputArchivo = document.getElementById('input-archivo');

            const chatRef = ref(db, 'chats/mensajes_globales');
            const ticketQuery = query(chatRef, orderByChild('ticketId'), equalTo(ticketId));

            // 5. FUNCIÓN UNIFICADA PARA ENVIAR (Texto o Archivos)
            const enviarMensaje = (contenido = null, tipo = 'texto') => {
                const texto = contenido || mensajeInput.value.trim();
                if (!texto) return;

                const now = new Date();
                const horaFormateada = now.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                push(chatRef, {
                        emisor_id: idUsuario,
                        mensaje: texto,
                        tipo: tipo,
                        timestamp: Date.now(),
                        hora: horaFormateada,
                        datos: concatenado,
                        ticketId: ticketId
                    })
                    .then(() => {
                        if (!contenido) mensajeInput.value = '';
                    })
                    .catch((error) => {
                        console.error("Error Firebase:", error);
                        alert("Error al enviar. Revisa los permisos.");
                    });
            };

            // 6. ESCUCHAR NUEVOS MENSAJES (Solo uno es necesario)
            onChildAdded(ticketQuery, (data) => {
                const msg = data.val();
                const esMio = idUsuario == msg.emisor_id;
                const div = document.createElement('div');
                div.className = esMio ? "flex items-start justify-end" : "flex items-start";

                let contenidoHTML = `<p class="text-sm">${msg.mensaje}</p>`;

                if (msg.tipo === 'imagen') {
                    contenidoHTML =
                        `<img src="${msg.mensaje}" class="rounded-lg max-w-full h-auto cursor-pointer border border-gray-600" onclick="window.open('${msg.mensaje}')">`;
                } else if (msg.tipo === 'archivo') {
                    contenidoHTML =
                        `<a href="${msg.mensaje}" target="_blank" class="flex items-center space-x-2 text-blue-400 underline text-sm">Ver documento adjunto</a>`;
                }

                div.innerHTML = `
            <div class="max-w-xs ${esMio ? 'bg-blue-600 rounded-br-none' : 'bg-gray-700 rounded-bl-none'} text-white p-3 rounded-2xl shadow mb-2">
                <span class="block text-[10px] text-gray-300 font-bold mb-1">${msg.datos}</span>
                ${contenidoHTML}
                <span class="block text-[10px] text-gray-400 text-right mt-1">${msg.hora}</span>
            </div>
        `;

                mensajesDiv.appendChild(div);
                mensajesDiv.scrollTop = mensajesDiv.scrollHeight;

                if (!esMio && msg.timestamp > inicioChat) {
                    if (Notification.permission === "granted") {
                        new Notification(`Ticket #${ticketId}`, {
                            body: `${msg.datos}: Envió un ${msg.tipo}`
                        });
                    }
                }
            });

            // 7. EVENTOS
            inputArchivo.addEventListener('change', async (e) => {
                const archivo = e.target.files[0];
                if (!archivo) return;

                const nombreArchivo = `${Date.now()}-${archivo.name}`;
                const archivoRef = sRef(storage, `chats/tickets/${ticketId}/${nombreArchivo}`);

                try {
                    const snapshot = await uploadBytes(archivoRef, archivo);
                    const url = await getDownloadURL(snapshot.ref);
                    enviarMensaje(url, archivo.type.includes('image') ? 'imagen' : 'archivo');
                } catch (error) {
                    console.error("Error al subir:", error);
                }
            });

            enviarBtn.addEventListener('click', () => enviarMensaje());
            mensajeInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    enviarMensaje();
                }
            });
        </script>
    @endpush

</div>
