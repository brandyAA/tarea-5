# Portal Web en PHP con APIs Externas

Este proyecto es un portal web desarrollado en PHP que consume varias APIs externas para mostrar información dinámica, como predicción de género, edad, clima, universidades, entre otros.

---

## Requisitos previos

- Servidor local con PHP instalado (recomendado XAMPP, WAMP, MAMP o similar)
- PHP versión 7.4 o superior
- Extensión **cURL** habilitada en PHP (para realizar solicitudes HTTP a las APIs)
- Conexión a internet para acceder a las APIs externas
- Navegador web moderno

---

## Instalación y ejecución

1. **Clonar o descargar** el repositorio y copiar los archivos en la carpeta raíz de tu servidor local.  
   Por ejemplo:  
   - En XAMPP: `C:\xampp\htdocs\portal-php`  
   - En WAMP: `C:\wamp64\www\portal-php`  

2. **Verificar que la extensión cURL esté habilitada**  
   - Abre el archivo `php.ini`  
   - Busca la línea `;extension=curl` y elimina el punto y coma (`;`) para habilitarla  
   - Reinicia el servidor Apache para aplicar cambios  

3. **Abrir el navegador** y navegar a:  



///------------------------------------------------------------------------------------------------------------------------------------------------------------------------------



o la ruta donde hayas copiado los archivos.  

4. **Usar el portal**  
- En cada sección, ingresar los datos solicitados (por ejemplo, nombre para predicción de género o país para universidades)  
- Visualizar los resultados que se obtienen desde las APIs externas  

---

## Consideraciones

- Algunas APIs pueden requerir clave (API key). Si es así, asegúrate de obtenerla y configurarla en el código donde se indique.  
- La conexión a internet debe estar activa para que el portal pueda obtener datos en tiempo real.  
- Si tienes problemas con SSL al hacer peticiones, revisa la configuración de PHP para cURL o usa el parámetro `CURLOPT_SSL_VERIFYPEER` en `false` durante desarrollo local.  

---

## Estructura del proyecto

- `index.php` — Página principal y formulario  
- `genero.php` — Predicción de género usando API Genderize  
- `edad.php` — Predicción de edad con API Agify  
- `clima.php` — Clima con API OpenWeather  
- `universidades.php` — Universidades de un país con API Hipolabs  
- `pokemon.php` — Información de Pokémon con PokeAPI  
- `noticias.php` — Noticias desde WordPress REST API  
- `monedas.php` — Conversión de monedas con ExchangeRate API  
- `pais.php` — Datos de un país con REST Countries API  
- `chiste.php` — Generador de chistes con Official Joke API  

*(Asegúrate de ajustar los nombres de archivo según tu proyecto)*

---

## Autor

Brandy Alcantara Arias

---

Si tienes alguna duda o problema, puedes contactarme para ayudarte.

