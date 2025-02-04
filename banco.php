<?php

$clientes = [];
$contas = [];

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
    $TelefoneFiltrado = preg_replace("/[^0-9]/", "", $Telefone);

    if(strlen($TelefoneFiltrado) != 11 or !is_numeric($TelefoneFiltrado)){
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

        print("Confirmar " . $Tipo . ": " . $Resposta . "\n\n");

        print("[1] Confirmar\n");
        print("[2] Retornar\n");
        $Confirmacao = readLine("");

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

function mudarSaldo($MudancaDeSaldo, $Tipo, &$Saldo){
    if($Tipo == "SACAR"){
        if($MudancaDeSaldo > $Saldo){
            system("clear");
            print("Você não tem R$" . $MudancaDeSaldo . " para sacar de sua conta!\n\n");

            readline("[PRESSIONE ENTER PARA RETORNAR]");

            return;
        }

        $Saldo -= $MudancaDeSaldo;
    }else{
        $Saldo += $MudancaDeSaldo;
    }
}

function pegarQuantidadeDinheiro($Tipo, &$Saldo){
    while(true){
        system("clear");

        print("--- " . $Tipo . " DINHEIRO ---\n");
        print("Quanto dinheiro deseja " . $Tipo . "?\n\n");
        print("[Informe 0 para cancelar]\n");    

        $Resposta = readline("");

        if (strlen($Resposta) <= 0){ continue; }

        if (!is_numeric($Resposta)){ continue; }

        system("clear");

        if($Resposta > 0){
            while(true){
                system("clear");

                print("Confirmar valor: R$" . $Resposta . "\n\n");
                print("[1] Confirmar\n");
                print("[2] Retornar\n\n");

                $Confirmacao = readline("");

                if($Confirmacao == 1){
                    mudarSaldo($Resposta, $Tipo, $Saldo);
                    return;
                }elseif($Confirmacao == 2){
                    break;
                }
            }
        }elseif($Resposta == 0){
            break;
        }else{
            print("Valor inválido!\n\n");
            readline("[PRESSIONE ENTER PARA RETORNAR]");
        }
    }
}

function painelConta($Conta){
    $Saldo = $Conta['Saldo'];
    $NumeroConta = $Conta['NumeroConta'];

    while(true){
        system("clear");

        print("NÚMERO DA CONTA: $NumeroConta\n");
        print("Saldo: R$$Saldo\n\n");

        print("[1] Depositar dinheiro\n");
        print("[2] Sacar dinheiro\n\n");
        print("[3] Fechar banco\n");

        $Resposta = readline("");

        if($Resposta == 1){
            pegarQuantidadeDinheiro("DEPOSITAR", $Saldo);
        }elseif($Resposta == 2){
            pegarQuantidadeDinheiro("SACAR", $Saldo);
        }elseif($Resposta == 3){
            break;
        }
    }

    system("clear");
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
    $Telefone = perguntaCadastrar("Informe o seu telefone: ", "Telefone");
    $CPF = perguntaCadastrar("Informe o seu CPF: ", "CPF");

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
