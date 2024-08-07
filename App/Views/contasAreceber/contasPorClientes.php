<!--Usando o Html Components-->
<?php use System\HtmlComponents\Modal\Modal; ?>

<style type="text/css">
    .desativado {
        color: #cc0033;
    }
</style>

<div class="row">

    <div class="card col-lg-12 content-div">
        <div class="card-body">
            <h5 class="card-title"><i class="fas fa-handshake"></i> Contas a receber / <i class="fas fa-user-tie"></i> Clientes</h5>
        </div>

        <?php if (count($clientes) > 0): ?>
            <table id="example" class="table tabela-ajustada table-striped" style="width:100%">
                <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th style="text-align:right;padding-right:0">
                        <?php $rota = BASEURL . '/cliente/modalFormulario'; ?>
                        <button onclick="modalFormularioClientes('<?php echo $rota; ?>', false);"
                                class="btn btn-sm btn-success" title="Novo Cliente">
                            <i class="fas fa-plus"></i>
                        </button>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?php echo $cliente->nome; ?></td>
                        <td>
                            <?php
                            echo 'R$ ' . real($cliente->valor);
                            ?>
                        </td>

                      

                        

                        <td style="text-align:right">
                            <div class="btn-group" role="group">

                                <button id="btnGroupDrop1" type="button"
                                        class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cogs"></i>
                                </button>

                                
                                    <a class="dropdown-item" href="<?php echo BASEURL;?>/contasAreceber/cliente/<?php echo $cliente->id;?>">      
                                        <i class="fas fa-ayes"></i> Visualizar mais...
                                    </a>
                                
                            </div>

                        </td>
                    </tr>
                <?php endforeach; ?>
                <tfoot></tfoot>
            </table>

        <?php else: ?>
            <center>
                <i class="far fa-grin-beam" style="font-size:50px;opacity:0.60"></i> <br> <br>
                Poxa, ainda não há nenhum Cliente cadastrado! <br>
                <?php $rota = BASEURL . '/cliente/modalFormulario'; ?>
                <button
                    onclick="modalFormularioClientes('<?php echo $rota; ?>', null);"
                    class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i>
                    Cadastrar Cliente
                </button>
            </center>
        <?php endif; ?>

        <br>

    </div>
</div>

<!--Modal Clientes-->
<?php Modal::start([
    'id' => 'modalClientes',
    'width' => 'modal-lg',
    'title' => 'Cadastrar Clientes'
]); ?>
<div id="formulario"></div>
<?php Modal::stop(); ?>

<!--Modal Desativar e ativar Clientes-->
<?php Modal::start([
    'id' => 'modalDesativarCliente',
    'width' => 'modal-sm',
    'title' => '<i class="fas fa-user-tie" style="color:#ad54da"></i>'
]); ?>
<div id="modalConteudo">
    <p id="nomeCliente"></p>

    <center>
        <set-modal-button class="set-modal-button"></set-modal-button>
        <button class="btn btn-sm btn-default" data-dismiss="modal">
            <i class="fas fa-window-close"></i> Não
        </button>
    </center>
</div>
<?php Modal::stop(); ?>

<!--Modal Visualizar endereços dos Clientes-->
<?php Modal::start([
    'id' => 'modalVisualizarEnderecos',
    'width' => 'modal-lg',
    'title' => 'Endereços'
]); ?>
<div id="containerModalVisualizarEnderecos"></div>
<?php Modal::stop(); ?>

<!--Modal Formulário de cadastro de endereços dos Clientes-->
<?php Modal::start([
    'id' => 'modalFormulario',
    'width' => 'modal-lg',
    'title' => 'Cadastrar Endereços'
]); ?>
<div id="modalEnderecoFormulario"></div>
<?php Modal::stop(); ?>

<script>
    function modalFormularioClientes(rota, id) {
        var url = "";

        if (id) {
            url = rota + "/" + id;
        } else {
            url = rota;
        }

        $("#formulario").html("<center><h3>Carregando...</h3></center>");
        $("#modalClientes").modal({backdrop: 'static'});
        $("#formulario").load(url);
    }

    function modalVisualizarEnderecos(rota, idCliente, nomeCliente) {
        var url = "";

        if (idCliente) {
            url = rota + "/" + idCliente;
        } else {
            url = rota;
        }

        $("#containerModalVisualizarEnderecos").html("<center><h3>Carregando...</h3></center>");
        $("#modalVisualizarEnderecos").modal({backdrop: 'static'});
        $("#modalVisualizarEnderecos .modal-title").html("<b>" + nomeCliente + "</b>");
        $("#containerModalVisualizarEnderecos").load(url);
    }

    function modalFormularioEndereco(rota, idCliente, id) {
        var url = "";

        if (id) {
            url = rota + "/" + idCliente + "/" + id;
        } else {
            url = rota + "/" + idCliente;
        }

        $("#modalEnderecoFormulario").html("<center><h3>Carregando...</h3></center>");
        $("#modalFormulario").modal({backdrop: 'static'});

        $("#modalEnderecoFormulario").load(url);
    }

    function modalAtivarEdesativarCliente(id, nome, operacao) {
        if (operacao == 'desativar') {
            $("#nomeCliente").html('Tem certeza que deseja desativar o cliente ' + nome + '?');
            $("set-modal-button").html('<button class="btn btn-sm btn-success" id="buttonDesativarCliente" data-id-cliente="" onclick="desativarCliente(this)"><i class="far fa-check-circle"></i> Sim</button>');

        } else if (operacao == 'ativar') {
            $("set-modal-button").html('<button class="btn btn-sm btn-success" id="buttonDesativarCliente" data-id-cliente="" onclick="ativarCliente(this)"><i class="far fa-check-circle"></i> Sim</button>');
            $("#nomeCliente").html('Você deseja ativar o cliente ' + nome + '?');
        }

        $("#modalDesativarCliente").modal({backdrop: 'static'});
        document.querySelector("#buttonDesativarCliente").dataset.idCliente = id;
    }

    function desativarCliente(elemento) {
        modalValidacao('Validação', 'Desativando Cliente...');
        id = elemento.dataset.idCliente;

        var rota = getDomain() + "/cliente/desativarCliente/" + id;
        $.get(rota, function (data, status) {
            var dados = JSON.parse(data);
            if (dados.status == true) {
                location.reload();
                //$("#modalDesativarCliente .close").click();
            }
        });
    }

    function ativarCliente(elemento) {
        modalValidacao('Validação', 'Ativando Cliente...');
        id = elemento.dataset.idCliente;

        var rota = getDomain() + "/cliente/ativarCliente/" + id;
        $.get(rota, function (data, status) {
            var dados = JSON.parse(data);
            if (dados.status == true) {
                location.reload();
                //$("#modalDesativarCliente .close").click();
            }
        });
    }
</script>
