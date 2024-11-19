// Función para publicar datos en WordPress
async function publicarEnWordpressConDatos(titulo, contenido) {
    try {
        const response = await fetch('publicar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ titulo, contenido }),
        });

        if (response.ok) {
            const data = await response.json();
            alert("Publicación creada con éxito.");
            console.log("Respuesta:", data);
        } else {
            const error = await response.json();
            alert("Error al crear la publicación.");
            console.error("Error:", error);
        }
    } catch (error) {
        console.error("Error al conectarse con la API de WordPress:", error);
        alert("Error al conectarse con la API.");
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
        alert("Hubo un error al intentar crear la publicación.");
        console.error(error);
    }
}

// Función para consultar la API externa y publicar en WordPress
async function consultarYPublicar() {
    try {
        // Consulta la API externa
        const response = await fetch('https://www.el-tiempo.net/api/json/v2/provincias/30');
        const data = await response.json();

        // Mostrar la respuesta completa para depuración
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
        console.error("Error al consultar la API externa:", error);
        alert("Hubo un error al obtener los datos de la API externa. Revisa la consola para más detalles.");
    }
}

// Función para obtener la fecha actual en formato DD/MM/YYYY
function obtenerFechaActual() {
    const hoy = new Date();
    const dia = hoy.getDate().toString().padStart(2, '0'); // Asegura que sea 2 dígitos
    const mes = (hoy.getMonth() + 1).toString().padStart(2, '0'); // Los meses empiezan en 0
    const anio = hoy.getFullYear();
    return `${dia}/${mes}/${anio}`;
}
