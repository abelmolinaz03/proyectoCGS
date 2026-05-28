<?php

/**
 * Valida que los valores de texto no superen la longitud máxima de sus columnas.
 *
 * @param array $campos Array de arrays con la forma: [valor, max_chars, etiqueta]
 * @return array Lista de mensajes de error (vacía si todo es válido)
 */
function validar_longitudes(array $campos): array {
    $errores = [];
    foreach ($campos as [$valor, $max, $etiqueta]) {
        if (mb_strlen($valor) > $max) {
            $errores[] = "\"$etiqueta\" no puede superar los $max caracteres.";
        }
    }
    return $errores;
}