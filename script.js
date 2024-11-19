async function publicarEnWordpress() {
    const titulo = document.getElementById("titulo").value;
    const contenido = document.getElementById("contenido").value;

    if (!titulo || !contenido) {
        alert("Por favor, rellena ambos campos.");
        return;
    }

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
        alert("Error al conectarse con la API.");
        console.error("Error de red:", error);
    }
}
