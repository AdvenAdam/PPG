$(document).ready(function() {
    $('#desa').change(function() {
        const id_desa = $(this).val()
        $.ajax({
            url: '/kelompok/get-by-desa/' + id_desa,
            type: 'GET',
            success: function(response) {
                $('#kelompok').prop('disabled', false);
                $('#kelompok').empty();
                $('#kelompok').append('<option value="">Pilih Kelompok</option>');
                response.forEach(function(data) {
                    $('#kelompok').append('<option value="' + data.id + '">' +
                        data.nama + '</option>');
                });
            }
        });
    });
});