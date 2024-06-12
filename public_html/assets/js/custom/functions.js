$(document).ready(function () {
    function limpa_formulário_cep() {
        // Limpa valores do formulário de cep.
        $("#rua").val("");
        $("#bairro").val("");
        $("#cidade").val("");
        $("#uf").val("");
        $("#ibge").val("");
    }

    //Quando o campo cep perde o foco.
    $("#cep").blur(function () {

        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if (validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $("#rua").val("...");
                $("#bairro").val("...");
                $("#cidade").val("...");
                $("#uf").val("...");
                $("#ibge").val("...");

                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#rua").val(dados.logradouro);
                        $("#bairro").val(dados.bairro);
                        $("#cidade").val(dados.localidade);
                        $("#uf").val(dados.uf);
                        //$("#ibge").val(dados.ibge);
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulário_cep();
                        alert("CEP não encontrado.");
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    });



    $("#cnpj").blur(function () {
        // Nova variável "cnpj" somente com dígitos.
        var cnpj = $(this).val().replace(/\D/g, '');

        // Verifica se o campo CNPJ possui valor informado.
        if (cnpj != "") {
            // Expressão regular para validar o formato do CNPJ.
            var validacnpj = /^[0-9]{14}$/;

            // Valida o formato do CNPJ.
            if (validacnpj.test(cnpj)) {
                // Função para validar o CNPJ com o algoritmo específico.
                if (validarCNPJ(cnpj)) {


                    //Consulta o webservice viacep.com.br/
                    $.getJSON("https://publica.cnpj.ws/cnpj/" + cnpj, function (dados) {
                        //Atualiza os campos com os valores da consulta.
                        $("#razaosocial").val(dados.razao_social)
                        $("#fantasia").val(dados.estabelecimento.nome_fantasia)
                        $("#rua").val(dados.estabelecimento.logradouro);
                        $("#complemento").val(dados.estabelecimento.complemento);
                        $("#cep").val(dados.estabelecimento.cep);
                        $("#bairro").val(dados.estabelecimento.bairro);
                        $("#cidade").val(dados.estabelecimento.cidade.nome);
                        $("#uf").val(dados.estabelecimento.estado.sigla);


                        $("#cnpj_raiz").text(dados.cnpj_raiz);
                        $("#razao_social").text(dados.razao_social);
                        $("#capital_social").text(dados.capital_social);
                        $("#porte").text(dados.porte.descricao);
                        $("#natureza_juridica").text(dados.natureza_juridica.descricao);
                        $("#qualificacao_responsavel").text(dados.qualificacao_do_responsavel.descricao);
                        $("#simples").text(dados.simples.simples);
                        $("#atividade_principal").text(dados.estabelecimento.atividade_principal.descricao);

                        const endereco = `${dados.estabelecimento.tipo_logradouro} ${dados.estabelecimento.logradouro}, ${dados.estabelecimento.numero}, ${dados.estabelecimento.bairro}, ${dados.estabelecimento.cidade.nome} - ${dados.estabelecimento.estado.sigla}, ${dados.estabelecimento.cep}`;
                        $("#endereco").text(endereco);

                        const contato = `Telefone: ${dados.estabelecimento.ddd1}-${dados.estabelecimento.telefone1}, Email: ${dados.estabelecimento.email}`;
                        $("#contato").text(contato);

                        dados.socios.forEach(socio => {
                            $("#socio_tbody").append(`
                <tr>
                    <td>${socio.cpf_cnpj_socio}</td>
                    <td>${socio.nome}</td>
                    <td>${socio.tipo}</td>
                    <td>${socio.data_entrada}</td>
                    <td>${socio.qualificacao_socio.descricao}</td>
                    <td>${socio.pais.nome}</td>
                </tr>
            `);
                        });

                        dados.estabelecimento.atividades_secundarias.forEach(atividade => {
                            $("#atividades_tbody").append(`
                <tr>
                    <td>${atividade.descricao}</td>
                </tr>
            `);
                        });

                        $("#dadosCnpj").modal('show')
                    });

                } else {
                    console.log("CNPJ inválido.");
                }
            } else {
                console.log("Formato de CNPJ inválido.");
            }
        } else {
            console.log("CNPJ não informado.");
        }
    });

    // Função para validar CNPJ utilizando o algoritmo de validação específico.
    function validarCNPJ(cnpj) {
        // Verificação de CNPJs inválidos conhecidos.
        if (cnpj == "00000000000000" || cnpj == "11111111111111" ||
            cnpj == "22222222222222" || cnpj == "33333333333333" ||
            cnpj == "44444444444444" || cnpj == "55555555555555" ||
            cnpj == "66666666666666" || cnpj == "77777777777777" ||
            cnpj == "88888888888888" || cnpj == "99999999999999") {
            return false;
        }

        // Validação dos dígitos verificadores.
        var tamanho = cnpj.length - 2
        var numeros = cnpj.substring(0, tamanho);
        var digitos = cnpj.substring(tamanho);
        var soma = 0;
        var pos = tamanho - 7;

        for (var i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }

        var resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0)) return false;

        tamanho = tamanho + 1;
        numeros = cnpj.substring(0, tamanho);
        soma = 0;
        pos = tamanho - 7;

        for (var i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }

        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1)) return false;

        return true;
    }
});