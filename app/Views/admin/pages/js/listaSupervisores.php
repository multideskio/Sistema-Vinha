<script>
    $(document).ready(function() {
        $('#selectSupervisor').empty();
        $('#selectSupervisor').removeAttr('required');
        var data = {};
        $.getJSON(_baseUrl + "api/v1/supervisores/list", data, function(data, textStatus, jqXHR) {
            // Itera sobre os dados e adiciona as opções ao select
            $.each(data, function(index, supervisores) {
                var option = `<option value="${supervisores.id}">${supervisores.id} - ${supervisores.nome} ${supervisores.sobrenome}</option>`;
                $('#selectSupervisor').append(option);
            });
            // Adiciona os atributos 'required' e 'data-choices' ao elemento <select>
            $('#selectSupervisor').attr('required');
            $('#selectSupervisor').attr('data-choices', true);
            // Inicializa o Choices.js no elemento <select>
            new Choices('#selectSupervisor');
        });
    });
</script>