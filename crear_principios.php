<?php
// Configuración del acceso a MediaWiki
$wiki_url = "http://localhost:8888/api.php"; // URL de la API de MediaWiki
$username = "Admin"; // Tu usuario
$password = "Universitat1*"; // Tu contraseña
$cookie_file = tempnam(sys_get_temp_dir(), 'cookie');

// Datos de Principios y Subprincipios
$principios = [
    "Principio_1" => [
        "title" => "Principio 1: Perceptible",
        "subpages" => [
            "1.1_Texto_Alternativo" => "Pauta 1.1: Proporciona texto alternativo para el contenido que no sea textual.",
            "1.2_Contenido_Multimedia" => "Pauta 1.2: Proporciona alternativas sincronizadas para contenidos multimedia.",
            "1.3_Adaptable" => "Pauta 1.3: Crear contenido adaptable sin perder información ni estructura.",
            "1.4_Distinguible" => "Pauta 1.4: Facilitar a los usuarios ver y escuchar el contenido."
        ]
    ],
    "Principio_2" => [
        "title" => "Principio 2: Operable",
        "subpages" => [
            "2.1_Teclado_Accesible" => "Pauta 2.1: Poder controlar todas las funciones desde el teclado.",
            "2.2_Tiempo_Suficiente" => "Pauta 2.2: Proporciona tiempo suficiente para leer y utilizar el contenido.",
            "2.3_Reacciones_físicas" => "Pauta 2.3: No diseñar contenido que cause ataques epilépticos.",
            "2.4_Navegable" => "Pauta 2.4: Proporciona ayuda para navegar y buscar contenido.",
            "2.5_Modalidades_de_los_input" => "Pauta 2.5: Especificar los tipos de los input correctamente.",
        ]
    ],
    "Principio_3" => [
        "title" => "Principio 3: Entendible",
        "subpages" => [
            "3.1_Leíble" => "Pauta 3.1: Hacer el texto del contenido leible y entendible",
            "3.2_Predecible" => "Pauta 3.2: El sistema debe ser operable y predecible en diferentes caminos",
            "3.3_Assistencia_en_los_input" => "Pauta 3.3: Ayudar al usuario con las indicaciones correcta en cada input"
        ]
    ],
    "Principio_4" => [
        "title" => "Principio 4: Robusto",
        "subpages" => [
            "4.1_Compatible" => "Pauta 4.1: Maximizar la comptibilidad con los actuales y futures agentes, incluyendo tecnologias asistivas"
        ]
    ],
    "Principio_5" => [
        "title" => "Principio 5: Conformidad",
        "subpages" => [
            "5.1_Normativa_de_requerimientos" => "Pauta 5.1: El contenido principal de las WCAG 2.2 es normativo y define los requisitos que afectan las declaraciones de conformidad. El material introductorio, los apéndices, las secciones marcadas como 'no normativas', los diagramas, los ejemplos y las notas son informativos (no normativos). El material no normativo proporciona información de asesoramiento para ayudar a interpretar las directrices, pero no crea requisitos que afecten una declaración de conformidad.",
            "5.2_Requisitos_de_conformidad" => "Pauta 5.2: Para que una página web cumpla con las WCAG 2.2, se deben cumplir con los requisitos de conformidad",
            "5.3_Reclamos_de_conformidad" => "Pauta 5.3: La conformidad se define solo para páginas web. Sin embargo, se puede realizar una reclamación de conformidad que cubra una página, una serie de páginas o varias páginas web relacionadas.",
            "5.4_Declaracion_De_conformidad_parcial_contenido_de_terceros" => "Pauta 5.4: Las páginas web a las que posteriormente se les agregará contenido adicional pueden utilizar una 'declaración de conformidad parcial'. Por ejemplo, un programa de correo electrónico, un blog, un artículo que permite a los usuarios agregar comentarios o aplicaciones que admitan contenido aportado por los usuarios. Otro ejemplo sería una página, como un portal o un sitio de noticias, compuesta por contenido agregado de múltiples contribuyentes, o sitios que insertan automáticamente contenido de otras fuentes a lo largo del tiempo, como cuando los anuncios se insertan dinámicamente. En estos casos, no es posible saber en el momento de la publicación original cuál será el contenido no controlado de las páginas. Es importante tener en cuenta que el contenido no controlado también puede afectar la accesibilidad del contenido controlado",
            "5.5_Lenguaje" => "Pauta 5.5: Se puede hacer una 'declaración de conformidad parcial debido al idioma' cuando la página no se ajusta, pero lo haría si existiera soporte de accesibilidad para (todos) los idiomas utilizados en la página. La forma de esa declaración sería: 'Esta página no cumple, pero cumpliría con las WCAG 2.2 en el nivel X si existiera soporte de accesibilidad para los siguientes idiomas'",
            "5.6_Consideraciones_de_privacidad" => "Pauta 5.6: A continuación se enumeran los criterios de éxito dentro de esta especificación que el Grupo de Trabajo ha identificado posibles implicaciones para la privacidad, ya sea al proporcionar protecciones para los usuarios finales o que son importantes para que los proveedores de sitios web los tengan en cuenta al implementar funciones diseñadas para proteger la privacidad del usuario. Esta lista refleja la comprensión actual del Grupo de Trabajo, pero otros criterios de Éxito pueden tener implicaciones de privacidad de las que el Grupo de Trabajo no tiene conocimiento al momento de su publicación.",
            "5.7_Consideraciones_de_seguridad" => "Pauta 5.7: Esta sección no es normativa. A continuación se enumeran los criterios de éxito dentro de esta especificación que el Grupo de Trabajo ha identificado posibles implicaciones para la seguridad, ya sea al proporcionar protecciones para los usuarios finales o que son importantes para que los proveedores de sitios web los tengan en cuenta al implementar funciones diseñadas para proteger la seguridad del usuario. Esta lista refleja la comprensión actual del Grupo de Trabajo, pero otros criterios de Éxito pueden tener implicaciones de seguridad de las que el Grupo de Trabajo no tiene conocimiento al momento de su publicación.",   
            ]
    ],
];

// Función para obtener un token de edición en MediaWiki
function getCsrfToken($wiki_url) {
    $params = [
        "action" => "query",
        "format" => "json",
        "meta" => "tokens"
    ];
    return makeRequest($wiki_url, $params)["query"]["tokens"]["csrftoken"] ?? null;
}

// Función para iniciar sesión en MediaWiki
function loginWiki($wiki_url, $username, $password) {
    // Paso 1: Obtener token de inicio de sesión
    $params1 = [
        "action" => "query",
        "format" => "json",
        "meta" => "tokens",
        "type" => "login"
    ];
    $response1 = makeRequest($wiki_url, $params1);
    $loginToken = $response1["query"]["tokens"]["logintoken"] ?? null;

    if (!$loginToken) {
        die("❌ Error: No se pudo obtener el token de login.");
    }

    // Paso 2: Enviar credenciales con el token de login
    $params2 = [
        "action" => "login",
        "format" => "json",
        "lgname" => $username,
        "lgpassword" => $password,
        "lgtoken" => $loginToken
    ];
    return makeRequest($wiki_url, $params2);
}

print_r(loginWiki($wiki_url, $username, $password));

// Función para crear páginas en MediaWiki
function createWikiPage($wiki_url, $title, $content, $token) {
    $edit_params = [
        "action" => "edit",
        "format" => "json",
        "title" => $title,
        "text" => $content,
        "token" => $token
    ];
    return makeRequest($wiki_url, $edit_params);
}

// Función para realizar peticiones a la API de MediaWiki
function makeRequest($url, $params) {
    global $cookie_file;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}

// INICIAR SESIÓN Y OBTENER TOKEN
loginWiki($wiki_url, $username, $password);
$token = getCsrfToken($wiki_url);
if (!$token) {
    die("❌ Error: No se pudo obtener el token de edición en MediaWiki.");
}
echo "🔹 Token obtenido: $token\n";

// CREAR PRINCIPIOS Y SUBPRINCIPIOS
foreach ($principios as $principio_id => $data) {
    $principio_title = $data["title"];
    $principio_content = "== $principio_title ==\n\nAquí se describen las pautas de este principio.\n\n";

    // Generar lista de subpáginas
    $principio_content .= "== Pautas del $principio_title ==\n";
    foreach ($data["subpages"] as $sub_id => $sub_content) {
        $principio_content .= "* [[{$principio_id}/{$sub_id}|$sub_content]]\n";
    }

    // Crear la página del principio con la lista de subpáginas
    createWikiPage($wiki_url, $principio_id, $principio_content, $token);
    echo "✅ Página creada: $principio_id\n";

    // Crear subpáginas
    foreach ($data["subpages"] as $sub_id => $sub_content) {
        $sub_title = "{$principio_id}/{$sub_id}";
        //ARREGLAR: Q es posi categoria Principio1//Principio2...
        $sub_page_content = "== $sub_content ==\n\n[[Category:" .   str_replace("_","",$principio_id). "]]";
        createWikiPage($wiki_url, $sub_title, $sub_page_content, $token);
        echo "✅ Subpágina creada: $sub_title\n";

        /*TODO -> Crear discusion por cada subpagina */
        // Crear la página de discusión asociada
            $talk_title = "Talk:$sub_title ejemplos accesibles";
            $talk_content = "== Ejemplos accesibles ==\n\nAquí puedes aportar ejemplos sobre esta pauta.";

            createWikiPage($wiki_url, $talk_title, $talk_content, $token);
            echo "✅ Página de discusión creada: $talk_title\n";
    }
}

echo "🎉 ¡Creación de principios y subprincipios completada!";
?>
