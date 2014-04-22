<?php

require_once 'mpdf.php';

/**
 * Classe criada para extender funcionalidades da classe mpdf
 *
 * @author Daniel Alcântara
 * @date 13/11/2013
 */
class ImpressorPDF extends mPDF {

    const _LIMITE_CARACTERES = 50000;

    public function imprimeHtmlPdf($html) {
        // Resolvendo problema de limite de caracteres do mpdf
        $iniSubstr = 0;
        $limiteRepeticoes = (int) (strlen($html) / self::_LIMITE_CARACTERES);
        for ($cont = 0; $cont < $limiteRepeticoes; $cont++) {
            $this->WriteHTML(substr($html, $iniSubstr, self::_LIMITE_CARACTERES), 2);
            $iniSubstr += self::_LIMITE_CARACTERES;
        }
        $this->WriteHTML(substr($html, $iniSubstr), 2);
    }

}
