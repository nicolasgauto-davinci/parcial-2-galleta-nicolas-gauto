const apiUrl = 'http://api.weatherapi.com/v1/current.json?q=-34.6037,-58.3816&lang=es&key=01e325166d2a4718ac4181537262806';

const cajaClima = document.getElementById("clima");

fetch(apiUrl)
.then(response => {
    if(!response.ok){
        throw new Error(`Rechazo del servidor: ${response.status}`);
    }
    return response.json();
})
.then(data => {
    const temperatura = Math.round(data.current.temp_c);
    const descripcion = data.current.condition.text;

    cajaClima.innerHTML = `<p>Clima en CABA: ${temperatura}°C, ${descripcion}.</p>`
})
.catch(error =>{
    console.error("Falla en la peticion AJAX: ", error);
    cajaClima.innerHTML= `<p>No se pudo obtener el clima actual</p>`
});