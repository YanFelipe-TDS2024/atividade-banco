<?php

$clientes = [];
$contas = [];

$cliente = [
    "Nome" => "John Doe",
    "CPF" => "00000000000",
    "Telefone" => "(45)999999999"
];

$clientes[] = $cliente;

function digitoVerificador($CPF, $Final){
    $Soma = 0;
    for($i = 0; $i < $Final; $i++) {
        $Soma += $CPF[$i] * ($Final + 1 - $i);
    }

    $resto = $Soma%11;
    $digitoVerificador = 11 - $resto;

    if ($resto < 2) {
        $digitoVerificador = 0;
    }

    if ($CPF[$Final] == $digitoVerificador){
        return true;
    }
}

function validarCPF($CPFUnfiltered){
    $CPF = preg_replace("/[^0-9]/", "", $CPFUnfiltered);
    
    if(strlen($CPF) != 11){
        return false;
    }

    for($i = 9; $i <= 10; $i++) {
        if (!digitoVerificador($CPF, $i)) {
            print("2");
            return false;
        }
    }

    return true;
}

function validarTelefone($Telefone){
    if(strlen($Telefone) != 14){
        return false;
    }

    if(! ($Telefone[0] == "(" and $Telefone[3] == ")" and $Telefone[9]) == "-"){
        return false;
    }

    $TelefoneFiltrado = preg_replace("/[^0-9]/", "", $Telefone);

    if(strlen($TelefoneFiltrado) != 11){
        return false;
    }

    return true;
}

function perguntaCadastrar($Pergunta, $Tipo){
    while (true){
        system("clear");
        print("--- CADASTRAR USUÁRIO ---\n\n");
    
        $Resposta = readLine($Pergunta);
    
        system("clear");

        print("Confirmar resposta: " . $Resposta . "\n\n");

        $Confirmacao = readLine("[1] Confirmar   [2] Retornar   ");

        $Valido = true;

        if ($Tipo == "Telefone"){
           $Valido = validarTelefone($Resposta);
        }elseif($Tipo == "CPF"){
            $Valido = validarCPF($Resposta);
        }

        if ($Confirmacao == 1 and $Valido){
            return $Resposta; 
        }elseif($Confirmacao == 1 and !$Valido){
            system("clear");
            print("Você informou um $Tipo inválido!\n\n");
            readLine("[PRESSIONE ENTER PARA TENTAR NOVAMENTE]");
        }
    }
}

function painelConta($Conta){
    $Saldo = $Conta['Saldo'];
    $NumeroConta = $Conta['NumeroConta'];

    while(true){
        system("clear");

        print($Saldo);

        print("NÚMERO DA CONTA: $NumeroConta\n");
        print("Saldo: R$$Saldo\n\n");
        readline("");
    }
}

function cadastrarConta($CPF){
    $Conta = [
        "NumeroConta" => uniqid(),
        "CPF" => $CPF,
        "Saldo" => 0
    ];

    $contas[] = $Conta;

    return $Conta;
}

function cadastrarCliente(){
    $Nome = perguntaCadastrar("Informe o seu nome: ", "Nome");
    $Telefone = perguntaCadastrar("Informe o seu telefone:   Formato: (DDD)00000-0000    ", "Telefone");
    $CPF = perguntaCadastrar("Informe o seu CPF:    ", "CPF");

    $Cliente = [
        "Nome" => $Nome,
        "Telefone" => $Telefone,
        "CPF" => $CPF,
        "NumeroConta" => uniqid()
    ];

    $clientes[] = $Cliente;

    $NumeroConta = cadastrarConta($CPF);

    painelConta($NumeroConta);
}
cadastrarCliente();
