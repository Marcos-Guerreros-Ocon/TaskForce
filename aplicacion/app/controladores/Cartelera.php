<?php

class Cartelera extends Controlador
{

    private $data = array();
    public function __construct()
    {
        $sessionManager = new SessionManager();
        if (!$sessionManager->has('user')) {
            header('location:' . RUTA_URL . '/usuario');
        }
        if (!isset($_COOKIE['token'])) {
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
        }
        $this->data['pag_actual'] = 'cartelera';
    }

    public function index()
    {


        $this->vista('cartelera/index', $this->data);
    }


    function scrapeoMaximo()
    {

        $arrayCartelera = array();

        $selectorPelis = '//*[@id="cartelera0"]//div[contains(@class, "pelicula")]';
        $selectorTitulo = './/h2/a';
        $selectorHoras = '//div[contains(@class, "horas")]//strong';
        $cine = 'canas';
        $selectorCartel = './/div[contains(@class, "cartel")]//img';
        $this->obtieneCartelera($arrayCartelera, 'https://www.cineslascanas.com/', $selectorPelis, $selectorTitulo, $selectorHoras, $cine, $selectorCartel);


        $selectorPelis = '//*[contains(concat(" ", normalize-space(@class), " "), "row article-cartelera")]';
        $selectorTitulo = './/h2/a';
        $selectorHoras = './/*[contains(concat(" ", normalize-space(@class), " "), "tabs-performances")]//*[contains(concat(" ", normalize-space(@class), " "), "tab-pane") and contains(concat(" ", normalize-space(@class), " "), "fade") and contains(concat(" ", normalize-space(@class), " "), "show")]//*[contains(concat(" ", normalize-space(@class), " "), "pelicula")]//a';
        $cine = 'infantes';
        $selectorCartel = './/*[contains(@class, "img-cartelera")]';
        $selectorDia = './/*[contains(concat(" ", normalize-space(@class), " "), "active")]//strong';
        $this->obtieneCartelera($cartelera, "https://www.cines7infantes.com/cartelera", $selectorPelis, $selectorTitulo, $selectorHoras, $cine, $selectorCartel, $selectorDia);


        $selectorPelis = '//*[contains(concat(" ", normalize-space(@class), " "), "list-films__result")]';
        $selectorTitulo = './/*[contains(concat(" ", normalize-space(@class), " "), "film-title")]';
        $selectorHoras = './/*[contains(concat(" ", normalize-space(@class), " "), "cine-results")]//*[contains(concat(" ", normalize-space(@class), " "), "film-title")]//a';
        $cine = 'yelmo';
        $selectorCartel = './/*[contains(concat(" ", normalize-space(@class), " "), "film-cartel")]//img';
        $this->obtieneCartelera($cartelera, "https://www.cines7infantes.com/cartelera", $selectorPelis, $selectorTitulo, $selectorHoras, $cine, $selectorCartel, $selectorDia);

        usort($arrayCartelera, function ($a, $b) {
            return strcmp($a['titulo'], $b['titulo']);
        });

        return $arrayCartelera;
    }
    function obtieneCartelera(&$arrayCartelera, $url, $selectorPelis, $selectorTitulo, $selectorHoras, $cine, $selectorCartel, $selectorDia = null)
    {
        try {
            $response = file_get_contents($url);
            $html = $response;
            $doc = new DOMDocument();
            @$doc->loadHTML($html);

            $xpath = new DOMXPath($doc);


            $pelis = $xpath->query($selectorPelis);

            foreach ($pelis as $peli) {

                $horas = [];


                $horasElements = $xpath->query($selectorHoras, $peli);
                var_dump($horasElements);
                foreach ($horasElements as $horaElement) {
                    $horas[] = $horaElement->textContent;
                }

                var_dump($horas);
                die;

                $tituloElement = $xpath->query($selectorTitulo, $peli)->item(0);

                // Verificar si hay elementos small dentro del título
                $smallElements = $xpath->query('.//small', $tituloElement);
                if ($smallElements->length > 0) {
                    // Eliminar cada elemento small del título
                    foreach ($smallElements as $smallElement) {
                        $smallElement->parentNode->removeChild($smallElement);
                    }
                }

                // Obtener el texto después de eliminar los elementos small
                $titulo = $tituloElement->textContent;

                $selector = '//*[contains(concat(" ", normalize-space(@class), " "), "active")]//strong';

                $dia = $selectorDia ?  $xpath->query($selector, $peli)->item(0)->textContent : "";
                $cartel = !$selectorCartel ? "" : $xpath->query($selectorCartel, $peli)->item(0)->getAttribute('src');

                $insertar = $selectorDia === null || ($selectorDia && strtolower($dia) === "hoy");

                $titulo = strpos($titulo, "(") === false ? $titulo : substr($titulo, 0, strpos($titulo, "(") - 1);

                if ($insertar) {
                    $this->actualizarCartelera($arrayCartelera, $titulo, $cine, $horas, $cartel);
                }
            }
        } catch (Exception $error) {
            echo 'Error al realizar la solicitud (' . $cine . '): ' . $error->getMessage();
        }
    }
    function eliminaAcentos($cadena)
    {
        $acentos = array('á' => 'a', 'à' => 'a', 'ä' => 'a', 'é' => 'e', 'è' => 'e', 'ë' => 'e', 'í' => 'i', 'ì' => 'i', 'ï' => 'i', 'ó' => 'o', 'ò' => 'o', 'ö' => 'o', 'ú' => 'u', 'ù' => 'u', 'ü' => 'u');
        return str_replace(array_keys($acentos), array_values($acentos), $cadena);
    }

    function eliminaSimbolos($cadena)
    {
        $simbolos = array("(", ")", ".", ",", "-", ":", ";");
        return str_replace($simbolos, '', $cadena);
    }

    function eliminaEspacios($cadena)
    {
        return preg_replace('/\s/', '', $cadena);
    }

    function compararTitulos($titulo1, $titulo2)
    {
        $tit1 = $this->eliminaEspacios($this->eliminaSimbolos($this->eliminaAcentos(strtolower($titulo1))));
        $tit2 = $this->eliminaEspacios($this->eliminaSimbolos($this->eliminaAcentos(strtolower($titulo2))));
        $tit1 = strpos($tit1, "(") === false ? $tit1 : substr($tit1, 0, strpos($tit1, "(") - 1);
        $tit2 = strpos($tit2, "(") === false ? $tit2 : substr($tit2, 0, strpos($tit2, "(") - 1);
        return $tit1 === $tit2;
    }

    function actualizarCartelera(&$arrayCartelera, $titulo, $cine, $horas, $cartel)
    {
        $nombresCines = array('canas' => 'Las Cañas', 'infantes' => '7 infantes', 'yelmo' => 'Yelmo');
        $cine = $nombresCines[$cine];

        $peliculaExistente = null;

        foreach ($arrayCartelera as &$pelicula) {
            if ($this->compararTitulos($pelicula['titulo'], $titulo)) {
                $peliculaExistente = &$pelicula;
                break;
            }
        }

        $titulo = strpos($titulo, "(") === false ? $titulo : substr($titulo, 0, strpos($titulo, "(") - 1);

        if ($peliculaExistente) {
            $cineExistente = null;
            foreach ($peliculaExistente['cines'] as &$item) {
                if ($item['cine'] === $cine) {
                    $cineExistente = &$item;
                    break;
                }
            }

            if ($cineExistente) {
                $cineExistente['horas'] = array_merge($cineExistente['horas'], $horas);
            } else {
                array_push($peliculaExistente['cines'], array('cine' => $cine, 'horas' => $horas));
            }

            if ($cine !== "yelmo") {
                $peliculaExistente['cartel'] = $cartel;
            }
        } else {
            $arrayCartelera[] = array(
                'titulo' => $titulo,
                'cines' => array(array('cine' => $cine, 'horas' => $horas)),
                'cartel' => $cartel
            );
        }
    }
}
