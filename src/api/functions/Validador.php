<?php

namespace Functions;
use stdClass;
use Database\MySQL;


class Validador
{


public static function sanitizeArrayRecursive(array $array, array $tipos = []): array {
    $output = [];

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            // Recursão para sub-arrays
            $output[$key] = self::sanitizeArrayRecursive($value, $tipos);
        } else {
            // Detecta tipo do campo
            $tipo = $tipos[$key] ?? null;

            // Sanitiza com base no tipo
            $valor = self::sanitizaValor((string)$value, $tipo);

          
             // Bloqueia valores suspeitos para SQL injection (exceto base64)
            if ($tipo !== 'base64' && preg_match("/['\";]/", $valor)) {
                $valor = ''; // ou lançar exception
            }

            $output[$key] = $valor;
        }
    }

    return $output;
}
    /**
     * Normaliza uma string de forma básica, removendo caracteres de controle e espaços.
     */
    public static function normalize_basic(?string $v): string
    {
        $v = (string)$v;
        // Se a função normalize_unicode não estiver definida no seu ambiente,
        // pode ser necessário implementar uma alternativa ou remover a chamada.
        // Ex: $v = normalizer_normalize($v, Normalizer::FORM_C);
        // Ex: $v = normalize_unicode($v);
        $v = preg_replace('/[^\P{C}\t\r\n]/u', '', $v) ?? $v;
        $v = trim($v);
        return $v;
    }

    /**
     * Retorna apenas os dígitos de uma string.
     */
    public static function only_digits(string $v): string
    {
        return preg_replace('/\D+/', '', $v) ?? '';
    }

    /**
     * Sanitiza um valor por tipo de campo.
     */
    public static function sanitizaValor(string $valor, ?string $tipo = null): string
    {
        $valor = str_replace("'", "", self::normalize_basic($valor));
        $valor = str_replace('"', '', ($valor));
        $valor = str_replace("*", '', ($valor));

        switch ($tipo) {
              case 'base64':
                // Apenas caracteres válidos do base64
                return preg_replace('/[^A-Za-z0-9=+\/]/', '', $valor);
            case 'cpf':
                return substr(self::only_digits($valor), 0, 11);
            case 'cnpj':
                return substr(self::only_digits($valor), 0, 14);
            case 'cep':
                return substr(self::only_digits($valor), 0, 8);
            case 'telefone':
                return substr(self::only_digits($valor), 0, 11);
            case 'data':
                $v = preg_replace('/[^0-9\/\-]/', '', $valor) ?? '';
                return $v;
            case 'hora':
                $v = preg_replace('/[^0-9:]/', '', $valor) ?? '';
                return $v;
            case 'email':
                $v = filter_var($valor, FILTER_SANITIZE_EMAIL) ?: '';
                return $v;
            case 'url':
                $v = filter_var($valor, FILTER_SANITIZE_URL) ?: '';
                return $v;
            case 'numero':
                return self::only_digits($valor);
            default:
                return strip_tags($valor);
        }
    }

    /**
     * Valida um CPF.
     */
    public static function validaCPF(string $cpf): bool
    {
        $cpf = self::only_digits($cpf);
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    /**
     * Valida um CNPJ.
     */
    public static function validar_cnpj(string $cnpj): bool
    {
        $cnpj = self::only_digits($cnpj);
        if (strlen($cnpj) !== 14 || preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += intval($cnpj[$i]) * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        $dv1 = $resto < 2 ? 0 : 11 - $resto;

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += intval($cnpj[$i]) * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        $dv2 = $resto < 2 ? 0 : 11 - $resto;

        return (intval($cnpj[12]) === $dv1) && (intval($cnpj[13]) === $dv2);
    }

    /**
     * Valida uma data (dd/mm/yyyy ou yyyy-mm-dd).
     */
    public static function validaData(string $data): bool
    {
        $data = trim($data);
        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $data, $m)) {
            return checkdate((int)$m[2], (int)$m[1], (int)$m[3]);
        }
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $data, $m)) {
            return checkdate((int)$m[2], (int)$m[3], (int)$m[1]);
        }
        return false;
    }

    /**
     * Valida uma hora (HH:MM ou HH:MM:SS).
     */
    public static function validaHora(string $hora): bool
    {
        return (bool)preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/', $hora);
    }

    /**
     * Sanitiza um valor por nome de campo.
     */
    public static function sanitizaCampo(string $name, string $valor): string
    {
        $tipos = [
            'fornecedor-cnpj' => 'cnpj',
            'fornecedor-cpf' => 'cpf',
            'fornecedor-cep' => 'cep',
            'fornecedor-telefone' => 'telefone',
            'fornecedor-email' => 'email',
            'fornecedor-inscricao' => 'cnpj',
            'fornecedor-data' => 'data',
            'fornecedor-hora' => 'hora',
            'fornecedor-numero' => 'numero',
            'fornecedor-url' => 'url',
        ];

        $tipo = $tipos[$name] ?? null;
        return self::sanitizaValor($valor, $tipo);
    }

    /**
     * Processa um array de dados, sanitizando e validando com base em um schema.
     * @return array|bool Retorna um array de erros ou os dados limpos se for bem-sucedido.
     */
    public static function processarDados(array $params, array $schema): array
    {
        $erros = [];
        $limpos = [];

        foreach ($schema as $campo => $rule) {
            $raw = isset($params[$campo]) ? (string)$params[$campo] : '';
            $san = '';

            // Verifica se é um campo obrigatório
            if ($rule['required'] && $raw === '') {
                $erros[$campo] = 'Campo obrigatório.';
                continue;
            }

            // Se o campo não é obrigatório e está vazio, pula para o próximo
            if ($raw === '') {
                $limpos[$campo] = '';
                continue;
            }

            // Sanitiza por nome e tipo
            $san = self::sanitizaCampo($campo, $raw);

            // Corta o tamanho máximo (defesa extra)
            if (isset($rule['max_len']) && mb_strlen($san) > $rule['max_len']) {
                $san = mb_substr($san, 0, $rule['max_len']);
            }

            // Validações por tipo
            switch ($rule['type']) {
                case 'cnpj':
                    if ($san !== '' && !self::validar_cnpj($san)) {
                        $erros[$campo] = 'CNPJ inválido.';
                    }
                    break;
                case 'cpf':
                    if ($san !== '' && !self::validaCPF($san)) {
                        $erros[$campo] = 'CPF inválido.';
                    }
                    break;
                case 'cep':
                    if ($san !== '' && !preg_match('/^\d{8}$/', $san)) {
                        $erros[$campo] = 'CEP inválido.';
                    }
                    break;
                case 'telefone':
                    if ($san !== '' && !preg_match('/^\d{10,11}$/', $san)) {
                        $erros[$campo] = 'Telefone inválido.';
                    }
                    break;
                case 'data':
                    if ($san !== '' && !self::validaData($san)) {
                        $erros[$campo] = 'Data inválida.';
                    }
                    break;
                case 'hora':
                    if ($san !== '' && !self::validaHora($san)) {
                        $erros[$campo] = 'Hora inválida.';
                    }
                    break;
                case 'email':
                    if ($san !== '' && !filter_var($san, FILTER_VALIDATE_EMAIL)) {
                        $erros[$campo] = 'Email inválido.';
                    }
                    break;
                case 'url':
                    if ($san !== '') {
                        $ok = filter_var($san, FILTER_VALIDATE_URL);
                        $scheme = parse_url($san, PHP_URL_SCHEME);
                        if (!$ok || !in_array($scheme, ['http', 'https'], true)) {
                            $erros[$campo] = 'URL inválida.';
                        }
                    }
                    break;
                case 'numero':
                    if ($san !== '' && !preg_match('/^\d+$/', $san)) {
                        $erros[$campo] = 'Número inválido.';
                    }
                    break;
            }

            // Adiciona o valor limpo apenas se não houver erro
            if (!isset($erros[$campo])) {
                $limpos[$campo] = $san;
            }
        }

        if (!empty($erros)) {
            return ['erros' => $erros, 'limpos' => $limpos];
        }

        return ['erros' => [], 'limpos' => $limpos];
    }
}