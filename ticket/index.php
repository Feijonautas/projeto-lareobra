<style>
    .tickets-table{
        width: 90%;
        margin: 0 auto;
        color: #333;
    }
    .tickets-table .table-head{
        background-color: #eee;
    }
    .tickets-table thead td{
        padding: 10px;
    }
    .tickets-table tbody td{
        padding: 10px;
        border: 1px solid #eee;
    }
    .tickets-table .table-link{
        color: #333;
    }

    .responsive{
        display: none;
    }
    @media screen and (max-width: 600px) {
        .no-responsive{
            display: none;
        }
        .responsive{
            display: block;
        }
        .ticket-line{
            margin-bottom: 15px;
        }
    }
</style>
<br>
<h1 align=center>Meus Tickets</h1>
<?php
$emailSessao = null;
$senhaSessao = null;
$cls_conta = new MinhaConta();
if(isset($_SESSION["minha_conta"])){
    $emailSessao = $_SESSION["minha_conta"]["email"];
    $senhaSessao = $_SESSION["minha_conta"]["senha"];
}

if($cls_conta->auth($emailSessao, $senhaSessao)){
?>
<table class="tickets-table" cellspacing=0>
    <tr>
        <td colspan="7">Registrar novo ticket: <a href="ticket/adicionar/" class="link-padrao">adicionar</a></td>
    </tr>
    <tr class="table-head no-responsive">
        <td>Referência</td>
        <td>Assunto</td>
        <td>Departamento</td>
        <td>Enviado</td>
        <td>Prioridade</td>
        <td>Status</td>
        <td align=center>Ver</td>
    </tr>
    <tbody>
    <?php
        require_once "@pew/pew-system-config.php";
        require_once "@pew/@classe-system-functions.php";
    
        $idConta = $cls_conta->query_minha_conta("md5(email) = '$emailSessao' and senha = '$senhaSessao'");
    
        $condicao = "id_cliente = '$idConta'";
        $contar = mysqli_query($conexao, "select count(id) as total from tickets_register where $condicao");
        $contagem = mysqli_fetch_assoc($contar);
        if($contagem['total'] > 0){
            $query = mysqli_query($conexao, "select * from tickets_register where $condicao");
            while($infoTicket = mysqli_fetch_array($query)){
                $dataCompleta = $infoTicket["data_controle"];
                $dataAno = substr($dataCompleta, 0, 10);
                $dataAno = $pew_functions->inverter_data($dataAno);
                $dataHorario = substr($dataCompleta, 11);

                switch($infoTicket["priority"]){
                    case 1:
                        $prioridade = "Média";
                        break;
                    case 2:
                        $prioridade = "Urgente";
                        break;
                    default:
                        $prioridade = "Normal";
                }

                switch($infoTicket["status"]){
                    case 0:
                        $status = "Fechado";
                        break;
                    case 2:
                        $status = "Aguardando resposta do cliente";
                        break;
                    default:
                        $status = "Aguardando resposta do atendente";
                        break;
                }
                
                echo "<tr class='ticket-line'>";
                    echo "<td class='no-responsive'>#{$infoTicket['ref']}</td>";
                    echo "<td class='no-responsive'>{$infoTicket['topic']}</td>";
                    echo "<td class='no-responsive'>{$infoTicket['department']}</td>";
                    echo "<td class='no-responsive'>$dataAno</td>";
                    echo "<td class='no-responsive'>$prioridade</td>";
                    echo "<td class='no-responsive'>$status</td>";
                    echo "<td class='no-responsive' align=center><a href='ticket/interna/{$infoTicket['ref']}/' class='link-padrao table-link'><i class='fas fa-eye'></i></a></td>";
                    echo "<td class='responsive'>Referencia: #{$infoTicket['ref']}</td>";
                    echo "<td class='responsive'>Assunto: {$infoTicket['topic']}</td>";
                    echo "<td class='responsive'>Departamento: {$infoTicket['department']}</td>";
                    echo "<td class='responsive'>Enviado: $dataAno</td>";
                    echo "<td class='responsive'>Prioridade: $prioridade</td>";
                    echo "<td class='responsive'>Status: $status</td>";
                    echo "<td class='responsive' style='text-align: center; margin-bottom: 35px'><a href='ticket/interna/{$infoTicket['ref']}/' class='link-padrao table-link' style='font-weight: bold; color: #0a5209; font-size: 18px;'>Ver tudo</a></td>";
                echo "</tr>";
            }
        }else{
            echo "<tr><td colspan=7><font style='color: #666;'>Nenhum ticket foi registrado.</font></td></tr>";
        }
    ?>
    </tbody>
</table>
<?php
}else{
    echo "<h3 align=center>Faça login para continuar</h3>";
}
?>