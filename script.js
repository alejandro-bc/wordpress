// Autenticación básica
const authHeader = 'Basic YWxleDpLb1ltIHdSQm4gSlNqUCAwTzZ2IFdpbjEgT3BnSw==';

// Función para actualizar el contenido de una página específica en WordPress
async function actualizarTelefonoEnWordpress(pageId, nuevoTelefono) {
    try {
        const response = await fetch(`http://wordpress.local/wp-json/wp/v2/pages/${pageId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': authHeader,
            },
            body: JSON.stringify({ content: `<p>Teléfono actualizado: ${nuevoTelefono}</p>` }),
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'Error desconocido al actualizar el contenido');
        }

        const data = await response.json();
        console.log('Éxito:', data);
        alert('Teléfono actualizado correctamente.');
    } catch (error) {
        console.error('Error al actualizar el teléfono:', error.message);
        alert(`Error al actualizar el contenido: ${error.message}`);
    }
}

// Función para publicar datos en WordPress
async function publicarEnWordpressConDatos(titulo, contenido) {
    try {
        const response = await fetch('http://wordpress.local/wp-json/wp/v2/posts', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': authHeader,
            },
            body: JSON.stringify({
                title: titulo,
                content: contenido,
                status: 'publish',
            }),
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'Error desconocido al crear la publicación');
        }

        const data = await response.json();
        console.log('Éxito:', data);
        alert('Publicación creada con éxito.');
    } catch (error) {
        console.error('Error al conectarse con la API de WordPress:', error.message);
        alert(`Error al crear la publicación: ${error.message}`);
    }
}

// Función para publicar contenido manual desde el formulario
async function publicarEnWordpress() {
    const titulo = document.getElementById("titulo").value;
    const contenido = document.getElementById("contenido").value;

    if (!titulo || !contenido) {
        alert("Por favor, rellena ambos campos.");
        return;
    }

    try {
        await publicarEnWordpressConDatos(titulo, contenido);
    } catch (error) {
        console.error('Error al publicar en WordPress:', error.message);
        alert(`Error al intentar crear la publicación: ${error.message}`);
    }
}

// Función para consultar la API externa y publicar en WordPress
async function consultarYPublicar() {
    try {
        // Consulta la API externa
        const response = await fetch('https://www.el-tiempo.net/api/json/v2/provincias/30');
        const data = await response.json();

        console.log("Respuesta de la API externa:", data);

        // Extraer información relevante
        const nombreProvincia = data.provincia?.NOMBRE_PROVINCIA || "Provincia desconocida";
        const descripcionHoy = data.today?.p || "Descripción del tiempo no disponible.";
        const descripcionManana = data.tomorrow?.p || "Descripción del tiempo para mañana no disponible.";

        // Obtener la fecha actual
        const fechaActual = obtenerFechaActual();

        // Crear título y contenido con los datos de la API
        const titulo = `El tiempo en la provincia de ${nombreProvincia} - ${fechaActual}`;
        const contenido = `
            <h2>Pronóstico de hoy</h2>
            <p>${descripcionHoy}</p>
            <h2>Pronóstico de mañana</h2>
            <p>${descripcionManana}</p>
        `;

        // Publicar en WordPress
        await publicarEnWordpressConDatos(titulo, contenido);
    } catch (error) {
        console.error('Error al consultar la API externa:', error.message);
        alert(`Error al obtener los datos de la API externa: ${error.message}`);
    }
}

// Función para obtener la fecha actual en formato DD/MM/YYYY
function obtenerFechaActual() {
    const hoy = new Date();
    const dia = hoy.getDate().toString().padStart(2, '0');
    const mes = (hoy.getMonth() + 1).toString().padStart(2, '0');
    const anio = hoy.getFullYear();
    return `${dia}/${mes}/${anio}`;
}
